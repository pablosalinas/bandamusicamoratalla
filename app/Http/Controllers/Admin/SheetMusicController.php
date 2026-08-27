<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SheetMusic;
use App\Models\InstrumentCatalog;
use Illuminate\Support\Facades\Storage;

class SheetMusicController extends Controller
{
    public function index()
    {
        // Cargamos las partituras con sus instrumentos
        $sheetMusics = SheetMusic::with('instruments')->orderBy('title')->paginate(15);
        return view('admin.sheet-music.index', compact('sheetMusics'));
    }

    public function create()
    {
        $instruments = InstrumentCatalog::where('is_active', true)->orderBy('name')->get();
        return view('admin.sheet-music.create', compact('instruments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'composer' => ['nullable', 'string', 'max:255'],
            'arranger' => ['nullable', 'string', 'max:255'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'], // Máximo 20MB
            'instruments' => ['nullable', 'array'],
            'leave_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $path = null;
        if ($request->hasFile('pdf_file')) {
            // Guardamos el archivo general en el disco privado 'local' por seguridad
            $path = $request->file('pdf_file')->store('sheet-music', 'local');
        }

        $sheetMusic = SheetMusic::create([
            'title' => $request->title,
            'composer' => $request->composer,
            'arranger' => $request->arranger,
            'pdf_file_path' => $path,
            'is_active' => $request->has('is_active'),
            'leave_reason' => $request->leave_reason,
        ]);

        // Sincronizar instrumentos asignados a la obra
        if ($request->has('instruments')) {
            $sheetMusic->instruments()->sync($request->instruments);
        }

        return redirect()->route('admin.sheet-music.index')->with('success', 'Partitura registrada correctamente.');
    }

    public function edit(SheetMusic $sheetMusic)
    {
        $instruments = InstrumentCatalog::orderBy('name')->get();
        return view('admin.sheet-music.edit', compact('sheetMusic', 'instruments'));
    }

    public function update(Request $request, SheetMusic $sheetMusic)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'composer' => ['nullable', 'string', 'max:255'],
            'arranger' => ['nullable', 'string', 'max:255'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'instruments' => ['nullable', 'array'],
            'leave_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $data = $request->only(['title', 'composer', 'arranger', 'leave_reason']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('pdf_file')) {
            // Eliminamos el archivo antiguo si existe
            if ($sheetMusic->pdf_file_path && Storage::disk('local')->exists($sheetMusic->pdf_file_path)) {
                Storage::disk('local')->delete($sheetMusic->pdf_file_path);
            }
            $data['pdf_file_path'] = $request->file('pdf_file')->store('sheet-music', 'local');
        }

        $sheetMusic->update($data);

        if ($request->has('instruments')) {
            $sheetMusic->instruments()->sync($request->instruments);
        } else {
            $sheetMusic->instruments()->detach();
        }

        return redirect()->route('admin.sheet-music.index')->with('success', 'Partitura actualizada correctamente.');
    }

    public function destroy(SheetMusic $sheetMusic)
    {
        if ($sheetMusic->pdf_file_path && Storage::disk('local')->exists($sheetMusic->pdf_file_path)) {
            Storage::disk('local')->delete($sheetMusic->pdf_file_path);
        }
        $sheetMusic->delete();
        
        return redirect()->route('admin.sheet-music.index')->with('success', 'Partitura eliminada.');
    }

    // Método para visualizar/descargar el PDF protegiendo el acceso
    public function download(SheetMusic $sheetMusic)
    {
        // Aquí podríamos añadir lógica para comprobar si el músico tiene este instrumento asignado
        if (!$sheetMusic->pdf_file_path || !Storage::disk('local')->exists($sheetMusic->pdf_file_path)) {
            abort(404, 'Archivo no encontrado');
        }

        return Storage::disk('local')->download($sheetMusic->pdf_file_path, $sheetMusic->title . '.pdf');
    }
}
