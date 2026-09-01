<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SheetMusic;
use App\Models\InstrumentCatalog;
use App\Models\SheetMusicInstrument;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SheetMusicController extends Controller
{
    public function index(Request $request)
    {
        $query = SheetMusic::query();
        
        if ($request->filled('work_type') && $request->work_type !== '') {
            $query->where('work_type', $request->work_type);
        }

        if ($request->filled('instrument_id') && $request->instrument_id !== '') {
            $query->whereHas('instruments', function($q) use ($request) {
                $q->where('instrument_catalogs.id', $request->instrument_id);
            });
        }
        
        $sheetMusics = $query->orderBy('title')->paginate(15);
        $workTypes = SheetMusic::whereNotNull('work_type')->distinct()->pluck('work_type')->filter()->sort();
        $instruments = InstrumentCatalog::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.sheet-music.index', compact('sheetMusics', 'workTypes', 'instruments'));
    }

    public function pdf(Request $request)
    {
        $query = SheetMusic::query();
        
        if ($request->filled('work_type') && $request->work_type !== '') {
            $query->where('work_type', $request->work_type);
        }

        if ($request->filled('instrument_id') && $request->instrument_id !== '') {
            $query->whereHas('instruments', function($q) use ($request) {
                $q->where('instrument_catalogs.id', $request->instrument_id);
            });
        }
        
        $sheetMusics = $query->orderBy('title')->get();
        $instrument = $request->filled('instrument_id') ? InstrumentCatalog::find($request->instrument_id) : null;

        return view('admin.sheet-music.pdf', compact('sheetMusics', 'instrument'));
    }

    public function create()
    {
        return view('admin.sheet-music.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'composer' => ['nullable', 'string', 'max:255'],
            'arranger' => ['nullable', 'string', 'max:255'],
            'work_type' => ['nullable', 'string', 'max:255'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'leave_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $path = null;
        if ($request->hasFile('pdf_file')) {
            $path = $request->file('pdf_file')->store('sheet-music', 'local');
        }

        $sheetMusic = SheetMusic::create([
            'title' => $request->title,
            'composer' => $request->composer,
            'arranger' => $request->arranger,
            'work_type' => $request->work_type,
            'pdf_file_path' => $path,
            'is_active' => $request->has('is_active'),
            'leave_reason' => $request->leave_reason,
        ]);

        return redirect()->route('admin.sheet-music.edit', $sheetMusic)->with('success', 'Obra creada. Ahora puedes asignar las partituras por instrumento y tipo.');
    }

    public function edit(SheetMusic $sheetMusic)
    {
        $instruments = InstrumentCatalog::where('is_active', true)->orderBy('name')->get();
        
        $files = SheetMusicInstrument::where('sheet_music_id', $sheetMusic->id)->get();
        $filesIndexed = [];
        foreach ($files as $file) {
            $filesIndexed[$file->instrument_catalog_id][$file->tipo_partitura] = $file;
        }

        return view('admin.sheet-music.edit', compact('sheetMusic', 'instruments', 'filesIndexed'));
    }

    public function update(Request $request, SheetMusic $sheetMusic)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'composer' => ['nullable', 'string', 'max:255'],
            'arranger' => ['nullable', 'string', 'max:255'],
            'work_type' => ['nullable', 'string', 'max:255'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'leave_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $data = $request->only(['title', 'composer', 'arranger', 'work_type', 'leave_reason']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('pdf_file')) {
            if ($sheetMusic->pdf_file_path && Storage::disk('local')->exists($sheetMusic->pdf_file_path)) {
                Storage::disk('local')->delete($sheetMusic->pdf_file_path);
            }
            $data['pdf_file_path'] = $request->file('pdf_file')->store('sheet-music', 'local');
        }

        $sheetMusic->update($data);

        // Procesar eliminación de archivos
        if ($request->has('delete_files') && is_array($request->delete_files)) {
            foreach ($request->delete_files as $instrumentId => $tipos) {
                foreach ($tipos as $tipo => $shouldDelete) {
                    if ($shouldDelete) {
                        $pivot = SheetMusicInstrument::where('sheet_music_id', $sheetMusic->id)
                            ->where('instrument_catalog_id', $instrumentId)
                            ->where('tipo_partitura', $tipo)
                            ->first();
                        if ($pivot) {
                            if ($pivot->pdf_file_path && Storage::disk('local')->exists($pivot->pdf_file_path)) {
                                Storage::disk('local')->delete($pivot->pdf_file_path);
                            }
                            $pivot->delete();
                        }
                    }
                }
            }
        }

        // Procesar subida de nuevos archivos
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $instrumentId => $tipos) {
                foreach ($tipos as $tipo => $file) {
                    if ($file->isValid()) {
                        $pivot = SheetMusicInstrument::where('sheet_music_id', $sheetMusic->id)
                            ->where('instrument_catalog_id', $instrumentId)
                            ->where('tipo_partitura', $tipo)
                            ->first();
                        
                        if ($pivot && $pivot->pdf_file_path && Storage::disk('local')->exists($pivot->pdf_file_path)) {
                            Storage::disk('local')->delete($pivot->pdf_file_path);
                        }

                        $extension = strtolower($file->getClientOriginalExtension());
                        
                        if (in_array($extension, ['jpg', 'jpeg', 'png', 'bmp', 'webp'])) {
                            $manager = new ImageManager(new Driver());
                            $image = $manager->read($file->getPathname());
                            
                            $image->text('www.bandamusicamoratalla.com', $image->width() - 20, $image->height() - 20, function($font) use ($image) {
                                $font->size(min($image->width() * 0.03, 30)); 
                                $font->color('rgba(150, 150, 150, 0.5)');
                                $font->align('right');
                                $font->valign('bottom');
                            });
                            
                            $filename = 'sheet-music-parts/' . uniqid() . '.jpg';
                            Storage::disk('local')->put($filename, (string) $image->toJpeg(80));
                            $path = $filename;
                        } else {
                            $path = $file->store('sheet-music-parts', 'local');
                        }

                        if ($pivot) {
                            $pivot->update(['pdf_file_path' => $path]);
                        } else {
                            SheetMusicInstrument::create([
                                'sheet_music_id' => $sheetMusic->id,
                                'instrument_catalog_id' => $instrumentId,
                                'tipo_partitura' => $tipo,
                                'pdf_file_path' => $path
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.sheet-music.edit', $sheetMusic)->with('success', 'Partitura y archivos actualizados correctamente.');
    }

    public function uploadPartAjax(Request $request, SheetMusic $sheetMusic)
    {
        $request->validate([
            'instrument_id' => 'required|exists:instrument_catalogs,id',
            'tipo' => 'required|string',
            'file' => 'required|file|max:20480'
        ]);

        $instrumentId = $request->instrument_id;
        $tipo = $request->tipo;
        $file = $request->file('file');

        if ($file->isValid()) {
            $pivot = SheetMusicInstrument::where('sheet_music_id', $sheetMusic->id)
                ->where('instrument_catalog_id', $instrumentId)
                ->where('tipo_partitura', $tipo)
                ->first();
            
            if ($pivot && $pivot->pdf_file_path && Storage::disk('local')->exists($pivot->pdf_file_path)) {
                Storage::disk('local')->delete($pivot->pdf_file_path);
            }

            $extension = strtolower($file->getClientOriginalExtension());
            
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'bmp', 'webp'])) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getPathname());
                
                $image->text('www.bandamusicamoratalla.com', $image->width() - 20, $image->height() - 20, function($font) use ($image) {
                    $font->size(min($image->width() * 0.03, 30)); 
                    $font->color('rgba(150, 150, 150, 0.5)');
                    $font->align('right');
                    $font->valign('bottom');
                });
                
                $filename = 'sheet-music-parts/' . uniqid() . '.jpg';
                Storage::disk('local')->put($filename, (string) $image->toJpeg(80));
                $path = $filename;
            } else {
                $path = $file->store('sheet-music-parts', 'local');
            }

            if ($pivot) {
                $pivot->update(['pdf_file_path' => $path]);
            } else {
                SheetMusicInstrument::create([
                    'sheet_music_id' => $sheetMusic->id,
                    'instrument_catalog_id' => $instrumentId,
                    'tipo_partitura' => $tipo,
                    'pdf_file_path' => $path
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function destroy(SheetMusic $sheetMusic)
    {
        $pivots = SheetMusicInstrument::where('sheet_music_id', $sheetMusic->id)->get();
        foreach ($pivots as $pivot) {
            if ($pivot->pdf_file_path && Storage::disk('local')->exists($pivot->pdf_file_path)) {
                Storage::disk('local')->delete($pivot->pdf_file_path);
            }
        }

        if ($sheetMusic->pdf_file_path && Storage::disk('local')->exists($sheetMusic->pdf_file_path)) {
            Storage::disk('local')->delete($sheetMusic->pdf_file_path);
        }
        $sheetMusic->delete();
        
        return redirect()->route('admin.sheet-music.index')->with('success', 'Partitura eliminada.');
    }

    public function download(SheetMusic $sheetMusic)
    {
        if (!$sheetMusic->pdf_file_path || !Storage::disk('local')->exists($sheetMusic->pdf_file_path)) {
            abort(404, 'Archivo no encontrado');
        }

        return Storage::disk('local')->download($sheetMusic->pdf_file_path, $sheetMusic->title . '_guion.pdf');
    }
    
    public function downloadPart(SheetMusicInstrument $sheetMusicInstrument)
    {
        if (!$sheetMusicInstrument->pdf_file_path || !Storage::disk('local')->exists($sheetMusicInstrument->pdf_file_path)) {
            abort(404, 'Archivo no encontrado');
        }
        
        $sheetMusic = SheetMusic::find($sheetMusicInstrument->sheet_music_id);
        $instrument = InstrumentCatalog::find($sheetMusicInstrument->instrument_catalog_id);
        
        $extension = pathinfo($sheetMusicInstrument->pdf_file_path, PATHINFO_EXTENSION);
        $filename = $sheetMusic->title . '_' . $instrument->name . '_' . $sheetMusicInstrument->tipo_partitura . '.' . $extension;

        return Storage::disk('local')->download($sheetMusicInstrument->pdf_file_path, $filename);
    }
}
