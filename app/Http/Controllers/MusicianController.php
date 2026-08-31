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
        $user->load('instruments');
        
        $instrumentIds = $user->instruments->pluck('id')->toArray();
        $sheetMusics = \App\Models\SheetMusic::where('is_active', true)
            ->whereHas('instruments', function($q) use ($instrumentIds) {
                $q->whereIn('instrument_catalog_id', $instrumentIds);
            })->orderBy('title')->get();

        $missedAttendances = \App\Models\Attendance::with('event')
            ->where('user_id', $user->id)
            ->whereIn('status', ['absent', 'excused'])
            ->get()
            ->sortByDesc(function($attendance) {
                return $attendance->event->event_date;
            });

        $currentFiscalYear = null;
        if ($user->isSuperAdmin() || $user->isCurrentBoardMember()) {
            $currentFiscalYear = \App\Models\FiscalYear::where('is_closed', false)
                ->orderBy('start_date', 'desc')
                ->first();
        }

        return view('dashboard', compact('user', 'sheetMusics', 'missedAttendances', 'currentFiscalYear'));
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

    public function planning()
    {
        \Carbon\Carbon::setLocale('es');
        $events = \App\Models\Event::where('is_active', true)
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->get()
            ->groupBy(function($val) {
                return \Carbon\Carbon::parse($val->event_date)->translatedFormat('F Y');
            });

        return view('musician.planning', compact('events'));
    }

    public function planningPdf()
    {
        \Carbon\Carbon::setLocale('es');
        $events = \App\Models\Event::where('is_active', true)
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->get()
            ->groupBy(function($val) {
                return \Carbon\Carbon::parse($val->event_date)->translatedFormat('F Y');
            });

        return view('musician.planning_pdf', compact('events'));
    }
}
