<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstrumentPhoto;
use Illuminate\Support\Facades\Storage;

class InstrumentPhotoController extends Controller
{
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
