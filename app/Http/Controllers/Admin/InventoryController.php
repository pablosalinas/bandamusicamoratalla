<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InstrumentCatalog;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['instruments' => function($q) {
            $q->withPivot('id', 'serial_number', 'tipo_partitura', 'propiedad', 'is_active');
        }]);

        // Filters
        if (!$request->has('show_all')) {
            $query->where('is_active', true);
        }
        
        if ($request->filled('musician_id')) {
            $query->where('id', $request->musician_id);
        }

        $users = $query->get();

        $inventory = collect();

        foreach ($users as $user) {
            foreach ($user->instruments as $instrument) {
                // Apply filters
                if (!$request->has('show_all') && !$instrument->pivot->is_active) {
                    continue;
                }
                
                if ($request->filled('propiedad') && $instrument->pivot->propiedad !== $request->propiedad) {
                    continue;
                }

                $photos = \App\Models\InstrumentPhoto::where('musician_instrument_id', $instrument->pivot->id)->get();

                $inventory->push((object)[
                    'musician' => $user,
                    'instrument' => $instrument,
                    'pivot' => $instrument->pivot,
                    'photos' => $photos
                ]);
            }
        }

        $musiciansList = User::orderBy('name')->get();

        return view('admin.inventory.index', compact('inventory', 'musiciansList'));
    }
}
