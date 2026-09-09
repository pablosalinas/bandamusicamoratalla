<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SheetMusic;
use Illuminate\Support\Facades\Storage;

class MusicianController extends Controller
{
    private function getBestTipoPartitura($userTipo, $availableTipos)
    {
        if (in_array($userTipo, $availableTipos)) {
            return $userTipo;
        }
        if (in_array('TODOS', $availableTipos)) {
            return 'TODOS';
        }
        
        $hierarchy = ['3º' => 4, '2º' => 3, '1º' => 2, 'PRINCIPAL' => 1];
        $userLevel = isset($hierarchy[$userTipo]) ? $hierarchy[$userTipo] : 0;
        
        $bestLevel = 0;
        $bestTipo = null;
        
        foreach ($availableTipos as $tipo) {
            $level = isset($hierarchy[$tipo]) ? $hierarchy[$tipo] : 0;
            if ($level > 0 && $level < $userLevel) {
                if ($level > $bestLevel) {
                    $bestLevel = $level;
                    $bestTipo = $tipo;
                }
            }
        }
        
        return $bestTipo;
    }

    public function index()
    {
        $user = Auth::user();
        $user->load('inventories.instrument');
        
        // Find which (instrument, tipo) the user has
        $userInstrumentParts = [];
        foreach ($user->inventories as $inv) {
            if ($inv->is_active) {
                $userInstrumentParts[] = [
                    'id' => $inv->instrument_catalog_id,
                    'tipo' => $inv->tipo_partitura ?: 'TODOS'
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
            
            $instrumentIds = array_column($userInstrumentParts, 'id');
            $query->whereIn('instrument_catalog_id', $instrumentIds);
            
            $allParts = $query->get();
            $grouped = $allParts->groupBy(function($item) {
                return $item->sheet_music_id . '_' . $item->instrument_catalog_id;
            });
            
            foreach ($grouped as $key => $parts) {
                $instrumentId = $parts->first()->instrument_catalog_id;
                $userTipo = 'TODOS';
                foreach ($userInstrumentParts as $uip) {
                    if ($uip['id'] == $instrumentId) {
                        $userTipo = $uip['tipo'];
                        break;
                    }
                }
                
                $availableTipos = $parts->pluck('tipo_partitura')->toArray();
                $bestTipo = $this->getBestTipoPartitura($userTipo, $availableTipos);
                
                if ($bestTipo) {
                    $bestPart = $parts->where('tipo_partitura', $bestTipo)->first();
                    $availableParts->push($bestPart);
                }
            }
            
            $availableParts = $availableParts->sortBy('title')->values();
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
        $user->load('inventories');
        
        $hasAccess = false;
        foreach ($user->inventories as $inv) {
            if ($inv->is_active && $inv->instrument_catalog_id == $sheetMusicInstrument->instrument_catalog_id) {
                $userTipo = $inv->tipo_partitura ?: 'TODOS';
                
                $allParts = \App\Models\SheetMusicInstrument::where('sheet_music_id', $sheetMusicInstrument->sheet_music_id)
                    ->where('instrument_catalog_id', $sheetMusicInstrument->instrument_catalog_id)
                    ->pluck('tipo_partitura')->toArray();
                
                $bestTipo = $this->getBestTipoPartitura($userTipo, $allParts);
                
                if ($bestTipo === $sheetMusicInstrument->tipo_partitura) {
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

    public function downloadParentalConsent(Request $request)
    {
        $user = Auth::user();
        $age = null;
        if ($user->birth_date) {
            $age = \Carbon\Carbon::parse($user->birth_date)->age;
        }

        // Only minors or those without birth date can download it from here (as per requirement)
        if ($age !== null && $age >= 18) {
            abort(403, 'No necesitas justificante parental al ser mayor de edad.');
        }

        $template = \App\Models\SiteSetting::getSetting('parental_consent_template', '');
        $pdfPath = \App\Models\SiteSetting::getSetting('parental_consent_pdf', '');

        if (!empty($template)) {
            $userName = $user->name . ' ' . $user->last_name;
            $eventInfo = '________________________________________________________________';
            $dateInfo = 'Fecha: ____________________';
            
            if ($request->filled('event_id')) {
                $event = \App\Models\Event::find($request->event_id);
                if ($event) {
                    \Carbon\Carbon::setLocale('es');
                    $eventInfo = $event->name . ' el ' . \Carbon\Carbon::parse($event->event_date)->translatedFormat('d \d\e F \d\e Y');
                    $dateInfo = 'Fecha: ' . now()->translatedFormat('d \d\e F \d\e Y');
                }
            }

            $template = str_replace(
                [
                    '<nombre>', '&lt;nombre&gt;', '[nombre]', '[NOMBRE]',
                    '<evento>', '&lt;evento&gt;', '[evento]', '[EVENTO]',
                    '<fecha>', '&lt;fecha&gt;', '[fecha]', '[FECHA]'
                ],
                [
                    $userName, $userName, $userName, $userName,
                    $eventInfo, $eventInfo, $eventInfo, $eventInfo,
                    $dateInfo, $dateInfo, $dateInfo, $dateInfo
                ],
                $template
            );
            return view('shared.parental_consent_pdf', compact('template', 'userName'));
        } elseif (!empty($pdfPath) && \Illuminate\Support\Facades\Storage::disk('public')->exists($pdfPath)) {
            return response()->download(\Illuminate\Support\Facades\Storage::disk('public')->path($pdfPath), 'justificante_parental_' . str_replace(' ', '_', $user->name) . '.pdf');
        }

        return redirect()->back()->with('error', 'El modelo de justificante parental aún no ha sido configurado por la administración.');
    }
}
