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
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = $request->query('search', '');
        $orderBy = $request->query('order_by', 'last_name');

        $query = User::with('inventories.instrument');

        if ($orderBy === 'name') {
            $query->orderBy('name')->orderBy('last_name');
        } else {
            $query->orderBy('last_name')->orderBy('name');
        }

        if ($status === 'pending') {
            $query->where('is_active', false)->where('role', 'musician');
        } elseif ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nif', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $pendingCount = User::pendingValidation()->count();
        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'status', 'search', 'orderBy', 'pendingCount'));
    }

    public function validateMusician(User $user)
    {
        $user->update([
            'is_active' => true,
            'leave_reason' => null
        ]);

        return redirect()->back()->with('success', "El músico {$user->name} {$user->last_name} ha sido validado y activado correctamente.");
    }

    public function toggleActive(User $user)
    {
        $newStatus = !$user->is_active;
        $user->update([
            'is_active' => $newStatus,
            'leave_reason' => $newStatus ? null : $user->leave_reason,
        ]);

        $statusText = $newStatus ? 'activado' : 'desactivado';
        return redirect()->back()->with('success', "El usuario {$user->name} {$user->last_name} ha sido {$statusText}.");
    }

    public function create()
    {
        $instruments = InstrumentCatalog::where('is_active', true)->orderBy('name')->get();
        $brands = \App\Models\InstrumentBrand::orderBy('name')->get();
        return view('admin.users.create', compact('instruments', 'brands'));
    }

    public function store(Request $request)
    {
        $cleanNif = $request->filled('nif') ? strtoupper(trim(str_replace([' ', '-'], '', $request->input('nif')))) : null;
        $cleanPhone = $request->filled('phone') ? trim(str_replace([' ', '-', '.'], '', $request->input('phone'))) : null;

        $request->merge([
            'nif' => $cleanNif,
            'phone' => $cleanPhone,
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email'],
            'nif' => ['nullable', 'string', new \App\Rules\ValidNif, 'unique:'.User::class.',nif'],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,treasurer,director,musician'],
            'instruments' => ['nullable', 'array'],
            'address' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50', 'unique:'.User::class.',phone'],
            'father_phone' => ['nullable', 'string', 'max:50'],
            'mother_phone' => ['nullable', 'string', 'max:50'],
            'guardian_phone' => ['nullable', 'string', 'max:50'],
            'joining_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'iban' => ['nullable', 'string', 'max:50', new \App\Rules\ValidIban],
        ], [
            'nif.unique' => 'Ya existe un usuario con este NIF / NIE.',
            'phone.unique' => 'Ya existe un usuario con este número de teléfono.',
        ]);

        $user = User::create([
            'name' => mb_strtoupper(trim($request->name), 'UTF-8'),
            'last_name' => mb_strtoupper(trim($request->last_name), 'UTF-8'),
            'nif' => $cleanNif,
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'birth_date' => $request->birth_date,
            'address' => $request->filled('address') ? mb_strtoupper(trim($request->address), 'UTF-8') : null,
            'postal_code' => $request->postal_code,
            'city' => $request->filled('city') ? mb_strtoupper(trim($request->city), 'UTF-8') : null,
            'province' => $request->filled('province') ? mb_strtoupper(trim($request->province), 'UTF-8') : null,
            'phone' => $cleanPhone,
            'father_phone' => $request->father_phone,
            'mother_phone' => $request->mother_phone,
            'guardian_phone' => $request->guardian_phone,
            'joining_year' => $request->joining_year,
            'iban' => auth()->user()->canViewIban() ? $request->iban : null,
        ]);

        // Note: Instruments and photos are now handled via the Inventory system after user creation.

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

        $startDate = $request->query('start_date', now()->subYear()->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());

        $attendances = \App\Models\Attendance::with('event')
            ->where('user_id', $user->id)
            ->whereIn('status', $statuses)
            ->whereHas('event', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('event_date', [$startDate, $endDate]);
            })
            ->get()
            ->sortByDesc(function($attendance) {
                return $attendance->event->event_date;
            });

        $userInstruments = $user->inventories;

        $brands = \App\Models\InstrumentBrand::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'instruments', 'attendances', 'filter', 'userInstruments', 'brands'));
    }

    public function update(Request $request, User $user)
    {
        $cleanNif = $request->filled('nif') ? strtoupper(trim(str_replace([' ', '-'], '', $request->input('nif')))) : null;
        $cleanPhone = $request->filled('phone') ? trim(str_replace([' ', '-', '.'], '', $request->input('phone'))) : null;

        $request->merge([
            'nif' => $cleanNif,
            'phone' => $cleanPhone,
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            'nif' => ['nullable', 'string', new \App\Rules\ValidNif, 'unique:'.User::class.',nif,'.$user->id],
            'role' => ['required', 'in:admin,treasurer,director,musician'],
            'instruments' => ['nullable', 'array'],
            'address' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50', 'unique:'.User::class.',phone,'.$user->id],
            'father_phone' => ['nullable', 'string', 'max:50'],
            'mother_phone' => ['nullable', 'string', 'max:50'],
            'guardian_phone' => ['nullable', 'string', 'max:50'],
            'joining_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'iban' => ['nullable', 'string', 'max:50', new \App\Rules\ValidIban],
        ], [
            'nif.unique' => 'Ya existe otro usuario con este NIF / NIE.',
            'phone.unique' => 'Ya existe otro usuario con este número de teléfono.',
        ]);

        $data = [
            'name' => mb_strtoupper(trim($request->name), 'UTF-8'),
            'last_name' => mb_strtoupper(trim($request->last_name), 'UTF-8'),
            'nif' => $cleanNif,
            'email' => strtolower(trim($request->email)),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'leave_reason' => $request->has('is_active') ? null : $request->leave_reason,
            'birth_date' => $request->birth_date,
            'address' => $request->filled('address') ? mb_strtoupper(trim($request->address), 'UTF-8') : null,
            'postal_code' => $request->postal_code,
            'city' => $request->filled('city') ? mb_strtoupper(trim($request->city), 'UTF-8') : null,
            'province' => $request->filled('province') ? mb_strtoupper(trim($request->province), 'UTF-8') : null,
            'phone' => $cleanPhone,
            'father_phone' => $request->father_phone,
            'mother_phone' => $request->mother_phone,
            'guardian_phone' => $request->guardian_phone,
            'joining_year' => $request->joining_year,
        ];

        if (auth()->user()->canViewIban()) {
            $data['iban'] = $request->iban;
        }

        $user->update($data);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Note: Instruments and photos are now handled via the Inventory system.

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

    public function generateParentalConsent(Request $request, User $user)
    {
        $template = \App\Models\SiteSetting::getSetting('parental_consent_template', '');
        if (empty($template)) {
            return redirect()->back()->with('error', 'El modelo de justificante parental no ha sido configurado en los ajustes.');
        }

        $userName = $user->name . ' ' . $user->last_name;
        $eventInfo = '________________________________________________________________';
        if ($request->filled('event_id')) {
            $event = \App\Models\Event::find($request->event_id);
            if ($event) {
                \Carbon\Carbon::setLocale('es');
                $eventInfo = $event->name . ' el ' . \Carbon\Carbon::parse($event->event_date)->translatedFormat('d \d\e F \d\e Y');
            }
        }
        
        $currentDate = now()->translatedFormat('d \d\e F \d\e Y');

        $template = str_replace(
            [
                '<nombre>', '&lt;nombre&gt;', '[nombre]', '[NOMBRE]',
                '<evento>', '&lt;evento&gt;', '[evento]', '[EVENTO]',
                '<fecha>', '&lt;fecha&gt;', '[fecha]', '[FECHA]'
            ],
            [
                $userName, $userName, $userName, $userName,
                $eventInfo, $eventInfo, $eventInfo, $eventInfo,
                'Fecha: ' . $currentDate, 'Fecha: ' . $currentDate, 'Fecha: ' . $currentDate, 'Fecha: ' . $currentDate
            ],
            $template
        );

        return view('shared.parental_consent_pdf', compact('template', 'userName'));
    }

    protected function buildFilteredQuery(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = $request->query('search', '');
        $orderBy = $request->query('order_by', 'last_name');

        $query = User::query();

        if ($orderBy === 'name') {
            $query->orderBy('name')->orderBy('last_name');
        } else {
            $query->orderBy('last_name')->orderBy('name');
        }

        if ($status === 'pending') {
            $query->where('is_active', false)->where('role', 'musician');
        } elseif ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nif', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function exportPdf(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = $request->query('search', '');
        $orderBy = $request->query('order_by', 'last_name');
        $users = $this->buildFilteredQuery($request)->get();

        return view('admin.users.pdf', compact('users', 'status', 'search', 'orderBy'));
    }

    public function exportCsv(Request $request)
    {
        $status = $request->query('status', 'all');
        $orderBy = $request->query('order_by', 'last_name');
        $users = $this->buildFilteredQuery($request)->get();

        $statusLabels = [
            'all' => 'todos',
            'active' => 'activos',
            'inactive' => 'bajas_inactivos',
            'pending' => 'pendientes_validacion'
        ];
        $label = $statusLabels[$status] ?? 'listado';
        $filename = 'musicos_' . $label . '_orden_' . $orderBy . '_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($users, $orderBy) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 para Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados dinámicos según el orden seleccionado
            $nameHeader = ($orderBy === 'name') ? 'NOMBRE COMPLETO (NOMBRE Y APELLIDOS)' : 'NOMBRE COMPLETO (APELLIDOS, NOMBRE)';

            fputcsv($handle, [
                $nameHeader,
                'NOMBRE',
                'APELLIDOS',
                'NIF/NIE',
                'FECHA NACIMIENTO',
                'EDAD',
                'EMAIL',
                'TELÉFONO',
                'TEL. PADRE',
                'TEL. MADRE',
                'TEL. TUTOR',
                'DIRECCIÓN',
                'CÓDIGO POSTAL',
                'LOCALIDAD',
                'PROVINCIA',
                'AÑO INGRESO',
                'ROL',
                'ESTADO',
                'MOTIVO BAJA',
                'FECHA ALTA SISTEMA'
            ], ';');

            $rolesEsp = [
                'admin' => 'Administrador',
                'treasurer' => 'Tesorero',
                'director' => 'Director',
                'musician' => 'Músico',
                'external' => 'Externo',
            ];

            foreach ($users as $user) {
                $age = $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->age : '';
                $estado = $user->is_active ? 'Activo' : ($user->privacy_accepted_at ? 'Pendiente Validación' : 'Inactivo / Baja');
                $rolNombre = $rolesEsp[$user->role] ?? ucfirst($user->role);

                // Composición del nombre según orden seleccionado
                $composedName = ($orderBy === 'name')
                    ? trim($user->name . ' ' . $user->last_name)
                    : trim($user->last_name . ', ' . $user->name);

                fputcsv($handle, [
                    $composedName,
                    $user->name,
                    $user->last_name,
                    $user->nif,
                    $user->birth_date ? $user->birth_date->format('d/m/Y') : '',
                    $age,
                    $user->email,
                    $user->phone,
                    $user->father_phone,
                    $user->mother_phone,
                    $user->guardian_phone,
                    $user->address,
                    $user->postal_code,
                    $user->city,
                    $user->province,
                    $user->joining_year,
                    $rolNombre,
                    $estado,
                    $user->leave_reason,
                    $user->created_at ? $user->created_at->format('d/m/Y H:i') : '',
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
