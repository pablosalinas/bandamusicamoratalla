<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstrumentSection;

class InstrumentSectionController extends Controller
{
    public function index()
    {
        $mainSections = InstrumentSection::with(['children.instruments', 'instruments'])
            ->whereNull('parent_id')
            ->orderBy('order_index')
            ->orderBy('name')
            ->get();

        return view('admin.instrument_sections.index', compact('mainSections'));
    }

    public function create()
    {
        $mainSections = InstrumentSection::whereNull('parent_id')->orderBy('order_index')->orderBy('name')->get();
        return view('admin.instrument_sections.create', compact('mainSections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:instrument_sections,id'],
            'order_index' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        InstrumentSection::create([
            'name' => trim($request->name),
            'parent_id' => $request->filled('parent_id') ? $request->parent_id : null,
            'order_index' => $request->input('order_index', 0),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.instrument-sections.index')->with('success', 'Cuerda/Subcuerda creada correctamente.');
    }

    public function edit(InstrumentSection $instrumentSection)
    {
        $mainSections = InstrumentSection::whereNull('parent_id')
            ->where('id', '!=', $instrumentSection->id)
            ->orderBy('order_index')
            ->orderBy('name')
            ->get();

        return view('admin.instrument_sections.edit', compact('instrumentSection', 'mainSections'));
    }

    public function update(Request $request, InstrumentSection $instrumentSection)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:instrument_sections,id'],
            'order_index' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        // Evitar ser hijo de sí mismo
        $parentId = $request->filled('parent_id') ? $request->parent_id : null;
        if ($parentId == $instrumentSection->id) {
            $parentId = null;
        }

        $instrumentSection->update([
            'name' => trim($request->name),
            'parent_id' => $parentId,
            'order_index' => $request->input('order_index', 0),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.instrument-sections.index')->with('success', 'Cuerda/Subcuerda actualizada correctamente.');
    }

    public function destroy(InstrumentSection $instrumentSection)
    {
        $instrumentSection->delete();
        return redirect()->route('admin.instrument-sections.index')->with('success', 'Cuerda/Subcuerda eliminada.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:instrument_sections,id',
            'sections.*.order_index' => 'required|integer',
        ]);

        foreach ($request->sections as $sec) {
            InstrumentSection::where('id', $sec['id'])->update(['order_index' => $sec['order_index']]);
        }

        return response()->json(['success' => true]);
    }
}
