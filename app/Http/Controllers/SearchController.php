<?php

namespace App\Http\Controllers;

use App\Models\BandHistoryImage;
use App\Models\CarouselMedia;
use App\Models\Event;
use App\Models\MediaArchive;
use App\Models\MediaArchiveImage;
use App\Models\NewsActivity;
use App\Models\NewsImage;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Buscador global de la web (por defecto) y externo en toda Internet (si se selecciona).
     * Permite buscar en Noticias, Eventos, Música/Multimedia, Fotos, Historia, etc.,
     * con filtros de fechas que por defecto lo cogen todo.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        // Ámbito: 'web' (por defecto), 'internet' (externo), 'both' (ambos)
        $scope = $request->input('scope', 'web');
        if (!in_array($scope, ['web', 'internet', 'both'])) {
            $scope = 'web';
        }

        // Categoría dentro de la web: 'all' (por defecto), 'news', 'events', 'music', 'photos', 'history'
        $category = $request->input('category', 'all');
        if (!in_array($category, ['all', 'news', 'events', 'music', 'photos', 'history'])) {
            $category = 'all';
        }

        // Filtros de fechas: por defecto 'all' y vacíos para que lo cojan todo
        $datePreset = $request->input('date_preset', 'all');
        $year = $request->filled('year') ? (int) $request->input('year') : null;
        $fromDate = $request->filled('from_date') ? $request->input('from_date') : null;
        $toDate = $request->filled('to_date') ? $request->input('to_date') : null;

        // Si se eligió un preset rápido de fecha y no se forzó rango manual
        if ($datePreset === 'week' && !$fromDate && !$toDate) {
            $fromDate = now()->subWeek()->toDateString();
            $toDate = now()->toDateString();
        } elseif ($datePreset === 'month' && !$fromDate && !$toDate) {
            $fromDate = now()->subMonth()->toDateString();
            $toDate = now()->toDateString();
        } elseif ($datePreset === 'year' && !$fromDate && !$toDate) {
            $fromDate = now()->subYear()->toDateString();
            $toDate = now()->toDateString();
        } elseif ($datePreset === 'all') {
            // Si el usuario envía date_preset=all explícitamente pero también seleccionó year/from_date/to_date,
            // respetamos year/from_date/to_date si están rellenos.
        }

        // Contexto de la banda para búsqueda externa (1 = añadir contexto si no se menciona, 0 = libre en toda internet)
        $bandContext = $request->input('band_context', '1') !== '0';

        // Obtener todos los años disponibles en la web para el selector
        $newsYears = NewsActivity::where('is_published', true)
            ->selectRaw('DISTINCT YEAR(COALESCE(event_date, created_at)) as yr')
            ->pluck('yr');
        $mediaYears = MediaArchive::where('is_active', true)
            ->selectRaw('DISTINCT YEAR(COALESCE(performance_date, created_at)) as yr')
            ->pluck('yr');
        $eventYears = Event::where('is_active', true)
            ->selectRaw('DISTINCT YEAR(COALESCE(event_date, created_at)) as yr')
            ->pluck('yr');

        $availableYears = $newsYears
            ->merge($mediaYears)
            ->merge($eventYears)
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        $newsResults = collect();
        $eventResults = collect();
        $musicResults = collect();
        $photoResults = collect();
        $historyResults = [
            'matched' => false,
            'excerpts' => [],
            'full_html' => '',
            'images' => collect(),
        ];

        $searchInWeb = in_array($scope, ['web', 'both']);
        $searchInInternet = in_array($scope, ['internet', 'both']);

        if ($searchInWeb) {
            // 1. NOTICIAS
            if (in_array($category, ['all', 'news'])) {
                $newsQuery = NewsActivity::query()
                    ->where('is_published', true)
                    ->with(['mainImage', 'newsImages']);

                if ($q !== '') {
                    $like = '%' . $q . '%';
                    $newsQuery->where(function ($sub) use ($like) {
                        $sub->where('title', 'like', $like)
                            ->orWhere('content', 'like', $like);
                    });
                }

                if ($year) {
                    $newsQuery->where(function ($sub) use ($year) {
                        $sub->whereYear('event_date', $year)
                            ->orWhere(function ($s2) use ($year) {
                                $s2->whereNull('event_date')->whereYear('created_at', $year);
                            });
                    });
                }

                if ($fromDate) {
                    $newsQuery->where(function ($sub) use ($fromDate) {
                        $sub->whereDate('event_date', '>=', $fromDate)
                            ->orWhere(function ($s2) use ($fromDate) {
                                $s2->whereNull('event_date')->whereDate('created_at', '>=', $fromDate);
                            });
                    });
                }

                if ($toDate) {
                    $newsQuery->where(function ($sub) use ($toDate) {
                        $sub->whereDate('event_date', '<=', $toDate)
                            ->orWhere(function ($s2) use ($toDate) {
                                $s2->whereNull('event_date')->whereDate('created_at', '<=', $toDate);
                            });
                    });
                }

                $newsQuery->orderByRaw('COALESCE(event_date, created_at) DESC');
                $newsResults = $newsQuery->take($category === 'news' ? 60 : 24)->get();
            }

            // 2. EVENTOS Y ACTUACIONES
            if (in_array($category, ['all', 'events'])) {
                $eventsQuery = Event::query()
                    ->where('is_active', true)
                    ->with(['newsActivity.newsImages']);

                // Si no busca explícitamente "ensayo", ocultamos los ensayos internos
                if ($q === '' || !Str::contains(Str::lower($q), 'ensayo')) {
                    $eventsQuery->where(function ($sub) {
                        $sub->whereNull('type')
                            ->orWhereRaw('LOWER(type) != ?', ['ensayo']);
                    });
                }

                if ($q !== '') {
                    $like = '%' . $q . '%';
                    $eventsQuery->where(function ($sub) use ($like) {
                        $sub->where('name', 'like', $like)
                            ->orWhere('description', 'like', $like)
                            ->orWhere('type', 'like', $like);
                    });
                }

                if ($year) {
                    $eventsQuery->where(function ($sub) use ($year) {
                        $sub->whereYear('event_date', $year)
                            ->orWhere(function ($s2) use ($year) {
                                $s2->whereNull('event_date')->whereYear('created_at', $year);
                            });
                    });
                }

                if ($fromDate) {
                    $eventsQuery->whereDate('event_date', '>=', $fromDate);
                }

                if ($toDate) {
                    $eventsQuery->whereDate('event_date', '<=', $toDate);
                }

                $eventsQuery->orderByRaw('COALESCE(event_date, created_at) DESC');
                $dbEvents = $eventsQuery->take($category === 'events' ? 60 : 18)->get();

                // También incluir noticias que tengan event_date y no estén ya duplicadas por event_id
                $linkedEventIds = $dbEvents->pluck('id')->all();
                $newsWithEventQuery = NewsActivity::query()
                    ->where('is_published', true)
                    ->whereNotNull('event_date')
                    ->where(function ($sub) use ($linkedEventIds) {
                        $sub->whereNull('event_id');
                        if (!empty($linkedEventIds)) {
                            $sub->orWhereNotIn('event_id', $linkedEventIds);
                        }
                    })
                    ->with(['mainImage', 'newsImages']);

                if ($q !== '') {
                    $like = '%' . $q . '%';
                    $newsWithEventQuery->where(function ($sub) use ($like) {
                        $sub->where('title', 'like', $like)
                            ->orWhere('content', 'like', $like);
                    });
                }
                if ($year) {
                    $newsWithEventQuery->whereYear('event_date', $year);
                }
                if ($fromDate) {
                    $newsWithEventQuery->whereDate('event_date', '>=', $fromDate);
                }
                if ($toDate) {
                    $newsWithEventQuery->whereDate('event_date', '<=', $toDate);
                }

                $newsEvents = $newsWithEventQuery->orderBy('event_date', 'desc')->take(24)->get();

                // Unificar formato de eventos
                $unifiedEvents = collect();
                foreach ($dbEvents as $ev) {
                    $unifiedEvents->push([
                        'id' => 'event-' . $ev->id,
                        'title' => $ev->name,
                        'description' => $ev->description ?: ($ev->newsActivity ? $ev->newsActivity->content : ''),
                        'event_date' => $ev->event_date ?: $ev->created_at,
                        'type' => ucfirst($ev->type ?: 'Actuación'),
                        'news_item' => $ev->newsActivity,
                        'image_url' => ($ev->newsActivity && $ev->newsActivity->newsImages->isNotEmpty())
                            ? $ev->newsActivity->newsImages->first()->url
                            : (($ev->newsActivity && $ev->newsActivity->mainImage) ? $ev->newsActivity->mainImage->url : null),
                    ]);
                }

                foreach ($newsEvents as $ne) {
                    // Evitar duplicados por mismo título y fecha
                    $exists = $unifiedEvents->contains(function ($item) use ($ne) {
                        return Str::lower($item['title']) === Str::lower($ne->title);
                    });
                    if (!$exists) {
                        $unifiedEvents->push([
                            'id' => 'news-event-' . $ne->id,
                            'title' => $ne->title,
                            'description' => $ne->content,
                            'event_date' => $ne->event_date,
                            'type' => 'Evento / Concierto',
                            'news_item' => $ne,
                            'image_url' => $ne->newsImages->isNotEmpty()
                                ? $ne->newsImages->first()->url
                                : ($ne->mainImage ? $ne->mainImage->url : null),
                        ]);
                    }
                }

                $eventResults = $unifiedEvents
                    ->sortByDesc(fn ($e) => $e['event_date'] ? Carbon::parse($e['event_date'])->timestamp : 0)
                    ->values()
                    ->take($category === 'events' ? 60 : 18);
            }

            // 3. MÚSICA / ARCHIVO SONORO Y MULTIMEDIA
            if (in_array($category, ['all', 'music'])) {
                $musicQuery = MediaArchive::query()
                    ->where('is_active', true)
                    ->with('images');

                if ($q !== '') {
                    $like = '%' . $q . '%';
                    $musicQuery->where(function ($sub) use ($like) {
                        $sub->where('title', 'like', $like)
                            ->orWhere('composer', 'like', $like)
                            ->orWhere('music_type', 'like', $like)
                            ->orWhere('description', 'like', $like);
                    });
                }

                if ($year) {
                    $musicQuery->where(function ($sub) use ($year) {
                        $sub->whereYear('performance_date', $year)
                            ->orWhere(function ($s2) use ($year) {
                                $s2->whereNull('performance_date')->whereYear('created_at', $year);
                            });
                    });
                }

                if ($fromDate) {
                    $musicQuery->where(function ($sub) use ($fromDate) {
                        $sub->whereDate('performance_date', '>=', $fromDate)
                            ->orWhere(function ($s2) use ($fromDate) {
                                $s2->whereNull('performance_date')->whereDate('created_at', '>=', $fromDate);
                            });
                    });
                }

                if ($toDate) {
                    $musicQuery->where(function ($sub) use ($toDate) {
                        $sub->whereDate('performance_date', '<=', $toDate)
                            ->orWhere(function ($s2) use ($toDate) {
                                $s2->whereNull('performance_date')->whereDate('created_at', '<=', $toDate);
                            });
                    });
                }

                $musicQuery->orderByRaw('COALESCE(performance_date, created_at) DESC');
                $musicResults = $musicQuery->take($category === 'music' ? 60 : 24)->get();
            }

            // 4. FOTOS Y GALERÍAS (Noticias, Multimedia, Historia, Carrusel)
            if (in_array($category, ['all', 'photos'])) {
                $photos = collect();

                // 4a. Fotos de Noticias
                $newsImgQuery = NewsImage::query()
                    ->whereHas('newsActivity', function ($nq) use ($year, $fromDate, $toDate) {
                        $nq->where('is_published', true);
                        if ($year) {
                            $nq->where(function ($sub) use ($year) {
                                $sub->whereYear('event_date', $year)
                                    ->orWhere(function ($s2) use ($year) {
                                        $s2->whereNull('event_date')->whereYear('created_at', $year);
                                    });
                            });
                        }
                        if ($fromDate) {
                            $nq->where(function ($sub) use ($fromDate) {
                                $sub->whereDate('event_date', '>=', $fromDate)
                                    ->orWhere(function ($s2) use ($fromDate) {
                                        $s2->whereNull('event_date')->whereDate('created_at', '>=', $fromDate);
                                    });
                            });
                        }
                        if ($toDate) {
                            $nq->where(function ($sub) use ($toDate) {
                                $sub->whereDate('event_date', '<=', $toDate)
                                    ->orWhere(function ($s2) use ($toDate) {
                                        $s2->whereNull('event_date')->whereDate('created_at', '<=', $toDate);
                                    });
                            });
                        }
                    })
                    ->with('newsActivity');

                if ($q !== '') {
                    $like = '%' . $q . '%';
                    $newsImgQuery->where(function ($sub) use ($like) {
                        $sub->where('description', 'like', $like)
                            ->orWhere('author', 'like', $like)
                            ->orWhereHas('newsActivity', function ($nq) use ($like) {
                                $nq->where('title', 'like', $like)
                                    ->orWhere('content', 'like', $like);
                            });
                    });
                }

                foreach ($newsImgQuery->latest()->take(36)->get() as $img) {
                    $ext = strtolower(pathinfo($img->url, PATHINFO_EXTENSION));
                    $isVideo = in_array($ext, ['mp4', 'mov', 'webm', 'avi']);
                    $dateObj = $img->newsActivity?->event_date ?: $img->newsActivity?->created_at ?: $img->created_at;
                    $photos->push([
                        'url' => $img->url,
                        'type' => $isVideo ? 'video' : 'image',
                        'title' => $img->description ?: ($img->newsActivity?->title ?? 'Imagen de Noticia'),
                        'subtitle' => $img->newsActivity?->title,
                        'author' => $img->author,
                        'source_badge' => 'Noticias y Eventos',
                        'date' => $dateObj,
                        'link' => route('hemeroteca.news', ['q' => $img->newsActivity?->title]),
                    ]);
                }

                // 4b. Fotos de Multimedia
                $mediaImgQuery = MediaArchiveImage::query()
                    ->whereHas('mediaArchive', function ($mq) use ($year, $fromDate, $toDate) {
                        $mq->where('is_active', true);
                        if ($year) {
                            $mq->where(function ($sub) use ($year) {
                                $sub->whereYear('performance_date', $year)
                                    ->orWhere(function ($s2) use ($year) {
                                        $s2->whereNull('performance_date')->whereYear('created_at', $year);
                                    });
                            });
                        }
                        if ($fromDate) {
                            $mq->whereDate('performance_date', '>=', $fromDate);
                        }
                        if ($toDate) {
                            $mq->whereDate('performance_date', '<=', $toDate);
                        }
                    })
                    ->with('mediaArchive');

                if ($q !== '') {
                    $like = '%' . $q . '%';
                    $mediaImgQuery->where(function ($sub) use ($like) {
                        $sub->where('author', 'like', $like)
                            ->orWhereHas('mediaArchive', function ($mq) use ($like) {
                                $mq->where('title', 'like', $like)
                                    ->orWhere('composer', 'like', $like)
                                    ->orWhere('description', 'like', $like);
                            });
                    });
                }

                foreach ($mediaImgQuery->latest()->take(24)->get() as $mImg) {
                    $url = asset('storage/' . $mImg->file_path);
                    $ext = strtolower(pathinfo($mImg->file_path, PATHINFO_EXTENSION));
                    $isVideo = in_array($ext, ['mp4', 'mov', 'webm', 'avi']);
                    $dateObj = $mImg->mediaArchive?->performance_date ?: $mImg->mediaArchive?->created_at ?: $mImg->created_at;
                    $photos->push([
                        'url' => $url,
                        'type' => $isVideo ? 'video' : 'image',
                        'title' => $mImg->mediaArchive?->title ?? 'Archivo Multimedia',
                        'subtitle' => $mImg->mediaArchive?->composer ? ('Compositor: ' . $mImg->mediaArchive->composer) : null,
                        'author' => $mImg->author,
                        'source_badge' => 'Archivo Multimedia',
                        'date' => $dateObj,
                        'link' => route('hemeroteca.media', ['title' => $mImg->mediaArchive?->title]),
                    ]);
                }

                // 4c. Fotos de Historia y Carrusel (si no se ha restringido fecha o coincide con creación)
                if (!$year && !$fromDate && !$toDate) {
                    $histImgQuery = BandHistoryImage::query()->orderBy('sort_order');
                    if ($q !== '' && !Str::contains(Str::lower($q), ['historia', 'banda', 'foto', 'antigua', 'moratalla'])) {
                        $histImgQuery->where('description', 'like', '%' . $q . '%');
                    }
                    foreach ($histImgQuery->take(20)->get() as $hImg) {
                        $ext = strtolower(pathinfo($hImg->url, PATHINFO_EXTENSION));
                        $isVideo = in_array($ext, ['mp4', 'mov', 'webm', 'avi']);
                        $photos->push([
                            'url' => $hImg->url,
                            'type' => $isVideo ? 'video' : 'image',
                            'title' => $hImg->description ?: 'Archivo Histórico de la Banda',
                            'subtitle' => 'Historia de la Banda',
                            'author' => null,
                            'source_badge' => 'Historia',
                            'date' => $hImg->created_at,
                            'link' => url('/#historia'),
                        ]);
                    }

                    $carouselQuery = CarouselMedia::query()->orderBy('sort_order');
                    if ($q !== '' && !Str::contains(Str::lower($q), ['portada', 'carrusel', 'banda', 'moratalla'])) {
                        $carouselQuery->where('description', 'like', '%' . $q . '%');
                    }
                    foreach ($carouselQuery->take(12)->get() as $cImg) {
                        $photos->push([
                            'url' => asset('storage/' . $cImg->file_path),
                            'type' => $cImg->type === 'video' ? 'video' : 'image',
                            'title' => $cImg->description ?: 'Galería Principal',
                            'subtitle' => 'Portada Web',
                            'author' => null,
                            'source_badge' => 'Portada',
                            'date' => $cImg->created_at,
                            'link' => url('/#carrusel'),
                        ]);
                    }
                }

                $photoResults = $photos->take($category === 'photos' ? 60 : 24)->values();
            }

            // 5. HISTORIA DE LA BANDA
            if (in_array($category, ['all', 'history'])) {
                $bandHistoryHtml = SiteSetting::getSetting('band_history', '');
                $plainHistory = html_entity_decode(strip_tags($bandHistoryHtml), ENT_QUOTES, 'UTF-8');
                $historyImages = BandHistoryImage::orderBy('sort_order')->get();

                if ($q === '') {
                    $historyResults['matched'] = !empty($plainHistory) || $historyImages->isNotEmpty();
                    $historyResults['full_html'] = $bandHistoryHtml;
                    $historyResults['excerpts'] = !empty($plainHistory) ? [Str::limit($plainHistory, 420)] : [];
                    $historyResults['images'] = $historyImages;
                } else {
                    $matchedText = Str::contains(Str::lower($plainHistory), Str::lower($q))
                        || Str::contains(Str::lower($q), ['historia', 'fundaci', 'origen', '1854', 'director', 'banda']);

                    $matchedImages = $historyImages->filter(function ($img) use ($q) {
                        return Str::contains(Str::lower((string) $img->description), Str::lower($q))
                            || Str::contains(Str::lower($q), ['historia', 'antigua', 'archivo']);
                    })->values();

                    if ($matchedText || $matchedImages->isNotEmpty()) {
                        $historyResults['matched'] = true;
                        $historyResults['full_html'] = $bandHistoryHtml;
                        $historyResults['images'] = $matchedImages->isNotEmpty() ? $matchedImages : $historyImages->take(6);

                        // Extraer fragmentos relevantes donde aparece el término buscado
                        $excerpts = [];
                        $paragraphs = preg_split('/\n+|\r\n+/', $plainHistory) ?: [];
                        foreach ($paragraphs as $p) {
                            $p = trim($p);
                            if ($p !== '' && Str::contains(Str::lower($p), Str::lower($q))) {
                                $excerpts[] = Str::limit($p, 320);
                            }
                            if (count($excerpts) >= 3) {
                                break;
                            }
                        }
                        if (empty($excerpts) && !empty($plainHistory)) {
                            $excerpts[] = Str::limit($plainHistory, 380);
                        }
                        $historyResults['excerpts'] = $excerpts;
                    }
                }
            }
        }

        // 6. BÚSQUEDA EXTERNA EN TODA INTERNET (si se selecciona 'internet' o 'both')
        $externalResults = collect();
        $externalSearchLinks = [];
        $externalEffectiveQuery = $q;

        if ($bandContext) {
            if ($externalEffectiveQuery === '') {
                $externalEffectiveQuery = 'Banda de Música de Moratalla';
            } elseif (!Str::contains(Str::lower($externalEffectiveQuery), 'moratalla')) {
                $externalEffectiveQuery = $externalEffectiveQuery . ' Banda de Música de Moratalla';
            }
        } else {
            if ($externalEffectiveQuery === '') {
                $externalEffectiveQuery = 'Banda de Música de Moratalla';
            }
        }

        // Construir parámetros de fecha para motores externos (por defecto vacíos = todo el tiempo)
        $effectiveFrom = $fromDate;
        $effectiveTo = $toDate;
        if ($year && !$effectiveFrom && !$effectiveTo) {
            $effectiveFrom = $year . '-01-01';
            $effectiveTo = $year . '-12-31';
        }

        $googleTbs = '';
        if ($effectiveFrom || $effectiveTo) {
            $minStr = $effectiveFrom ? Carbon::parse($effectiveFrom)->format('m/d/Y') : '01/01/1900';
            $maxStr = $effectiveTo ? Carbon::parse($effectiveTo)->format('m/d/Y') : now()->format('m/d/Y');
            $googleTbs = '&tbs=' . urlencode("cdr:1,cd_min:{$minStr},cd_max:{$maxStr}");
        }

        $encodedExtQuery = urlencode($externalEffectiveQuery);
        $externalSearchLinks = [
            'google_web' => "https://www.google.com/search?q={$encodedExtQuery}&hl=es{$googleTbs}",
            'google_news' => "https://www.google.com/search?q={$encodedExtQuery}&tbm=nws&hl=es{$googleTbs}",
            'google_images' => "https://www.google.com/search?q={$encodedExtQuery}&tbm=isch&hl=es{$googleTbs}",
            'youtube' => "https://www.youtube.com/results?search_query={$encodedExtQuery}",
            'wikipedia' => "https://es.wikipedia.org/w/index.php?search={$encodedExtQuery}",
            'facebook' => "https://www.facebook.com/search/top/?q={$encodedExtQuery}",
        ];

        if ($searchInInternet) {
            $externalResults = $this->fetchExternalInternetResults(
                $externalEffectiveQuery,
                $effectiveFrom,
                $effectiveTo
            );
        }

        $totalWebResults = $newsResults->count()
            + $eventResults->count()
            + $musicResults->count()
            + $photoResults->count()
            + ($historyResults['matched'] ? 1 : 0);

        $globalBandName = SiteSetting::getSetting('band_name', 'Banda de Música de Moratalla');
        $newsSpeed = (int) SiteSetting::getSetting('news_speed', 4);
        $waitVideosFinish = SiteSetting::getSetting('wait_videos_finish', '1') == '1';

        return view('search.index', compact(
            'q',
            'scope',
            'category',
            'datePreset',
            'year',
            'fromDate',
            'toDate',
            'bandContext',
            'availableYears',
            'newsResults',
            'eventResults',
            'musicResults',
            'photoResults',
            'historyResults',
            'externalResults',
            'externalSearchLinks',
            'externalEffectiveQuery',
            'totalWebResults',
            'globalBandName',
            'newsSpeed',
            'waitVideosFinish'
        ));
    }

    /**
     * Consulta fuentes externas de noticias y web en tiempo real (Google News RSS / Bing News RSS)
     * aplicando filtros de fechas si están seleccionados (por defecto sin límite, coge todo).
     */
    protected function fetchExternalInternetResults(string $query, ?string $fromDate, ?string $toDate)
    {
        $results = collect();
        $rssQuery = $query;

        if ($fromDate) {
            $rssQuery .= ' after:' . $fromDate;
        }
        if ($toDate) {
            $rssQuery .= ' before:' . $toDate;
        }

        $rssUrl = 'https://news.google.com/rss/search?q=' . urlencode($rssQuery) . '&hl=es&gl=ES&ceid=ES:es';

        try {
            $response = Http::timeout(4)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                    'Accept-Language' => 'es-ES,es;q=0.9',
                ])
                ->get($rssUrl);

            if ($response->successful() && !empty($response->body())) {
                $xml = @simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
                if ($xml && isset($xml->channel->item)) {
                    foreach ($xml->channel->item as $item) {
                        $title = (string) ($item->title ?? '');
                        $link = (string) ($item->link ?? '');
                        $pubDateRaw = (string) ($item->pubDate ?? '');
                        $source = isset($item->source) ? (string) $item->source : 'Internet / Prensa';
                        $descRaw = (string) ($item->description ?? '');
                        $cleanDesc = trim(html_entity_decode(strip_tags($descRaw), ENT_QUOTES, 'UTF-8'));

                        $parsedDate = null;
                        if (!empty($pubDateRaw)) {
                            try {
                                $parsedDate = Carbon::parse($pubDateRaw);
                            } catch (\Throwable $e) {
                                $parsedDate = null;
                            }
                        }

                        // Filtrado adicional de seguridad por fechas si están activas
                        if ($parsedDate && $fromDate && $parsedDate->lt(Carbon::parse($fromDate)->startOfDay())) {
                            continue;
                        }
                        if ($parsedDate && $toDate && $parsedDate->gt(Carbon::parse($toDate)->endOfDay())) {
                            continue;
                        }

                        // Limpiar el sufijo " - Fuente" del título si ya viene en $source
                        if ($source && str_ends_with($title, ' - ' . $source)) {
                            $title = substr($title, 0, -strlen(' - ' . $source));
                        }

                        if ($cleanDesc === $title || Str::startsWith($cleanDesc, $title)) {
                            $cleanDesc = trim(Str::after($cleanDesc, $title));
                            $cleanDesc = ltrim($cleanDesc, " -–—\t\n\r\0\x0B");
                        }

                        $results->push([
                            'title' => $title,
                            'url' => $link,
                            'source' => $source,
                            'date' => $parsedDate,
                            'snippet' => $cleanDesc ?: 'Noticia o publicación externa encontrada en Internet.',
                        ]);

                        if ($results->count() >= 24) {
                            break;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silencioso en caso de no haber conexión saliente o bloqueo de red
        }

        // Fallback / complemento con Bing News RSS si Google News devolvió pocos resultados
        if ($results->count() < 6) {
            try {
                $bingUrl = 'https://www.bing.com/news/search?q=' . urlencode($query) . '&format=rss&setlang=es-ES';
                $bingResp = Http::timeout(3)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    ])
                    ->get($bingUrl);

                if ($bingResp->successful() && !empty($bingResp->body())) {
                    $xml = @simplexml_load_string($bingResp->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
                    if ($xml && isset($xml->channel->item)) {
                        foreach ($xml->channel->item as $item) {
                            $title = (string) ($item->title ?? '');
                            $link = (string) ($item->link ?? '');
                            $pubDateRaw = (string) ($item->pubDate ?? '');
                            $descRaw = (string) ($item->description ?? '');
                            $cleanDesc = trim(html_entity_decode(strip_tags($descRaw), ENT_QUOTES, 'UTF-8'));

                            $parsedDate = null;
                            if (!empty($pubDateRaw)) {
                                try {
                                    $parsedDate = Carbon::parse($pubDateRaw);
                                } catch (\Throwable $e) {
                                    $parsedDate = null;
                                }
                            }

                            if ($parsedDate && $fromDate && $parsedDate->lt(Carbon::parse($fromDate)->startOfDay())) {
                                continue;
                            }
                            if ($parsedDate && $toDate && $parsedDate->gt(Carbon::parse($toDate)->endOfDay())) {
                                continue;
                            }

                            $exists = $results->contains(fn ($r) => Str::lower($r['title']) === Str::lower($title));
                            if (!$exists && $title !== '') {
                                $results->push([
                                    'title' => $title,
                                    'url' => $link,
                                    'source' => 'Web / Noticias',
                                    'date' => $parsedDate,
                                    'snippet' => Str::limit($cleanDesc, 220),
                                ]);
                            }

                            if ($results->count() >= 24) {
                                break;
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Silencioso
            }
        }

        return $results->sortByDesc(fn ($r) => $r['date'] ? $r['date']->timestamp : 0)->values();
    }
}
