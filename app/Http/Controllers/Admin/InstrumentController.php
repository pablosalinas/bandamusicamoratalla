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
            'type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'leave_reason' => ['nullable', 'string', 'max:255'],
        ]);

        InstrumentCatalog::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'leave_reason' => $request->leave_reason,
        ]);

        return redirect()->route('admin.instruments.index')->with('success', 'Instrumento añadido correctamente al catálogo.');
    }

    public function ajaxCreate(Request $request)
    {
        $request->validate([
            'instruments' => ['required', 'array'],
            'instruments.*.name' => ['required', 'string', 'max:255'],
            'instruments.*.type' => ['required', 'string', 'max:255'],
        ]);

        $created = [];
        foreach ($request->instruments as $instData) {
            // Verificar si ya existe para evitar duplicados exactos
            $existing = InstrumentCatalog::where('name', strtoupper(trim($instData['name'])))->first();
            if (!$existing) {
                $newInst = InstrumentCatalog::create([
                    'name' => strtoupper(trim($instData['name'])),
                    'type' => strtoupper(trim($instData['type'])),
                    'is_active' => true,
                ]);
                $created[] = $newInst;
            } else {
                $created[] = $existing;
            }
        }

        return response()->json(['success' => true, 'instruments' => $created]);
    }

    public function edit(InstrumentCatalog $instrument)
    {
        return view('admin.instruments.edit', compact('instrument'));
    }

    public function update(Request $request, InstrumentCatalog $instrument)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:instrument_catalogs,name,' . $instrument->id],
            'type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'leave_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $instrument->update([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'leave_reason' => $request->leave_reason,
        ]);

        return redirect()->route('admin.instruments.index')->with('success', 'Instrumento actualizado correctamente.');
    }

    public function destroy(InstrumentCatalog $instrument)
    {
        // Al tener onDelete('cascade') en las tablas pivot, se borrarán las asociaciones pero no los usuarios
        $instrument->delete();
        
        return redirect()->route('admin.instruments.index')->with('success', 'Instrumento eliminado del catálogo.');
    }
}
