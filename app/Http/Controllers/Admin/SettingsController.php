<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'band_name' => \App\Models\SiteSetting::getSetting('band_name', 'Banda de Música de Moratalla'),
            'session_timeout' => \App\Models\SiteSetting::getSetting('session_timeout', 120),
            'statutes' => \App\Models\SiteSetting::getSetting('statutes', ''),
        ];
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'band_name' => 'required|string|max:255',
            'session_timeout' => 'required|integer|min:1',
            'statutes' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => is_numeric($value) ? 'integer' : 'text']
            );
        }

        return redirect()->route('admin.settings.index')->with('success', 'Configuración actualizada correctamente.');
    }
}
