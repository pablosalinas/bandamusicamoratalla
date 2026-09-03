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
    public function index()
    {
        $events = Event::orderBy('event_date', 'desc')->paginate(15);
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
}
