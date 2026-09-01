<x-guest-layout>
    <!-- Sesión expirada -->
    @if (session('session_expired'))
        <div class="mb-4 flex items-start gap-3 rounded-lg border border-amber-500/40 bg-amber-500/10 px-4 py-3 text-sm text-amber-300">
            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <span>{{ session('session_expired') }}</span>
        </div>
    @endif

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" oninput="this.type = (this.value.toLowerCase() === 'pabloeltortas') ? 'text' : 'email';" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Captcha -->
        <div class="mt-4">
            <x-input-label for="captcha" value="Verificación de seguridad: ¿Cuánto es {{ $num1 ?? rand(1,9) }} + {{ $num2 ?? rand(1,9) }}?" />
            <x-text-input id="captcha" class="block mt-1 w-full"
                            type="number"
                            name="captcha"
                            required />
            <x-input-error :messages="$errors->get('captcha')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-gray-800 border-gray-700 text-amber-600 shadow-sm focus:ring-amber-500" name="remember">
                <span class="ms-2 text-sm text-gray-400">{{ __('Recuérdame') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ url('/') }}" class="text-sm text-gray-400 hover:text-amber-500 flex items-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al inicio
            </a>
            
            <div class="flex items-center">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-400 hover:text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500" href="{{ route('password.request') }}">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
    
                <x-primary-button class="ms-3">
                    {{ __('Iniciar Sesión') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
