<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        // Filtro: Pendientes vs Todos
        $status = $request->input('status', 'pendientes');
        if ($status === 'pendientes') {
            $query->where('event_date', '>=', Carbon::today());
        }

        // Filtro: Rango de fechas
        if ($request->filled('start_date')) {
            $query->where('event_date', '>=', Carbon::parse($request->start_date)->startOfDay());
        }
        if ($request->filled('end_date')) {
            $query->where('event_date', '<=', Carbon::parse($request->end_date)->endOfDay());
        }

        // Orden temporal ascendente
        $events = $query->orderBy('event_date', 'asc')->paginate(30)->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $event = Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Evento creado correctamente.');
    }

    public function show(Event $event)
    {
        return redirect()->route('admin.events.attendance', $event);
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Evento eliminado.');
    }

    public function attendance(Event $event)
    {
        // Solo obtener usuarios activos
        $users = User::where('is_active', true)->orderBy('name')->get();
        $attendances = $event->attendances()->get()->keyBy('user_id');

        return view('admin.events.attendance', compact('event', 'users', 'attendances'));
    }

    public function storeAttendance(Request $request, Event $event)
    {
        $request->validate([
            'attendance' => 'array',
            'parental_consent' => 'array',
        ]);

        $attendances = $request->input('attendance', []);
        $parentalConsents = $request->input('parental_consent', []);

        // Eliminamos todas las asistencias previas del evento
        $event->attendances()->delete();

        $insertData = [];
        foreach ($attendances as $userId => $status) {
            $insertData[] = [
                'event_id' => $event->id,
                'user_id' => $userId,
                'status' => $status,
                'has_parental_consent' => isset($parentalConsents[$userId]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        if (!empty($insertData)) {
            Attendance::insert($insertData);
        }

        return redirect()->route('admin.events.index')->with('success', 'Control de asistencia guardado correctamente.');
    }

    public function bulkCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'day_of_week' => 'required|integer|between:0,6',
            'time' => 'required|date_format:H:i',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $dayOfWeek = (int) $request->day_of_week;
        $timeParts = explode(':', $request->time);
        
        $currentDate = $startDate->copy();
        
        // Find the first matching day of week
        while ($currentDate->dayOfWeek !== $dayOfWeek) {
            $currentDate->addDay();
        }
        
        $count = 0;
        $insertData = [];
        
        while ($currentDate->lte($endDate)) {
            $eventDate = $currentDate->copy()->setTime((int)$timeParts[0], (int)$timeParts[1]);
            
            $insertData[] = [
                'name' => $request->name,
                'type' => $request->type,
                'event_date' => $eventDate,
                'is_active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            
            $count++;
            $currentDate->addWeek();
        }
        
        if (!empty($insertData)) {
            Event::insert($insertData);
        }
        
        return redirect()->route('admin.events.index')->with('success', "Se han generado $count eventos exitosamente.");
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'type' => 'nullable|string'
        ]);

        $fromDate = Carbon::parse($request->from_date)->startOfDay();
        $toDate = Carbon::parse($request->to_date)->endOfDay();
        $today = Carbon::now()->startOfDay();
        
        // Never allow deleting past events (must be >= today)
        if ($fromDate->lt($today)) {
            $fromDate = $today;
        }

        $query = Event::where('event_date', '>=', $fromDate)
                     ->where('event_date', '<=', $toDate);
                     
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $count = $query->count();
        $query->delete();
        
        return redirect()->route('admin.events.index')->with('success', "Se han eliminado $count eventos futuros.");
    }
}
