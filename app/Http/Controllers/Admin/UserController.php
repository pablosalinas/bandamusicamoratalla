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
        $brands = \App\Models\InstrumentBrand::orderBy('name')->get();
        return view('admin.users.create', compact('instruments', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,treasurer,director,musician'],
            'instruments' => ['nullable', 'array'],
            'address' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'iban' => ['nullable', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'city' => $request->city,
            'province' => $request->province,
            'phone' => $request->phone,
            'iban' => auth()->user()->canViewIban() ? $request->iban : null,
        ]);

        if ($request->has('instruments')) {
            $syncData = [];
            foreach ($request->instruments as $instrumentId) {
                $syncData[$instrumentId] = [
                    'serial_number' => $request->input("serial_numbers.{$instrumentId}"),
                    'tipo_partitura' => $request->input("tipo_partitura.{$instrumentId}"),
                    'propiedad' => $request->input("propiedad.{$instrumentId}"),
                    'is_active' => $request->has("is_active_instrument.{$instrumentId}"),
                    'instrument_brand_id' => $request->input("instrument_brand_id.{$instrumentId}"),
                    'modelo' => $request->input("modelo.{$instrumentId}")
                ];
            }
            $user->instruments()->sync($syncData);
            
            // Handle photos
            foreach ($request->instruments as $instrumentId) {
                if ($request->hasFile("photos.{$instrumentId}")) {
                    $pivot = \App\Models\MusicianInstrument::where('user_id', $user->id)
                                ->where('instrument_catalog_id', $instrumentId)
                                ->first();
                    if ($pivot) {
                        foreach ($request->file("photos.{$instrumentId}") as $file) {
                            $path = $file->store('instrument_photos', 'public');
                            \App\Services\ImageWatermarkService::applyWatermark(storage_path('app/public/' . $path));
                            $pivot->photos()->create(['photo_path' => $path]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(Request $request, User $user)
    {
        $instruments = InstrumentCatalog::orderBy('name')->get();
        
        $filter = $request->query('attendance_filter', 'absent');
        
        $statusMap = [
            'absent' => ['absent'],
            'excused' => ['excused'],
            'present' => ['present'],
        ];
        $statuses = $statusMap[$filter] ?? ['absent'];

        $attendances = \App\Models\Attendance::with('event')
            ->where('user_id', $user->id)
            ->whereIn('status', $statuses)
            ->get()
            ->sortByDesc(function($attendance) {
                return $attendance->event->event_date;
            });

        $userInstruments = $user->instruments->pluck('id')->toArray();
        $userInstrumentsData = $user->instruments->keyBy('id');

        $brands = \App\Models\InstrumentBrand::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'instruments', 'attendances', 'filter', 'userInstruments', 'userInstrumentsData', 'brands'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            'role' => ['required', 'in:admin,treasurer,director,musician'],
            'instruments' => ['nullable', 'array'],
            'address' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'iban' => ['nullable', 'string', 'max:50'],
        ]);

        $data = [
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'leave_reason' => $request->has('is_active') ? null : $request->leave_reason,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'city' => $request->city,
            'province' => $request->province,
            'phone' => $request->phone,
        ];

        if (auth()->user()->canViewIban()) {
            $data['iban'] = $request->iban;
        }

        $user->update($data);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($request->has('instruments')) {
            $syncData = [];
            foreach ($request->instruments as $instrumentId) {
                $syncData[$instrumentId] = [
                    'serial_number' => $request->input("serial_numbers.{$instrumentId}"),
                    'tipo_partitura' => $request->input("tipo_partitura.{$instrumentId}"),
                    'propiedad' => $request->input("propiedad.{$instrumentId}"),
                    'is_active' => $request->has("is_active_instrument.{$instrumentId}"),
                    'instrument_brand_id' => $request->input("instrument_brand_id.{$instrumentId}"),
                    'modelo' => $request->input("modelo.{$instrumentId}")
                ];
            }
            $user->instruments()->sync($syncData);

            // Handle photos
            foreach ($request->instruments as $instrumentId) {
                if ($request->hasFile("photos.{$instrumentId}")) {
                    $pivot = \App\Models\MusicianInstrument::where('user_id', $user->id)
                                ->where('instrument_catalog_id', $instrumentId)
                                ->first();
                    if ($pivot) {
                        foreach ($request->file("photos.{$instrumentId}") as $file) {
                            $path = $file->store('instrument_photos', 'public');
                            \App\Services\ImageWatermarkService::applyWatermark(storage_path('app/public/' . $path));
                            $pivot->photos()->create(['photo_path' => $path]);
                        }
                    }
                }
            }
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
