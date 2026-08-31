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
}
