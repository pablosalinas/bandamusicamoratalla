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
        
        // Find which (instrument, tipo) the user has
        $userInstrumentParts = [];
        foreach ($user->instruments as $inst) {
            if ($inst->pivot->is_active) {
                $userInstrumentParts[] = [
                    'id' => $inst->id,
                    'tipo' => $inst->pivot->tipo_partitura ?: 'TODOS'
                ];
            }
        }

        // Get the specific parts the user can download
        $availableParts = collect();
        if (!empty($userInstrumentParts)) {
            $query = \App\Models\SheetMusicInstrument::query()
                ->join('sheet_music', 'sheet_music_instruments.sheet_music_id', '=', 'sheet_music.id')
                ->where('sheet_music.is_active', true)
                ->select('sheet_music_instruments.*', 'sheet_music.title', 'sheet_music.composer', 'sheet_music.work_type');
            
            $query->where(function($q) use ($userInstrumentParts) {
                foreach ($userInstrumentParts as $uip) {
                    $q->orWhere(function($subQ) use ($uip) {
                        $subQ->where('instrument_catalog_id', $uip['id'])
                             ->whereIn('tipo_partitura', [$uip['tipo'], 'TODOS']);
                    });
                }
            });
            
            $availableParts = $query->orderBy('sheet_music.title')->get();
        }

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

        return view('dashboard', compact('user', 'availableParts', 'missedAttendances', 'currentFiscalYear'));
    }

    public function download(\App\Models\SheetMusicInstrument $sheetMusicInstrument)
    {
        $user = Auth::user();
        
        $hasAccess = false;
        foreach ($user->instruments as $inst) {
            if ($inst->pivot->is_active && $inst->id == $sheetMusicInstrument->instrument_catalog_id) {
                $userTipo = $inst->pivot->tipo_partitura ?: 'TODOS';
                if (in_array($sheetMusicInstrument->tipo_partitura, [$userTipo, 'TODOS'])) {
                    $hasAccess = true;
                    break;
                }
            }
        }

        if (!$hasAccess || !$sheetMusicInstrument->pdf_file_path || !Storage::disk('local')->exists($sheetMusicInstrument->pdf_file_path)) {
            abort(403, 'No tienes acceso a esta partitura o el archivo no existe.');
        }

        $sheetMusic = \App\Models\SheetMusic::find($sheetMusicInstrument->sheet_music_id);
        $instrument = \App\Models\InstrumentCatalog::find($sheetMusicInstrument->instrument_catalog_id);
        $extension = pathinfo($sheetMusicInstrument->pdf_file_path, PATHINFO_EXTENSION);
        $filename = $sheetMusic->title . '_' . $instrument->name . '_' . $sheetMusicInstrument->tipo_partitura . '.' . $extension;

        return Storage::disk('local')->download($sheetMusicInstrument->pdf_file_path, $filename);
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
