<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstrumentCatalog;

class InstrumentController extends Controller
{
    public function index()
    {
        $instruments = InstrumentCatalog::with('section.parent')
            ->orderBy('order_index')
            ->orderBy('name')
            ->paginate(20);
        return view('admin.instruments.index', compact('instruments'));
    }

    public function create()
    {
        $sections = \App\Models\InstrumentSection::with('parent')
            ->orderBy('order_index')
            ->orderBy('name')
            ->get();
        return view('admin.instruments.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:instrument_catalogs,name'],
            'instrument_section_id' => ['nullable', 'exists:instrument_sections,id'],
            'order_index' => ['nullable', 'integer', 'min:0'],
            'type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'leave_reason' => ['nullable', 'string', 'max:255'],
        ]);

        // Si se selecciona cuerda y no se especificó type, inferir type de la cuerda superior o de la seleccionada
        $type = $request->type;
        if (!$type && $request->filled('instrument_section_id')) {
            $sec = \App\Models\InstrumentSection::find($request->instrument_section_id);
            $type = $sec?->parent ? $sec->parent->name : $sec?->name;
        }

        InstrumentCatalog::create([
            'name' => $request->name,
            'instrument_section_id' => $request->instrument_section_id,
            'order_index' => $request->input('order_index', 0),
            'type' => $type,
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
        $sections = \App\Models\InstrumentSection::with('parent')
            ->orderBy('order_index')
            ->orderBy('name')
            ->get();
        return view('admin.instruments.edit', compact('instrument', 'sections'));
    }

    public function update(Request $request, InstrumentCatalog $instrument)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:instrument_catalogs,name,' . $instrument->id],
            'instrument_section_id' => ['nullable', 'exists:instrument_sections,id'],
            'order_index' => ['nullable', 'integer', 'min:0'],
            'type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'leave_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $type = $request->type;
        if (!$type && $request->filled('instrument_section_id')) {
            $sec = \App\Models\InstrumentSection::find($request->instrument_section_id);
            $type = $sec?->parent ? $sec->parent->name : $sec?->name;
        }

        $instrument->update([
            'name' => $request->name,
            'instrument_section_id' => $request->instrument_section_id,
            'order_index' => $request->input('order_index', 0),
            'type' => $type,
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
