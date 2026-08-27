<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InstrumentCatalog;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('instruments')->orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $instruments = InstrumentCatalog::where('is_active', true)->orderBy('name')->get();
        return view('admin.users.create', compact('instruments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,musician'],
            'instruments' => ['nullable', 'array'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'birth_date' => $request->birth_date,
        ]);

        if ($request->has('instruments')) {
            $syncData = [];
            foreach ($request->instruments as $instrumentId) {
                $syncData[$instrumentId] = ['serial_number' => $request->input("serial_numbers.{$instrumentId}")];
            }
            $user->instruments()->sync($syncData);
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $instruments = InstrumentCatalog::orderBy('name')->get();
        
        $missedAttendances = \App\Models\Attendance::with('event')
            ->where('user_id', $user->id)
            ->whereIn('status', ['absent', 'excused'])
            ->get()
            ->sortByDesc(function($attendance) {
                return $attendance->event->event_date;
            });

        return view('admin.users.edit', compact('user', 'instruments', 'missedAttendances'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            'role' => ['required', 'in:admin,musician'],
            'instruments' => ['nullable', 'array'],
        ]);

        $user->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'leave_reason' => $request->has('is_active') ? null : $request->leave_reason,
            'birth_date' => $request->birth_date,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($request->has('instruments')) {
            $syncData = [];
            foreach ($request->instruments as $instrumentId) {
                $syncData[$instrumentId] = ['serial_number' => $request->input("serial_numbers.{$instrumentId}")];
            }
            $user->instruments()->sync($syncData);
        } else {
            $user->instruments()->detach();
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors('No puedes eliminar tu propia cuenta.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado.');
    }
}
