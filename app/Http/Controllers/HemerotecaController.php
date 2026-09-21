<?php

namespace App\Http\Controllers;

use App\Models\NewsActivity;
use App\Models\MediaArchive;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HemerotecaController extends Controller
{
    /**
     * Hemeroteca de Noticias:
     * - Orden inverso de aparición (de más reciente a más antigua).
     * - Filtros por fechas (desde/hasta), por año y búsqueda por título/contenido.
     */
    public function news(Request $request)
    {
        $query = NewsActivity::query()
            ->where('is_published', true)
            ->where('show_in_hemeroteca', true)
            ->with(['mainImage', 'newsImages']);

        // Búsqueda por texto (título o contenido)
        if ($request->filled('q')) {
            $search = '%' . trim($request->q) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('content', 'like', $search);
            });
        }

        // Filtro por año
        if ($request->filled('year')) {
            $year = (int) $request->year;
            $query->where(function ($q) use ($year) {
                $q->whereYear('event_date', $year)
                  ->orWhere(function ($sub) use ($year) {
                      $sub->whereNull('event_date')->whereYear('created_at', $year);
                  });
            });
        }

        // Filtro por fecha desde
        if ($request->filled('from_date')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('event_date', '>=', $request->from_date)
                  ->orWhere(function ($sub) use ($request) {
                      $sub->whereNull('event_date')->whereDate('created_at', '>=', $request->from_date);
                  });
            });
        }

        // Filtro por fecha hasta
        if ($request->filled('to_date')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('event_date', '<=', $request->to_date)
                  ->orWhere(function ($sub) use ($request) {
                      $sub->whereNull('event_date')->whereDate('created_at', '<=', $request->to_date);
                  });
            });
        }

        // Orden inverso de aparición: prioriza event_date si existe, luego created_at descendente
        $query->orderByRaw('COALESCE(event_date, created_at) DESC');

        $news = $query->paginate(9)->withQueryString();

        // Obtener años disponibles para el selector
        $availableYears = NewsActivity::where('is_published', true)
            ->where('show_in_hemeroteca', true)
            ->selectRaw('DISTINCT YEAR(COALESCE(event_date, created_at)) as yr')
            ->orderBy('yr', 'desc')
            ->pluck('yr')
            ->filter()
            ->values();

        $globalBandName = SiteSetting::getSetting('band_name', 'Banda de Música de Moratalla');
        $newsSpeed = (int) SiteSetting::getSetting('news_speed', 4);
        $waitVideosFinish = SiteSetting::getSetting('wait_videos_finish', '1') == '1';

        return view('hemeroteca.news', compact('news', 'availableYears', 'globalBandName', 'newsSpeed', 'waitVideosFinish'));
    }

    /**
     * Hemeroteca Multimedia:
     * - Orden alfabético por título o por compositor (seleccionable).
     * - Filtros por fechas/año, búsqueda por título y por compositor, y tipo de medio.
     */
    public function media(Request $request)
    {
        $query = MediaArchive::query()
            ->where('is_active', true)
            ->where('show_in_hemeroteca', true)
            ->with('images');

        // Búsqueda por título
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . trim($request->title) . '%');
        }

        // Búsqueda o filtro por compositor
        if ($request->filled('composer')) {
            $query->where('composer', 'like', '%' . trim($request->composer) . '%');
        }

        // Filtro por tipo de archivo (audio / video)
        if ($request->filled('type') && in_array($request->type, ['audio', 'video'])) {
            $query->where('type', $request->type);
        }

        // Filtro por año de interpretación
        if ($request->filled('year')) {
            $year = (int) $request->year;
            $query->where(function ($q) use ($year) {
                $q->whereYear('performance_date', $year)
                  ->orWhere(function ($sub) use ($year) {
                      $sub->whereNull('performance_date')->whereYear('created_at', $year);
                  });
            });
        }

        // Filtro por fecha desde
        if ($request->filled('from_date')) {
            $query->whereDate('performance_date', '>=', $request->from_date);
        }

        // Filtro por fecha hasta
        if ($request->filled('to_date')) {
            $query->whereDate('performance_date', '<=', $request->to_date);
        }

        // Orden alfabético: por defecto título A-Z, o por compositor si se selecciona
        $sortBy = $request->get('sort', 'title');
        if ($sortBy === 'composer') {
            $query->orderByRaw("CASE WHEN composer IS NULL OR composer = '' THEN 1 ELSE 0 END, composer ASC, title ASC");
        } elseif ($sortBy === 'date_desc') {
            $query->orderByRaw("COALESCE(performance_date, created_at) DESC");
        } else {
            $sortBy = 'title';
            $query->orderBy('title', 'asc');
        }

        $mediaArchives = $query->paginate(12)->withQueryString();

        // Obtener años disponibles en multimedia
        $availableYears = MediaArchive::where('is_active', true)
            ->where('show_in_hemeroteca', true)
            ->selectRaw('DISTINCT YEAR(COALESCE(performance_date, created_at)) as yr')
            ->orderBy('yr', 'desc')
            ->pluck('yr')
            ->filter()
            ->values();

        // Obtener lista de compositores para autocompletar o sugerencias
        $composers = MediaArchive::where('is_active', true)
            ->where('show_in_hemeroteca', true)
            ->whereNotNull('composer')
            ->where('composer', '!=', '')
            ->distinct()
            ->orderBy('composer')
            ->pluck('composer');

        $globalBandName = SiteSetting::getSetting('band_name', 'Banda de Música de Moratalla');

        return view('hemeroteca.media', compact('mediaArchives', 'availableYears', 'composers', 'sortBy', 'globalBandName'));
    }
}
