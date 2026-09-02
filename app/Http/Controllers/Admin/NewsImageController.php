<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsActivity;
use App\Models\NewsImage;
use Illuminate\Http\Request;

class NewsImageController extends Controller
{
    public function store(Request $request, NewsActivity $news)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $file = $request->file('image');
        $ext = strtolower($file->getClientOriginalExtension());
        $filename = uniqid('news_') . '.' . $ext;

        $uploadDir = public_path('uploads/news');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $file->move($uploadDir, $filename);

        $maxSort = $news->newsImages()->max('sort_order') ?? 0;

        $news->newsImages()->create([
            'file_path' => $filename,
            'sort_order' => $maxSort + 1,
        ]);

        return back()->with('success', 'Imagen añadida a la noticia.');
    }

    public function update(Request $request, NewsImage $image)
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
        ]);

        $image->update($validated);

        return back()->with('success', 'Imagen actualizada.');
    }

    public function destroy(NewsImage $image)
    {
        $filePath = public_path('uploads/news/' . $image->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $image->delete();

        return back()->with('success', 'Imagen eliminada.');
    }
}
