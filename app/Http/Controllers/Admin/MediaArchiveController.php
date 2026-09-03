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
        $mediaArchives = MediaArchive::with('images')->orderBy('sort_order')->get();
        $dbTypes = \App\Models\SheetMusic::whereNotNull('work_type')->where('work_type', '!=', '')->distinct()->pluck('work_type')->toArray();
        $defaultTypes = ['Pasodoble', 'Marcha Mora', 'Marcha Cristiana', 'Marcha de Procesión', 'Obra', 'Banda Sonora', 'Zarzuela', 'Himno', 'Pasacalle', 'Obertura', 'Poema Sinfónico', 'Suite', 'Fantasía'];
        $existingTypes = array_unique(array_merge($defaultTypes, $dbTypes));
        sort($existingTypes);
        
        return view('admin.media_archive.index', compact('mediaArchives', 'existingTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:audio,video',
            'composer' => 'nullable|string|max:255',
            'music_type' => 'nullable|string|max:255',
            'performance_date' => 'nullable|date',
            'file' => [
                'bail',
                'required',
                'file',
                function ($attribute, $value, $fail) use ($request) {
                    $maxSize = 20480; // 20MB for both video and audio
                    if ($value->getSize() > $maxSize * 1024) {
                        $fail("El archivo no debe ser mayor de {$maxSize} KB.");
                    }
                    
                    $mime = $value->getMimeType();
                    $extension = strtolower($value->getClientOriginalExtension());
                    
                    if ($request->type === 'video') {
                        $validVideoExts = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
                        if (!str_starts_with($mime, 'video/') && !str_starts_with($mime, 'application/') && !in_array($extension, $validVideoExts)) {
                            $fail("El archivo debe ser un vídeo válido.");
                        }
                    }
                    if ($request->type === 'audio') {
                        $validAudioExts = ['mp3', 'wav', 'ogg', 'm4a', 'flac', 'aac'];
                        if (!str_starts_with($mime, 'audio/') && !str_starts_with($mime, 'application/') && !in_array($extension, $validAudioExts)) {
                            $fail("El archivo debe ser un audio válido.");
                        }
                    }
                }
            ],
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'boolean'
        ]);

        $path = $request->file('file')->store('media_archive', 'public');
        $maxOrder = MediaArchive::max('sort_order') ?? 0;

        $mediaArchive = MediaArchive::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'type' => $request->type,
            'composer' => $request->composer,
            'music_type' => $request->music_type,
            'performance_date' => $request->performance_date,
            'is_active' => $request->has('is_active'),
            'sort_order' => $maxOrder + 1
        ]);

        if ($request->hasFile('images')) {
            $imgOrder = 0;
            foreach ($request->file('images') as $file) {
                $imgPath = $file->store('media_archive_images', 'public');
                \App\Services\ImageWatermarkService::applyWatermark(storage_path('app/public/' . $imgPath));
                
                $imgOrder++;
                \App\Models\MediaArchiveImage::create([
                    'media_archive_id' => $mediaArchive->id,
                    'file_path' => $imgPath,
                    'sort_order' => $imgOrder
                ]);
            }
        }

        return redirect()->route('admin.media-archive.index')->with('success', 'Archivo multimedia subido correctamente.');
    }

    public function update(Request $request, MediaArchive $mediaArchive)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'composer' => 'nullable|string|max:255',
            'music_type' => 'nullable|string|max:255',
            'performance_date' => 'nullable|date',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'boolean'
        ]);

        $mediaArchive->update([
            'title' => $request->title,
            'description' => $request->description,
            'composer' => $request->composer,
            'music_type' => $request->music_type,
            'performance_date' => $request->performance_date,
            'is_active' => $request->has('is_active')
        ]);

        if ($request->hasFile('images')) {
            $imgOrder = $mediaArchive->images()->max('sort_order') ?? 0;
            foreach ($request->file('images') as $file) {
                $imgPath = $file->store('media_archive_images', 'public');
                \App\Services\ImageWatermarkService::applyWatermark(storage_path('app/public/' . $imgPath));
                
                $imgOrder++;
                \App\Models\MediaArchiveImage::create([
                    'media_archive_id' => $mediaArchive->id,
                    'file_path' => $imgPath,
                    'sort_order' => $imgOrder
                ]);
            }
        }

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

    public function destroyImage(\App\Models\MediaArchiveImage $image)
    {
        if (Storage::disk('public')->exists($image->file_path)) {
            Storage::disk('public')->delete($image->file_path);
        }
        
        $image->delete();

        return back()->with('success', 'Imagen eliminada correctamente.');
    }
}
