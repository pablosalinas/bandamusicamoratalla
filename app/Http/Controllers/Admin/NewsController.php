<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsActivity;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class NewsController extends Controller
{
    public function index()
    {
        $news = NewsActivity::with(['mainImage'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'event_date' => 'nullable|date',
            'is_published' => 'boolean',
            'show_in_hemeroteca' => 'boolean',
            'active_from' => 'nullable|date',
            'active_to' => 'nullable|date|after_or_equal:active_from',
            'create_event' => 'nullable|boolean',
            'event_time' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $validated['is_published'] = $request->has('is_published');
        $validated['show_in_hemeroteca'] = $request->has('show_in_hemeroteca');

        // Si se marca crear como evento:
        $createdEvent = null;
        if ($request->boolean('create_event')) {
            $eventDate = $validated['event_date'] ? Carbon::parse($validated['event_date']) : Carbon::now();
            if ($request->filled('event_time')) {
                $timeParts = explode(':', $request->event_time);
                if (count($timeParts) >= 2) {
                    $eventDate->setTime((int)$timeParts[0], (int)$timeParts[1]);
                }
            } else {
                $eventDate->setTime(20, 0);
            }

            $createdEvent = Event::create([
                'name' => $validated['title'],
                'description' => $validated['content'],
                'type' => 'propias',
                'event_date' => $eventDate,
                'is_active' => true,
            ]);

            if (Schema::hasColumn('news_activities', 'event_id')) {
                $validated['event_id'] = $createdEvent->id;
            }
        }

        $news = NewsActivity::create($validated);

        if ($createdEvent && empty($news->event_id) && Schema::hasColumn('news_activities', 'event_id')) {
            $news->updateQuietly(['event_id' => $createdEvent->id]);
        }

        $msg = 'Noticia creada exitosamente. Ahora puedes añadir imágenes.';
        if ($createdEvent) {
            $msg = 'Noticia y Evento "' . $createdEvent->name . '" creados exitosamente en el calendario.';
        }

        return redirect()->route('admin.news.edit', $news)->with('success', $msg);
    }

    public function edit(NewsActivity $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, NewsActivity $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'event_date' => 'nullable|date',
            'is_published' => 'boolean',
            'show_in_hemeroteca' => 'boolean',
            'active_from' => 'nullable|date',
            'active_to' => 'nullable|date|after_or_equal:active_from',
            'sync_event' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $validated['is_published'] = $request->has('is_published');
        $validated['show_in_hemeroteca'] = $request->has('show_in_hemeroteca');

        $news->update($validated);

        // Sincronizar evento si el usuario marcó la opción
        $syncedEvent = false;
        if ($request->boolean('sync_event')) {
            $event = $news->linked_event;
            if ($event) {
                $eventData = [
                    'name' => $news->title,
                    'description' => $news->content,
                ];
                if ($news->event_date) {
                    $prevTime = $event->event_date ? $event->event_date->format('H:i') : '20:00';
                    $timeParts = explode(':', $prevTime);
                    $d = Carbon::parse($news->event_date->format('Y-m-d'));
                    if (count($timeParts) >= 2) {
                        $d->setTime((int)$timeParts[0], (int)$timeParts[1]);
                    }
                    $eventData['event_date'] = $d;
                }
                $event->update($eventData);
                $syncedEvent = true;
            }
        }

        $msg = 'Noticia actualizada exitosamente.';
        if ($syncedEvent) {
            $msg .= ' Se ha actualizado también la información del Evento vinculado.';
        }

        return redirect()->route('admin.news.edit', $news)->with('success', $msg);
    }

    public function createEvent(Request $request, NewsActivity $news)
    {
        if ($news->linked_event) {
            return back()->with('info', 'Esta noticia ya está vinculada al evento "' . $news->linked_event->name . '".');
        }

        $eventDate = $news->event_date ? Carbon::parse($news->event_date->format('Y-m-d')) : Carbon::now();
        if ($request->filled('event_time')) {
            $timeParts = explode(':', $request->event_time);
            if (count($timeParts) >= 2) {
                $eventDate->setTime((int)$timeParts[0], (int)$timeParts[1]);
            }
        } else {
            $eventDate->setTime(20, 0);
        }

        $event = Event::create([
            'name' => $news->title,
            'description' => $news->content,
            'type' => 'propias',
            'event_date' => $eventDate,
            'is_active' => true,
        ]);

        if (Schema::hasColumn('news_activities', 'event_id')) {
            $news->event_id = $event->id;
            $news->save();
        }

        return back()->with('success', 'Evento "' . $event->name . '" creado exitosamente en el calendario a partir de esta noticia.');
    }

    public function syncEvent(Request $request, NewsActivity $news)
    {
        $event = $news->linked_event;
        if (!$event) {
            return back()->with('error', 'No se ha encontrado ningún evento vinculado a esta noticia.');
        }

        $eventData = [
            'name' => $news->title,
            'description' => $news->content,
        ];

        if ($news->event_date) {
            $prevTime = $event->event_date ? $event->event_date->format('H:i') : '20:00';
            $timeParts = explode(':', $prevTime);
            $d = Carbon::parse($news->event_date->format('Y-m-d'));
            if (count($timeParts) >= 2) {
                $d->setTime((int)$timeParts[0], (int)$timeParts[1]);
            }
            $eventData['event_date'] = $d;
        }

        $event->update($eventData);

        return back()->with('success', 'Evento "' . $event->name . '" actualizado correctamente con el título, fecha y contenido de la noticia.');
    }

    public function destroy(NewsActivity $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Noticia eliminada.');
    }
}

