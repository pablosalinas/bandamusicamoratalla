<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstrumentCatalog;

class InstrumentController extends Controller
{
    public function index()
    {
        $instruments = InstrumentCatalog::orderBy('name')->paginate(15);
        return view('admin.instruments.index', compact('instruments'));
    }

    public function create()
    {
        return view('admin.instruments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:instrument_catalogs,name'],
            'family' => ['nullable', 'string', 'max:255'],
        ]);

        InstrumentCatalog::create($request->only('name', 'family'));

        return redirect()->route('admin.instruments.index')->with('success', 'Instrumento añadido correctamente al catálogo.');
    }

    public function edit(InstrumentCatalog $instrument)
    {
        return view('admin.instruments.edit', compact('instrument'));
    }

    public function update(Request $request, InstrumentCatalog $instrument)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:instrument_catalogs,name,' . $instrument->id],
            'family' => ['nullable', 'string', 'max:255'],
        ]);

        $instrument->update($request->only('name', 'family'));

        return redirect()->route('admin.instruments.index')->with('success', 'Instrumento actualizado correctamente.');
    }

    public function destroy(InstrumentCatalog $instrument)
    {
        // Al tener onDelete('cascade') en las tablas pivot, se borrarán las asociaciones pero no los usuarios
        $instrument->delete();
        
        return redirect()->route('admin.instruments.index')->with('success', 'Instrumento eliminado del catálogo.');
    }
}
