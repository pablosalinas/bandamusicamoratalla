<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $bandIban = '';
        $rawIban = \App\Models\SiteSetting::getSetting('band_iban', '');
        if ($rawIban) {
            try {
                $bandIban = \Illuminate\Support\Facades\Crypt::decryptString($rawIban);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                $bandIban = '';
            }
        }

        $settings = [
            'band_name' => \App\Models\SiteSetting::getSetting('band_name', 'Banda de Música de Moratalla'),
            'session_timeout' => \App\Models\SiteSetting::getSetting('session_timeout', 120),
            'statutes' => \App\Models\SiteSetting::getSetting('statutes', ''),
            'band_history' => \App\Models\SiteSetting::getSetting('band_history', ''),
            'carousel_speed' => \App\Models\SiteSetting::getSetting('carousel_speed', 4),
            'band_iban' => $bandIban,
            'site_logos' => json_decode(\App\Models\SiteSetting::getSetting('site_logos', '[]'), true),
        ];
        
        $carouselMedia = \App\Models\CarouselMedia::orderBy('sort_order')->get();
        
        return view('admin.settings.index', compact('settings', 'carouselMedia'));
    }

    public function update(Request $request)
    {
        $rules = [
            'band_name' => 'required|string|max:255',
            'session_timeout' => 'required|integer|min:1',
            'statutes' => 'nullable|string',
            'band_history' => 'nullable|string',
            'carousel_speed' => 'required|integer|min:1',
        ];

        if (auth()->user()->canViewIban()) {
            $rules['band_iban'] = ['nullable', 'string', 'max:50', new \App\Rules\ValidIban];
        }

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            if ($key === 'band_iban') {
                $value = $value ? \Illuminate\Support\Facades\Crypt::encryptString($value) : '';
            }
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => is_numeric($value) ? 'integer' : 'text']
            );
        }

        return redirect()->route('admin.settings.index')->with('success', 'Configuración actualizada correctamente.');
    }

    public function storeCarouselMedia(Request $request)
    {
        $request->validate([
            'media' => 'required|array',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:51200' // Max 50MB per file
        ]);

        if ($request->hasFile('media')) {
            $maxOrder = \App\Models\CarouselMedia::max('sort_order') ?? 0;
            
            foreach ($request->file('media') as $file) {
                $path = $file->store('carousel', 'public');
                $mime = $file->getMimeType();
                $type = str_starts_with($mime, 'video/') ? 'video' : 'image';
                
                if ($type === 'image') {
                    \App\Services\ImageWatermarkService::applyWatermark(storage_path('app/public/' . $path));
                }
                
                $maxOrder++;
                \App\Models\CarouselMedia::create([
                    'file_path' => $path,
                    'type' => $type,
                    'sort_order' => $maxOrder
                ]);
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Archivos añadidos al carrusel correctamente.');
    }

    public function destroyCarouselMedia(\App\Models\CarouselMedia $media)
    {
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($media->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($media->file_path);
        }
        $media->delete();
        
        return redirect()->route('admin.settings.index')->with('success', 'Archivo eliminado del carrusel.');
    }

    public function updateCarouselMedia(Request $request, \App\Models\CarouselMedia $media)
    {
        $request->validate([
            'description' => 'nullable|string|max:255',
        ]);
        
        $media->update([
            'description' => $request->description
        ]);
        
        return redirect()->route('admin.settings.index')->with('success', 'Descripción actualizada.');
    }

    public function storeLogo(Request $request)
    {
        $request->validate([
            'logos' => 'required|array',
            'logos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        $currentLogos = json_decode(\App\Models\SiteSetting::getSetting('site_logos', '[]'), true) ?: [];

        if ($request->hasFile('logos')) {
            foreach ($request->file('logos') as $file) {
                $path = $file->store('logos', 'public');
                $currentLogos[] = $path;
            }
        }

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'site_logos'],
            ['value' => json_encode(array_values($currentLogos)), 'type' => 'text']
        );

        return redirect()->route('admin.settings.index')->with('success', 'Logos añadidos correctamente.');
    }

    public function destroyLogo(Request $request)
    {
        $path = $request->input('path');
        $currentLogos = json_decode(\App\Models\SiteSetting::getSetting('site_logos', '[]'), true) ?: [];
        
        $currentLogos = array_filter($currentLogos, function($logo) use ($path) {
            return $logo !== $path;
        });

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
        }

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'site_logos'],
            ['value' => json_encode(array_values($currentLogos)), 'type' => 'text']
        );

        return redirect()->route('admin.settings.index')->with('success', 'Logo eliminado correctamente.');
    }
}
