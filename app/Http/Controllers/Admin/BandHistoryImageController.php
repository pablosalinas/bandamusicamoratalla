<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BandHistoryImage;
use Illuminate\Http\Request;

class BandHistoryImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $file = $request->file('image');
        $ext = strtolower($file->getClientOriginalExtension());
        $filename = uniqid('bh_') . '.' . $ext;

        $uploadDir = public_path('uploads/band-history');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $file->move($uploadDir, $filename);

        $maxSort = BandHistoryImage::max('sort_order') ?? 0;

        BandHistoryImage::create([
            'file_path' => $filename,
            'sort_order' => $maxSort + 1,
        ]);

        return back()->with('success', 'Imagen añadida correctamente.');
    }

    public function update(Request $request, BandHistoryImage $image)
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
        ]);

        $image->update($validated);

        return back()->with('success', 'Imagen actualizada.');
    }

    public function destroy(BandHistoryImage $image)
    {
        $filePath = public_path('uploads/band-history/' . $image->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $image->delete();

        return back()->with('success', 'Imagen eliminada.');
    }
}
