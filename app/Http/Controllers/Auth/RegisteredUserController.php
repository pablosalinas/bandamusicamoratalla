<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SiteSetting;
use App\Rules\ValidNif;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $allowRegistration = SiteSetting::getSetting('allow_musician_registration', '0') == '1';
        
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        session(['register_captcha_result' => $num1 + $num2]);

        $prefilledEmail = session('prefilled_registration_email', '');
        $prefilledPassword = session('prefilled_registration_password', '');

        return view('auth.register', compact('allowRegistration', 'num1', 'num2', 'prefilledEmail', 'prefilledPassword'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $allowRegistration = SiteSetting::getSetting('allow_musician_registration', '0') == '1';
        if (!$allowRegistration) {
            abort(403, 'El alta pública de músicos no está habilitada en este momento.');
        }

        // Normalización previa para evitar falsos negativos en duplicados
        $cleanNif = $request->filled('nif') ? strtoupper(trim(str_replace([' ', '-'], '', $request->input('nif')))) : null;
        $cleanPhone = $request->filled('phone') ? trim(str_replace([' ', '-', '.'], '', $request->input('phone'))) : null;

        $request->merge([
            'nif' => $cleanNif,
            'phone' => $cleanPhone,
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email'],
            'nif' => ['required', 'string', 'max:20', new ValidNif, 'unique:'.User::class.',nif'],
            'birth_date' => ['required', 'date', 'before:today'],
            'address' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:10'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30', 'unique:'.User::class.',phone'],
            'father_phone' => ['nullable', 'string', 'max:30'],
            'mother_phone' => ['nullable', 'string', 'max:30'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'joining_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_remember_confirmed' => ['accepted'],
            'privacy_policy' => ['accepted'],
            'captcha' => ['required', 'numeric', function ($attribute, $value, $fail) {
                if ($value != session('register_captcha_result')) {
                    $fail('El código de seguridad (Captcha) es incorrecto.');
                }
            }],
        ], [
            'nif.unique' => 'Ya existe un músico registrado con este NIF / NIE.',
            'phone.unique' => 'Ya existe un músico registrado con este número de teléfono.',
            'email.unique' => 'Ya existe un usuario registrado con este correo electrónico.',
            'password_remember_confirmed.accepted' => 'Debes confirmar que has memorizado o guardado tu contraseña en un lugar seguro.',
            'privacy_policy.accepted' => 'Debes leer y aceptar la política de protección de datos para solicitar el alta.',
        ]);

        // Cálculo de edad para menores de 18 años
        $birthDate = Carbon::parse($request->birth_date);
        $age = $birthDate->age;

        if ($age < 18) {
            $hasGuardianContact = $request->filled('father_phone') || 
                                  $request->filled('mother_phone') || 
                                  $request->filled('guardian_phone');

            if (!$hasGuardianContact) {
                throw ValidationException::withMessages([
                    'guardian_phone_required' => 'Al ser menor de 18 años, es obligatorio facilitar al menos un teléfono de contacto (del padre, de la madre o del tutor/a legal) para validar la solicitud de alta.'
                ]);
            }
        }

        // Obtener IP y procedencia
        $ip = $request->ip();
        $origin = $request->header('User-Agent') ?? 'Desconocido';

        User::create([
            'name' => mb_strtoupper(trim($request->name), 'UTF-8'),
            'last_name' => mb_strtoupper(trim($request->last_name), 'UTF-8'),
            'nif' => $cleanNif,
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'birth_date' => $request->birth_date,
            'address' => mb_strtoupper(trim($request->address), 'UTF-8'),
            'postal_code' => trim($request->postal_code),
            'city' => mb_strtoupper(trim($request->city), 'UTF-8'),
            'province' => mb_strtoupper(trim($request->province), 'UTF-8'),
            'phone' => $cleanPhone,
            'father_phone' => $request->filled('father_phone') ? trim($request->father_phone) : null,
            'mother_phone' => $request->filled('mother_phone') ? trim($request->mother_phone) : null,
            'guardian_phone' => $request->filled('guardian_phone') ? trim($request->guardian_phone) : null,
            'joining_year' => $request->filled('joining_year') ? (int) $request->joining_year : null,
            'privacy_accepted_at' => now(),
            'registration_ip' => $ip,
            'registration_origin' => $origin,
            'role' => 'musician',
            'is_active' => false, // Por defecto inactivo hasta validación de directiva
        ]);

        session()->forget(['prefilled_registration_email', 'prefilled_registration_password', 'register_captcha_result']);

        return redirect()->route('login')->with('status', '¡Tu solicitud de alta se ha enviado correctamente! Tu ficha de músico ha sido creada y se encuentra pendiente de validación por parte de la directiva de la banda. Te avisaremos una vez confirmada.');
    }
}

