<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InstrumentCatalog;
use App\Models\InstrumentBrand;
use App\Models\Inventory;
use App\Models\InventoryMovement;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with(['instrument', 'brand', 'currentUser', 'photos']);

        // Filters
        if ($request->filled('musician_id')) {
            $query->where('user_id', $request->musician_id);
        }
        
        if ($request->filled('propiedad')) {
            $query->where('propiedad', $request->propiedad);
        }

        if ($request->filled('status')) {
            if ($request->status === 'assigned') {
                $query->whereNotNull('user_id')->where('is_active', true);
            } elseif ($request->status === 'available') {
                $query->whereNull('user_id')->where('is_active', true);
            }
        }

        if (!$request->has('show_inactive')) {
            $query->where('is_active', true);
        }

        $inventory = $query->orderBy('created_at', 'desc')->get();
        $musiciansList = User::orderBy('name')->get();

        return view('admin.inventory.index', compact('inventory', 'musiciansList'));
    }

    public function create()
    {
        $catalogs = InstrumentCatalog::orderBy('name')->get();
        $brands = InstrumentBrand::orderBy('name')->get();
        $musicians = User::orderBy('name')->get();

        return view('admin.inventory.create', compact('catalogs', 'brands', 'musicians'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'instrument_catalog_id' => 'required|exists:instrument_catalogs,id',
            'instrument_brand_id' => 'nullable|exists:instrument_brands,id',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'propiedad' => 'required|in:banda,musico',
            'status' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'tipo_partitura' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $data['is_active'] = $request->has('is_active');
        $inventory = Inventory::create($data);

        if ($inventory->user_id) {
            InventoryMovement::create([
                'inventory_id' => $inventory->id,
                'from_user_id' => null,
                'to_user_id' => $inventory->user_id,
                'type' => 'assigned',
                'notes' => 'Asignación inicial al crear.'
            ]);
        }

        return redirect()->route('admin.inventory.index')->with('success', 'Instrumento registrado correctamente.');
    }

    public function edit(Inventory $inventory)
    {
        $catalogs = InstrumentCatalog::orderBy('name')->get();
        $brands = InstrumentBrand::orderBy('name')->get();
        $musicians = User::orderBy('name')->get();

        return view('admin.inventory.edit', compact('inventory', 'catalogs', 'brands', 'musicians'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $data = $request->validate([
            'instrument_catalog_id' => 'required|exists:instrument_catalogs,id',
            'instrument_brand_id' => 'nullable|exists:instrument_brands,id',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'propiedad' => 'required|in:banda,musico',
            'status' => 'required|string',
            'tipo_partitura' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $data['is_active'] = $request->has('is_active');
        $inventory->update($data);

        return redirect()->route('admin.inventory.index')->with('success', 'Instrumento actualizado correctamente.');
    }

    public function show(Inventory $inventory)
    {
        $inventory->load(['movements.fromUser', 'movements.toUser', 'photos']);
        $musicians = User::orderBy('name')->get();
        return view('admin.inventory.show', compact('inventory', 'musicians'));
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('admin.inventory.index')->with('success', 'Instrumento eliminado del inventario.');
    }

    // Custom actions for movements
    public function assign(Request $request, Inventory $inventory)
    {
        $request->validate(['user_id' => 'required|exists:users,id', 'notes' => 'nullable|string']);
        
        if ($inventory->user_id) {
            return back()->with('error', 'El instrumento ya está asignado.');
        }

        $inventory->update(['user_id' => $request->user_id]);
        
        InventoryMovement::create([
            'inventory_id' => $inventory->id,
            'from_user_id' => null,
            'to_user_id' => $request->user_id,
            'type' => 'assigned',
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Instrumento asignado correctamente.');
    }

    public function returnInstrument(Request $request, Inventory $inventory)
    {
        $request->validate(['notes' => 'nullable|string']);

        if (!$inventory->user_id) {
            return back()->with('error', 'El instrumento no está asignado actualmente.');
        }

        $oldUser = $inventory->user_id;
        $inventory->update(['user_id' => null]);
        
        InventoryMovement::create([
            'inventory_id' => $inventory->id,
            'from_user_id' => $oldUser,
            'to_user_id' => null,
            'type' => 'returned',
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Instrumento devuelto al inventario.');
    }

    public function transfer(Request $request, Inventory $inventory)
    {
        $request->validate(['user_id' => 'required|exists:users,id', 'notes' => 'nullable|string']);

        if (!$inventory->user_id) {
            return back()->with('error', 'El instrumento no está asignado actualmente. Usa la opción de Asignar.');
        }
        
        if ($inventory->user_id == $request->user_id) {
            return back()->with('error', 'El instrumento ya está asignado a este músico.');
        }

        $oldUser = $inventory->user_id;
        $newUser = $request->user_id;

        // 1. Return
        InventoryMovement::create([
            'inventory_id' => $inventory->id,
            'from_user_id' => $oldUser,
            'to_user_id' => null,
            'type' => 'returned',
            'notes' => 'Devolución automática por transferencia. ' . $request->notes,
            'created_at' => now()->subSecond(),
            'updated_at' => now()->subSecond(),
        ]);

        // 2. Assign
        $inventory->update(['user_id' => $newUser]);
        
        InventoryMovement::create([
            'inventory_id' => $inventory->id,
            'from_user_id' => null,
            'to_user_id' => $newUser,
            'type' => 'assigned',
            'notes' => 'Asignación por transferencia. ' . $request->notes
        ]);

        return back()->with('success', 'Instrumento transferido correctamente.');
    }

    public function pdf(Request $request)
    {
        $query = Inventory::with(['instrument', 'brand', 'currentUser']);

        if ($request->filled('musician_id')) {
            $query->where('user_id', $request->musician_id);
        }
        if ($request->filled('propiedad')) {
            $query->where('propiedad', $request->propiedad);
        }
        if (!$request->has('show_inactive')) {
            $query->where('is_active', true);
        }

        $inventory = $query->orderBy('created_at', 'desc')->get();
        return view('admin.inventory.pdf', compact('inventory'));
    }
}
