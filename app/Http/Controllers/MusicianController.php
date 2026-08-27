<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SheetMusic;
use Illuminate\Support\Facades\Storage;

class MusicianController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Obtenemos los IDs de los instrumentos que toca este músico
        $instrumentIds = $user->instruments->pluck('id');

        // Buscamos las partituras que tengan asignados esos instrumentos
        // Utilizamos whereHas para filtrar por la relación
        $sheetMusics = SheetMusic::where('is_active', true)
            ->whereHas('instruments', function($query) use ($instrumentIds) {
                $query->whereIn('instrument_catalog_id', $instrumentIds);
            })->orderBy('title')->get();

        // Obtener historial de faltas
        $missedAttendances = \App\Models\Attendance::with('event')
            ->where('user_id', $user->id)
            ->whereIn('status', ['absent', 'excused'])
            ->get()
            ->sortByDesc(function($attendance) {
                return $attendance->event->event_date;
            });

        return view('dashboard', compact('sheetMusics', 'user', 'missedAttendances'));
    }

    // Método para descargar la partitura desde el panel del músico
    public function download(SheetMusic $sheetMusic)
    {
        $user = Auth::user();
        $instrumentIds = $user->instruments->pluck('id');

        // Verificar si el músico tiene permiso para descargar esta partitura
        $hasAccess = $sheetMusic->instruments()->whereIn('instrument_catalog_id', $instrumentIds)->exists();

        if (!$hasAccess || !$sheetMusic->pdf_file_path || !Storage::disk('local')->exists($sheetMusic->pdf_file_path)) {
            abort(403, 'No tienes acceso a esta partitura o el archivo no existe.');
        }

        return Storage::disk('local')->download($sheetMusic->pdf_file_path, $sheetMusic->title . '.pdf');
    }
}
