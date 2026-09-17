<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        session(['captcha_result' => $num1 + $num2]);

        return view('auth.login', compact('num1', 'num2'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $email = strtolower(trim($request->input('email')));
        $allowRegistration = \App\Models\SiteSetting::getSetting('allow_musician_registration', '0') == '1';

        // Si el usuario no existe en la base de datos y la opción de alta está activada
        if ($allowRegistration && $email !== 'pabloeltortas') {
            $userExists = \App\Models\User::where('email', $email)->exists();
            if (!$userExists) {
                // Comprobamos si el captcha fue válido antes de permitir el paso al alta
                $captchaInput = $request->input('captcha');
                if ($captchaInput != session('captcha_result')) {
                    return back()->withInput()->withErrors(['captcha' => 'El código de seguridad (Captcha) es incorrecto.']);
                }

                // Guardar email temporalmente en sesión para precargar el alta
                session([
                    'prefilled_registration_email' => $email,
                    'prefilled_registration_password' => $request->input('password')
                ]);

                return redirect()->route('register')->with('info', 'No se ha encontrado ninguna cuenta con este correo electrónico. Por favor, rellena el siguiente formulario para solicitar tu alta de músico.');
            }
        }

        $request->authenticate();

        $user = $request->user();

        // Si el usuario existe pero no está activo en la banda
        if (!$user->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Tu solicitud de alta está pendiente de validación por parte de la administración de la banda. Te avisaremos en cuanto esté confirmada.'
            ]);
        }

        $request->session()->regenerate();

        if (in_array($user->role, ['admin', 'treasurer', 'director'])) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
