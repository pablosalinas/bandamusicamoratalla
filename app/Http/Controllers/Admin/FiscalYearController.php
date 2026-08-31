<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FiscalYear;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FiscalYearController extends Controller
{
    public function index()
    {
        $fiscalYears = FiscalYear::orderBy('start_date', 'desc')->get();
        return view('admin.fiscal_years.index', compact('fiscalYears'));
    }

    public function create()
    {
        return view('admin.fiscal_years.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        FiscalYear::create($request->only('name', 'start_date', 'end_date'));

        return redirect()->route('admin.fiscal-years.index')->with('success', 'Ejercicio económico creado correctamente.');
    }

    public function show(FiscalYear $fiscalYear, Request $request)
    {
        $query = $fiscalYear->movements();
        
        $sortBy = $request->query('sort', 'date');
        
        if ($sortBy === 'date') {
            $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        } elseif ($sortBy === 'type') {
            $query->orderBy('type')->orderBy('date', 'desc');
        }
        
        $movements = $query->paginate(20);
        
        return view('admin.fiscal_years.show', compact('fiscalYear', 'movements', 'sortBy'));
    }

    public function edit(FiscalYear $fiscalYear)
    {
        return view('admin.fiscal_years.edit', compact('fiscalYear'));
    }

    public function update(Request $request, FiscalYear $fiscalYear)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_closed' => 'boolean',
        ]);

        $fiscalYear->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_closed' => $request->has('is_closed'),
        ]);

        return redirect()->route('admin.fiscal-years.index')->with('success', 'Ejercicio económico actualizado.');
    }

    public function destroy(FiscalYear $fiscalYear)
    {
        if ($fiscalYear->movements()->count() > 0) {
            return back()->withErrors('No se puede eliminar un ejercicio que tiene movimientos presupuestarios.');
        }
        
        $fiscalYear->delete();
        return redirect()->route('admin.fiscal-years.index')->with('success', 'Ejercicio eliminado.');
    }

    public function report(FiscalYear $fiscalYear)
    {
        $movements = $fiscalYear->movements()->orderBy('date', 'asc')->get();
        return view('admin.fiscal_years.report', compact('fiscalYear', 'movements'));
    }

    public function importBalance(FiscalYear $fiscalYear)
    {
        if ($fiscalYear->is_closed) {
            return back()->withErrors('No se puede importar el saldo en un ejercicio cerrado.');
        }

        // Find the most recent fiscal year before this one
        $previousYear = FiscalYear::where('end_date', '<=', $fiscalYear->start_date)
            ->where('id', '!=', $fiscalYear->id)
            ->orderBy('end_date', 'desc')
            ->first();

        if (!$previousYear) {
            return back()->withErrors('No se ha encontrado un ejercicio anterior válido para importar el saldo.');
        }

        $totalIncome = $previousYear->total_income;
        $totalExpense = $previousYear->total_expense;

        if ($totalIncome == 0 && $totalExpense == 0) {
            return back()->with('success', 'El ejercicio anterior no tuvo movimientos. No se ha importado nada.');
        }

        // Check if a carry over movement already exists
        $exists = $fiscalYear->movements()->where('description', 'like', 'Ingresos del Ejercicio Anterior%')->exists();
        if ($exists) {
            return back()->withErrors('Los importes del ejercicio anterior ya fueron importados.');
        }

        if ($totalIncome > 0) {
            $fiscalYear->movements()->create([
                'date' => $fiscalYear->start_date,
                'type' => 'income',
                'description' => 'Ingresos del Ejercicio Anterior (' . $previousYear->name . ')',
                'amount' => $totalIncome,
                'is_reconciled' => true,
            ]);
        }

        if ($totalExpense > 0) {
            $fiscalYear->movements()->create([
                'date' => $fiscalYear->start_date,
                'type' => 'expense',
                'description' => 'Gastos del Ejercicio Anterior (' . $previousYear->name . ')',
                'amount' => $totalExpense,
                'is_reconciled' => true,
            ]);
        }

        return back()->with('success', 'Se han importado los totales del ejercicio anterior: Ingresos (' . number_format($totalIncome, 2, ',', '.') . ' €) y Gastos (' . number_format($totalExpense, 2, ',', '.') . ' €).');
    }
}
