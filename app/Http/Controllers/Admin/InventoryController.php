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
        $query = Inventory::with(['instrument', 'brand', 'users', 'photos']);

        // Filters
        if ($request->filled('musician_id')) {
            $query->whereHas('users', function($q) use ($request) {
                $q->where('users.id', $request->musician_id);
            });
        }
        
        if ($request->filled('propiedad')) {
            if ($request->propiedad === 'banda') {
                $query->where('propiedad', 'like', '%banda%');
            } else {
                $query->where(function($q) {
                    $q->where('propiedad', 'not like', '%banda%')
                      ->orWhere('propiedad', 'like', '%propio%')
                      ->orWhere('propiedad', 'like', '%musico%')
                      ->orWhere('propiedad', 'like', '%músico%');
                });
            }
        }

        if ($request->filled('status')) {
            if ($request->status === 'assigned') {
                $query->whereHas('users')->where('is_active', true);
            } elseif ($request->status === 'available') {
                $query->whereDoesntHave('users')->where('is_active', true);
            }
        }

        if ($request->filled('verification')) {
            if ($request->verification === 'pending') {
                $query->where('is_verified', false);
            } elseif ($request->verification === 'verified') {
                $query->where('is_verified', true);
            }
        }

        if (!$request->has('show_inactive')) {
            $query->where('is_active', true);
        }

        $pendingCount = Inventory::where('is_verified', false)->count();

        $inventory = $query->orderBy('created_at', 'desc')->get();
        $musiciansList = User::orderBy('name')->get();

        return view('admin.inventory.index', compact('inventory', 'musiciansList', 'pendingCount'));
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
            'purchase_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string'
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['is_verified'] = true; // Admin registrations are verified by default
        $userId = $request->user_id;
        unset($data['user_id']); // No longer in the schema

        if ($request->hasFile('invoice')) {
            $data['invoice_path'] = $request->file('invoice')->store('invoices', 'public');
        }
        unset($data['invoice']);

        $inventory = Inventory::create($data);

        if ($userId) {
            $inventory->users()->attach($userId);
            InventoryMovement::create([
                'inventory_id' => $inventory->id,
                'from_user_id' => null,
                'to_user_id' => $userId,
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
            'purchase_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'is_verified' => 'nullable|boolean',
            'notes' => 'nullable|string'
        ]);

        $data['is_active'] = $request->has('is_active');
        if ($request->has('is_verified')) {
            $data['is_verified'] = (bool) $request->is_verified;
        }

        if ($request->hasFile('invoice')) {
            if ($inventory->invoice_path && \Storage::disk('public')->exists($inventory->invoice_path)) {
                \Storage::disk('public')->delete($inventory->invoice_path);
            }
            $data['invoice_path'] = $request->file('invoice')->store('invoices', 'public');
        }
        unset($data['invoice']);

        $inventory->update($data);

        return redirect()->route('admin.inventory.index')->with('success', 'Instrumento actualizado correctamente.');
    }

    public function verify(Inventory $inventory)
    {
        $inventory->update(['is_verified' => true]);
        return back()->with('success', 'Instrumento validado y aprobado para el inventario oficial correctamente.');
    }

    public function show(Inventory $inventory)
    {
        $inventory->load(['movements.fromUser', 'movements.toUser', 'photos', 'users']);
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
        
        if ($inventory->users()->where('users.id', $request->user_id)->exists()) {
            return back()->with('error', 'El instrumento ya está asignado a este músico.');
        }

        $inventory->users()->attach($request->user_id);
        
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
        $request->validate([
            'notes' => 'nullable|string',
            'user_id' => 'required|exists:users,id' // Especificamos qué músico lo devuelve
        ]);

        $userId = $request->user_id;

        if (!$inventory->users()->where('users.id', $userId)->exists()) {
            return back()->with('error', 'El instrumento no está asignado a este músico actualmente.');
        }

        $inventory->users()->detach($userId);
        
        InventoryMovement::create([
            'inventory_id' => $inventory->id,
            'from_user_id' => $userId,
            'to_user_id' => null,
            'type' => 'returned',
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Instrumento devuelto al inventario.');
    }

    public function transfer(Request $request, Inventory $inventory)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', // to user
            'from_user_id' => 'required|exists:users,id', // from user
            'notes' => 'nullable|string'
        ]);

        $oldUser = $request->from_user_id;
        $newUser = $request->user_id;

        if (!$inventory->users()->where('users.id', $oldUser)->exists()) {
            return back()->with('error', 'El instrumento no está asignado al músico de origen.');
        }
        
        if ($oldUser == $newUser || $inventory->users()->where('users.id', $newUser)->exists()) {
            return back()->with('error', 'El instrumento ya está asignado a este músico de destino.');
        }

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
        
        $inventory->users()->detach($oldUser);

        // 2. Assign
        $inventory->users()->attach($newUser);
        
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
        $query = Inventory::with(['instrument', 'brand', 'users']);

        if ($request->filled('musician_id')) {
            $query->whereHas('users', function($q) use ($request) {
                $q->where('users.id', $request->musician_id);
            });
        }
        if ($request->filled('propiedad')) {
            if ($request->propiedad === 'banda') {
                $query->where('propiedad', 'like', '%banda%');
            } else {
                $query->where(function($q) {
                    $q->where('propiedad', 'not like', '%banda%')
                      ->orWhere('propiedad', 'like', '%propio%')
                      ->orWhere('propiedad', 'like', '%musico%')
                      ->orWhere('propiedad', 'like', '%músico%');
                });
            }
        }
        if ($request->filled('status')) {
            if ($request->status === 'assigned') {
                $query->whereHas('users')->where('is_active', true);
            } elseif ($request->status === 'available') {
                $query->whereDoesntHave('users')->where('is_active', true);
            }
        }
        if (!$request->has('show_inactive')) {
            $query->where('is_active', true);
        }

        $inventory = $query->orderBy('created_at', 'desc')->get();
        return view('admin.inventory.pdf', compact('inventory'));
    }

    public function traceabilityPdf(Inventory $inventory)
    {
        $inventory->load(['instrument', 'brand', 'users', 'movements.fromUser', 'movements.toUser']);
        return view('admin.inventory.traceability_pdf', compact('inventory'));
    }
}
