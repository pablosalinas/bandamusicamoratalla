<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstrumentBrand;
use Illuminate\Http\Request;

class InstrumentBrandController extends Controller
{
    public function index()
    {
        $brands = InstrumentBrand::orderBy('name')->get();
        return view('admin.instrument_brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:instrument_brands,name',
        ]);

        InstrumentBrand::create($request->all());

        return redirect()->route('admin.instrument-brands.index')->with('success', 'Marca de instrumento añadida.');
    }

    public function destroy(InstrumentBrand $instrumentBrand)
    {
        $instrumentBrand->delete();
        return redirect()->route('admin.instrument-brands.index')->with('success', 'Marca eliminada.');
    }
}
