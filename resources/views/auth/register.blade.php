<x-guest-layout maxWidth="sm:max-w-3xl">
    <div class="mb-6 text-center border-b border-gray-800 pb-4">
        <h2 class="text-2xl font-bold text-white flex items-center justify-center gap-2">
            <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
            </svg>
            Solicitud de Alta de Músico
        </h2>
        <p class="text-sm text-gray-400 mt-1">Completa tus datos personales para solicitar tu incorporación a la Banda de Música de Moratalla.</p>
    </div>

    @if(!($allowRegistration ?? \App\Models\SiteSetting::getSetting('allow_musician_registration', '0') == '1'))
        <div class="rounded-lg border border-amber-500/40 bg-amber-500/10 p-6 text-center text-amber-300">
            <svg class="mx-auto h-12 w-12 text-amber-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <h3 class="text-lg font-semibold text-white mb-1">El alta online no está disponible</h3>
            <p class="text-sm text-gray-300">En este momento el registro público está desactivado por la administración. Si necesitas solicitar tu alta, contacta directamente con la junta directiva o acude a nuestros ensayos.</p>
            <div class="mt-6">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-500">
                    Volver al Acceso
                </a>
            </div>
        </div>
    @else
        @if (session('info'))
            <div class="mb-6 flex items-start gap-3 rounded-lg border border-amber-500/40 bg-amber-500/10 px-4 py-3 text-sm text-amber-300">
                <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if ($errors->has('guardian_phone_required'))
            <div class="mb-6 flex items-start gap-3 rounded-lg border border-red-500/50 bg-red-900/20 px-4 py-3 text-sm text-red-300">
                <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <span>{{ $errors->first('guardian_phone_required') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" x-data="{
            birthDate: '{{ old('birth_date', '') }}',
            get isMinor() {
                if (!this.birthDate) return false;
                const dob = new Date(this.birthDate);
                if (isNaN(dob.getTime())) return false;
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const m = today.getMonth() - dob.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                return age < 18;
            }
        }">
            @csrf

            <!-- BLOQUE 1: IDENTIFICACIÓN -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-amber-500 mb-3 border-b border-gray-800 pb-1">1. Datos Personales</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nombre -->
                    <div>
                        <x-input-label for="name" value="Nombre *" />
                        <x-text-input id="name" class="block mt-1 w-full uppercase" type="text" name="name" :value="old('name')" required autofocus placeholder="EJ. JUAN" oninput="this.value = this.value.toUpperCase()" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Apellidos -->
                    <div>
                        <x-input-label for="last_name" value="Apellidos *" />
                        <x-text-input id="last_name" class="block mt-1 w-full uppercase" type="text" name="last_name" :value="old('last_name')" required placeholder="EJ. GARCÍA LÓPEZ" oninput="this.value = this.value.toUpperCase()" />
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>

                    <!-- NIF / NIE -->
                    <div>
                        <x-input-label for="nif" value="NIF / NIE *" />
                        <x-text-input id="nif" class="block mt-1 w-full uppercase" type="text" name="nif" :value="old('nif')" required placeholder="12345678A" maxlength="20" oninput="this.value = this.value.toUpperCase()" />
                        <p class="text-[11px] text-gray-500 mt-1">Se verificará la letra algorítmica y que no esté ya registrado.</p>
                        <x-input-error :messages="$errors->get('nif')" class="mt-1" />
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div>
                        <x-input-label for="birth_date" value="Fecha de Nacimiento *" />
                        <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" x-model="birthDate" required style="color-scheme: dark;" />
                        <p class="text-[11px] text-gray-500 mt-1" x-show="!isMinor">Introduce tu fecha de nacimiento.</p>
                        <p class="text-[11px] text-amber-400 mt-1 font-semibold" x-show="isMinor">⚠️ Eres menor de edad. Se requiere al menos un teléfono de tutor legal.</p>
                        <x-input-error :messages="$errors->get('birth_date')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- SECCIÓN CONDICIONAL: MENORES DE EDAD -->
            <div x-show="isMinor" x-cloak class="mb-6 p-4 rounded-xl bg-amber-950/30 border border-amber-500/40">
                <div class="flex items-start gap-3 mb-3">
                    <svg class="h-5 w-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-white">Contacto de Padres o Tutores Legales (Obligatorio al menos uno)</h4>
                        <p class="text-xs text-amber-200/80">Al ser menor de 18 años, un responsable de la banda contactará con tu padre, madre o tutor legal para confirmar y autorizar tu alta como músico.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <x-input-label for="father_phone" value="Teléfono Padre" />
                        <x-text-input id="father_phone" class="block mt-1 w-full text-sm" type="tel" name="father_phone" :value="old('father_phone')" placeholder="600 000 000" />
                        <x-input-error :messages="$errors->get('father_phone')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="mother_phone" value="Teléfono Madre" />
                        <x-text-input id="mother_phone" class="block mt-1 w-full text-sm" type="tel" name="mother_phone" :value="old('mother_phone')" placeholder="600 000 000" />
                        <x-input-error :messages="$errors->get('mother_phone')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="guardian_phone" value="Teléfono Tutor/a" />
                        <x-text-input id="guardian_phone" class="block mt-1 w-full text-sm" type="tel" name="guardian_phone" :value="old('guardian_phone')" placeholder="600 000 000" />
                        <x-input-error :messages="$errors->get('guardian_phone')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- BLOQUE 2: DIRECCIÓN Y CONTACTO -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-amber-500 mb-3 border-b border-gray-800 pb-1">2. Contacto y Domicilio</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Teléfono Personal -->
                    <div>
                        <x-input-label for="phone" value="Teléfono Móvil del Músico *" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required placeholder="600 000 000" />
                        <p class="text-[11px] text-gray-500 mt-1">Teléfono principal de contacto y avisos.</p>
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>

                    <!-- Dirección -->
                    <div>
                        <x-input-label for="address" value="Dirección Completa (Calle, número, piso) *" />
                        <x-text-input id="address" class="block mt-1 w-full uppercase" type="text" name="address" :value="old('address')" required placeholder="C/ GRAN VÍA, 12, 2ºA" oninput="this.value = this.value.toUpperCase()" />
                        <x-input-error :messages="$errors->get('address')" class="mt-1" />
                    </div>

                    <!-- Código Postal -->
                    <div>
                        <x-input-label for="postal_code" value="Código Postal *" />
                        <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code')" required placeholder="30440" maxlength="10" />
                        <x-input-error :messages="$errors->get('postal_code')" class="mt-1" />
                    </div>

                    <!-- Población -->
                    <div>
                        <x-input-label for="city" value="Población *" />
                        <x-text-input id="city" class="block mt-1 w-full uppercase" type="text" name="city" :value="old('city', 'MORATALLA')" required placeholder="MORATALLA" oninput="this.value = this.value.toUpperCase()" />
                        <x-input-error :messages="$errors->get('city')" class="mt-1" />
                    </div>

                    <!-- Provincia -->
                    <div>
                        <x-input-label for="province" value="Provincia *" />
                        <x-text-input id="province" class="block mt-1 w-full uppercase" type="text" name="province" :value="old('province', 'MURCIA')" required placeholder="MURCIA" oninput="this.value = this.value.toUpperCase()" />
                        <x-input-error :messages="$errors->get('province')" class="mt-1" />
                    </div>

                    <!-- Año de Incorporación -->
                    <div>
                        <x-input-label for="joining_year" value="Año de Incorporación a la Banda (Opcional)" />
                        <x-text-input id="joining_year" class="block mt-1 w-full" type="number" name="joining_year" :value="old('joining_year', date('Y'))" placeholder="{{ date('Y') }}" min="1950" max="{{ date('Y') + 1 }}" />
                        <p class="text-[11px] text-gray-500 mt-1">Año en que entraste o tienes previsto debutar.</p>
                        <x-input-error :messages="$errors->get('joining_year')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- BLOQUE 3: CREDENCIALES DE ACCESO -->
            <div class="mb-8">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-amber-500 mb-4 border-b border-gray-800 pb-1">3. Credenciales de Acceso</h3>

                <!-- Aviso destacado y obligatorio de contraseña -->
                <div class="mb-6 p-5 bg-gradient-to-r from-amber-500/20 via-amber-900/30 to-amber-500/10 border-2 border-amber-500 rounded-xl shadow-lg shadow-amber-500/10">
                    <div class="flex items-start gap-3.5">
                        <div class="p-2 bg-amber-500 text-gray-950 rounded-lg shrink-0 mt-0.5">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base sm:text-lg font-extrabold text-amber-300 uppercase tracking-wide">
                                ADVERTENCIA DE SEGURIDAD MUY IMPORTANTE
                            </h4>
                            <p class="text-sm text-gray-200 mt-1 leading-relaxed">
                                <strong>Debes recordar y guardar tu contraseña en un lugar seguro.</strong>
                            </p>
                            <p class="text-xs text-amber-200/80 mt-1 leading-relaxed">
                                Esta clave es estrictamente confidencial y será la que te permitirá acceder a tu <em>Área de Músico</em>, consultar tus partituras, ensayos y eventos una vez que la junta directiva valide tu solicitud.
                            </p>
                        </div>
                    </div>

                    <!-- Checkbox de confirmación obligatoria -->
                    <div class="mt-4 pt-3.5 border-t border-amber-500/30">
                        <label for="password_remember_confirmed" class="flex items-start gap-3 cursor-pointer group">
                            <input id="password_remember_confirmed" type="checkbox" name="password_remember_confirmed" value="1" {{ old('password_remember_confirmed') ? 'checked' : '' }} required class="mt-0.5 h-5 w-5 rounded bg-gray-900 border-2 border-amber-400 text-amber-600 focus:ring-2 focus:ring-amber-500 cursor-pointer">
                            <span class="text-xs sm:text-sm font-semibold text-white group-hover:text-amber-200 select-none">
                                He leído esta advertencia y confirmo que he anotado o memorizado mi contraseña en un lugar seguro. *
                            </span>
                        </label>
                        <x-input-error :messages="$errors->get('password_remember_confirmed')" class="mt-2 text-xs" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Email -->
                    <div class="sm:col-span-2">
                        <x-input-label for="email" value="Correo Electrónico *" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $prefilledEmail)" required autocomplete="username" placeholder="tu-correo@ejemplo.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <x-input-label for="password" value="Contraseña *" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" :value="old('password', $prefilledPassword)" required autocomplete="new-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <x-input-label for="password_confirmation" value="Confirmar Contraseña *" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" :value="old('password_confirmation', $prefilledPassword)" required autocomplete="new-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- BLOQUE 4: CAPTCHA Y PROTECCIÓN DE DATOS -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-amber-500 mb-3 border-b border-gray-800 pb-1">4. Seguridad y Protección de Datos</h3>

                <!-- Captcha Antirrobot -->
                <div class="mb-4 p-4 bg-gray-950 rounded-lg border border-gray-800">
                    <x-input-label for="captcha" value="Verificación antirrobot: ¿Cuánto es {{ $num1 ?? rand(1,9) }} + {{ $num2 ?? rand(1,9) }}? *" />
                    <x-text-input id="captcha" class="block mt-2 w-full sm:w-48" type="number" name="captcha" required placeholder="Resultado" />
                    <x-input-error :messages="$errors->get('captcha')" class="mt-2" />
                </div>

                <!-- Cláusula Informativa RGPD en Grande -->
                @php
                    $bandContactEmail = \App\Models\SiteSetting::getSetting('band_email', 'bandamusicademoratalla@gmail.com');
                @endphp
                <div class="p-6 bg-gray-950 rounded-xl border-2 border-amber-500/40 shadow-md space-y-4 mb-5 text-gray-300 leading-relaxed">
                    <div class="flex items-center gap-3 border-b border-gray-800 pb-3">
                        <div class="p-2 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/30">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-base sm:text-lg font-bold text-white uppercase tracking-wide">
                                Información Básica sobre Protección de Datos (RGPD)
                            </h4>
                            <p class="text-xs text-gray-400">Reglamento (UE) 2016/679 y Ley Orgánica 3/2018 (LOPDGDD)</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                        <div class="p-3 bg-gray-900/90 rounded-lg border border-gray-800">
                            <strong class="text-amber-400 block font-semibold mb-1">Responsable del Tratamiento:</strong>
                            <span>Asociación Banda de Música de Moratalla.</span>
                        </div>
                        <div class="p-3 bg-gray-900/90 rounded-lg border border-gray-800">
                            <strong class="text-amber-400 block font-semibold mb-1">Legitimación:</strong>
                            <span>Consentimiento del interesado o tutor/a legal al enviar la solicitud.</span>
                        </div>
                        <div class="p-3 bg-gray-900/90 rounded-lg border border-gray-800 sm:col-span-2">
                            <strong class="text-amber-400 block font-semibold mb-1">Finalidad:</strong>
                            <span>Gestión de altas de componentes, archivo y asignación de partituras, control de asistencia a ensayos, salidas, actuaciones y actividades oficiales de la banda.</span>
                        </div>
                        <div class="p-3 bg-gray-900/90 rounded-lg border border-gray-800 sm:col-span-2">
                            <strong class="text-amber-400 block font-semibold mb-1">Destinatarios y Cesiones:</strong>
                            <span>No se cederán datos a terceros salvo obligación legal. No se recaban datos bancarios en este proceso.</span>
                        </div>
                        <div class="p-3 bg-gray-900/90 rounded-lg border border-gray-800 sm:col-span-2">
                            <strong class="text-amber-400 block font-semibold mb-1">Ejercicio de Derechos:</strong>
                            <span>Puedes acceder, rectificar, suprimir o solicitar la limitación del tratamiento de tus datos enviando un correo a <a href="mailto:{{ $bandContactEmail }}" class="text-amber-400 font-bold underline hover:text-amber-300">{{ $bandContactEmail }}</a> o dirigiéndote a la junta directiva.</span>
                        </div>
                    </div>
                </div>

                <!-- Checkbox RGPD Grande y Visible -->
                <div class="p-4 rounded-xl bg-amber-500/10 border-2 border-amber-500/50">
                    <label for="privacy_policy" class="flex items-start gap-3.5 cursor-pointer group">
                        <input id="privacy_policy" type="checkbox" class="mt-1 h-5 w-5 rounded bg-gray-900 border-2 border-amber-400 text-amber-600 focus:ring-2 focus:ring-amber-500 cursor-pointer" name="privacy_policy" value="1" {{ old('privacy_policy') ? 'checked' : '' }} required>
                        <span class="text-sm font-semibold text-white group-hover:text-amber-200 select-none leading-relaxed">
                            He leído íntegramente la información anterior, acepto la <a href="{{ route('legal') }}" target="_blank" class="text-amber-400 underline font-bold hover:text-amber-300">política de protección de datos</a> y autorizo el tratamiento de mis datos personales para la gestión interna en la banda de música. *
                        </span>
                    </label>
                    <x-input-error :messages="$errors->get('privacy_policy')" class="mt-2 text-xs" />
                </div>
            </div>

            <!-- BOTONES -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-8 pt-4 border-t border-gray-800">
                <a class="text-sm text-gray-400 hover:text-white flex items-center gap-1 transition-colors" href="{{ route('login') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    ¿Ya tienes cuenta? Iniciar Sesión
                </a>

                <x-primary-button class="w-full sm:w-auto justify-center">
                    Enviar Solicitud de Alta
                </x-primary-button>
            </div>
        </form>
    @endif
</x-guest-layout>
