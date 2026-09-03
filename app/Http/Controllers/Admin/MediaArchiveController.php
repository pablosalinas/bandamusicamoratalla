<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaArchiveController extends Controller
{
    public function index()
    {
        $mediaArchives = MediaArchive::orderBy('sort_order')->get();
        return view('admin.media_archive.index', compact('mediaArchives'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:audio,video',
            'file' => [
                'required',
                'file',
                function ($attribute, $value, $fail) use ($request) {
                    $maxSize = $request->type === 'video' ? 20480 : 8192; // 20MB for video, 8MB for audio
                    if ($value->getSize() > $maxSize * 1024) {
                        $fail("El archivo no debe ser mayor de {$maxSize} KB.");
                    }
                    
                    $mime = $value->getMimeType();
                    if ($request->type === 'video' && !str_starts_with($mime, 'video/')) {
                        $fail("El archivo debe ser un vídeo válido.");
                    }
                    if ($request->type === 'audio' && !str_starts_with($mime, 'audio/')) {
                        $fail("El archivo debe ser un audio válido.");
                    }
                }
            ],
            'is_active' => 'boolean'
        ]);

        $path = $request->file('file')->store('media_archive', 'public');
        $maxOrder = MediaArchive::max('sort_order') ?? 0;

        MediaArchive::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'type' => $request->type,
            'is_active' => $request->has('is_active'),
            'sort_order' => $maxOrder + 1
        ]);

        return redirect()->route('admin.media-archive.index')->with('success', 'Archivo multimedia subido correctamente.');
    }

    public function update(Request $request, MediaArchive $mediaArchive)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $mediaArchive->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.media-archive.index')->with('success', 'Archivo actualizado correctamente.');
    }

    public function destroy(MediaArchive $mediaArchive)
    {
        if (Storage::disk('public')->exists($mediaArchive->file_path)) {
            Storage::disk('public')->delete($mediaArchive->file_path);
        }
        
        $mediaArchive->delete();

        return redirect()->route('admin.media-archive.index')->with('success', 'Archivo eliminado correctamente.');
    }

    public function updateOrder(Request $request, MediaArchive $mediaArchive)
    {
        $request->validate([
            'sort_order' => 'required|integer'
        ]);

        $mediaArchive->update([
            'sort_order' => $request->sort_order
        ]);

        return back()->with('success', 'Orden actualizado.');
    }
}
