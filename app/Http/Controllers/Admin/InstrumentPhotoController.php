<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstrumentPhoto;
use Illuminate\Support\Facades\Storage;

class InstrumentPhotoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'photo' => 'required|image|max:10240',
            'description' => 'nullable|string|max:255'
        ]);

        $path = $request->file('photo')->store('instrument_photos', 'public');
        \App\Services\ImageWatermarkService::applyWatermark(storage_path('app/public/' . $path));

        InstrumentPhoto::create([
            'inventory_id' => $request->inventory_id,
            'photo_path' => $path,
            'description' => $request->description
        ]);

        return back()->with('success', 'Foto subida correctamente.');
    }
    public function update(Request $request, InstrumentPhoto $photo)
    {
        $request->validate([
            'description' => 'nullable|string|max:255'
        ]);

        $photo->update([
            'description' => $request->description
        ]);

        return back()->with('success', 'Descripción de la foto actualizada.');
    }

    public function destroy(InstrumentPhoto $photo)
    {
        if ($photo->photo_path) {
            Storage::disk('public')->delete($photo->photo_path);
        }
        $photo->delete();

        return back()->with('success', 'Foto eliminada correctamente.');
    }
}
