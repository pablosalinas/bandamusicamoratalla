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
        $user->load('inventories');
        
        $hasAccess = false;
        foreach ($user->inventories as $inv) {
            if ($inv->is_active && $inv->instrument_catalog_id == $sheetMusicInstrument->instrument_catalog_id) {
                $userTipo = $inv->tipo_partitura ?: 'TODOS';
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

    public function downloadParentalConsent()
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
            $watermarkHtml = '';
            
            // Buscar el logo con el orden más bajo
            $rawLogos = json_decode(\App\Models\SiteSetting::getSetting('site_logos', '[]'), true) ?: [];
            $logos = [];
            foreach ($rawLogos as $logo) {
                if (is_string($logo)) {
                    $logos[] = ['path' => $logo, 'order' => 999];
                } else if (is_array($logo)) {
                    $logos[] = $logo;
                }
            }
            usort($logos, function($a, $b) {
                return ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
            });

            if (count($logos) > 0) {
                $bestLogoPath = $logos[0]['path'];
                $fullPath = str_starts_with($bestLogoPath, 'images/') 
                    ? public_path($bestLogoPath) 
                    : storage_path('app/public/' . $bestLogoPath);

                if (file_exists($fullPath)) {
                    // DOMPDF necesita permisos y a veces falla con rutas relativas o URLs. Base64 es la forma más segura.
                    $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($fullPath);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    
                    $watermarkHtml = '<img src="' . $base64 . '" class="watermark">';
                }
            }

            // Generate PDF from HTML
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML('
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .watermark {
                            position: fixed;
                            top: 30%;
                            left: 15%;
                            width: 70%;
                            opacity: 0.1;
                            z-index: -1;
                        }
                    </style>
                </head>
                <body>
                    ' . $watermarkHtml . '
                    ' . $template . '
                </body>
                </html>
            ');
            return $pdf->stream('justificante_parental_' . str_replace(' ', '_', $user->name) . '.pdf');
        } elseif (!empty($pdfPath) && \Illuminate\Support\Facades\Storage::disk('public')->exists($pdfPath)) {
            return response()->download(\Illuminate\Support\Facades\Storage::disk('public')->path($pdfPath), 'justificante_parental_' . str_replace(' ', '_', $user->name) . '.pdf');
        }

        return redirect()->back()->with('error', 'El modelo de justificante parental aún no ha sido configurado por la administración.');
    }
}
