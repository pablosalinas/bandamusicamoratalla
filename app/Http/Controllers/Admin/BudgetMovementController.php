<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BudgetMovement;
use App\Models\FiscalYear;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BudgetMovementController extends Controller
{
    public function create(FiscalYear $fiscalYear)
    {
        if ($fiscalYear->is_closed) {
            return back()->withErrors('No se pueden añadir movimientos a un ejercicio cerrado.');
        }
        return view('admin.budget_movements.create', compact('fiscalYear'));
    }

    public function store(Request $request, FiscalYear $fiscalYear)
    {
        if ($fiscalYear->is_closed) {
            return back()->withErrors('El ejercicio está cerrado.');
        }

        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = [
            'fiscal_year_id' => $fiscalYear->id,
            'date' => $request->date,
            'type' => $request->type,
            'description' => $request->description,
            'amount' => $request->amount,
            'is_reconciled' => $request->has('is_reconciled'),
        ];

        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')->store('budget_documents', 'public');
            // Auto reconcile if document is uploaded, if desired by UI logic, but user can override. 
            // We'll trust the explicit checkbox `is_reconciled`.
        }

        BudgetMovement::create($data);

        return redirect()->route('admin.fiscal-years.show', $fiscalYear)->with('success', 'Movimiento registrado correctamente.');
    }

    public function edit(FiscalYear $fiscalYear, BudgetMovement $movement)
    {
        return view('admin.budget_movements.edit', compact('fiscalYear', 'movement'));
    }

    public function update(Request $request, FiscalYear $fiscalYear, BudgetMovement $movement)
    {
        if ($fiscalYear->is_closed) {
            return back()->withErrors('El ejercicio está cerrado.');
        }

        $wasReconciled = $movement->is_reconciled;
        $isReconciledNow = $request->has('is_reconciled');

        // If it was reconciled and they are un-reconciling it
        if ($wasReconciled && !$isReconciledNow) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'unreconcile_movement',
                'description' => "Quitado el punteo del movimiento #{$movement->id} ({$movement->description})",
                'ip_address' => $request->ip(),
            ]);
            
            // Only update the reconciliation status if that's the only thing they can do
            $movement->update(['is_reconciled' => false]);
            return redirect()->route('admin.budget-movements.edit', [$fiscalYear, $movement])
                ->with('success', 'Punteo quitado. Ahora puedes modificar el resto de campos.');
        }

        if ($wasReconciled && $isReconciledNow) {
            // Cannot edit fields if it's still reconciled
            return back()->withErrors('No puedes modificar un movimiento punteado. Quita el punteo primero.');
        }

        // Normal update for unreconciled movement
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = [
            'date' => $request->date,
            'type' => $request->type,
            'description' => $request->description,
            'amount' => $request->amount,
            'is_reconciled' => $isReconciledNow,
        ];

        if ($request->hasFile('document')) {
            if ($movement->document_path) {
                Storage::disk('public')->delete($movement->document_path);
            }
            $data['document_path'] = $request->file('document')->store('budget_documents', 'public');
        } elseif ($request->has('remove_document')) {
            if ($movement->document_path) {
                Storage::disk('public')->delete($movement->document_path);
            }
            $data['document_path'] = null;
        }

        $movement->update($data);

        return redirect()->route('admin.fiscal-years.show', $fiscalYear)->with('success', 'Movimiento actualizado.');
    }

    public function destroy(FiscalYear $fiscalYear, BudgetMovement $movement)
    {
        if ($movement->is_reconciled) {
            return back()->withErrors('No se puede eliminar un movimiento punteado.');
        }

        if ($movement->document_path) {
            Storage::disk('public')->delete($movement->document_path);
        }
        
        $movement->delete();

        return redirect()->route('admin.fiscal-years.show', $fiscalYear)->with('success', 'Movimiento eliminado.');
    }
}
