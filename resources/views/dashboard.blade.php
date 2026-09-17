<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">
            {{ __('Área del Músico') }}
        </h2>
    </x-slot>

    <div class="mt-8 flow-root">
        <div class="bg-gray-900 border border-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-300">
                @if(!$user->photo_path)
                    <div class="mb-8 rounded-md bg-amber-900/40 border border-amber-600/50 p-4 shadow-sm">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-0.5">
                                <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2.25m0 4.5.01-.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-medium text-amber-500">Foto de perfil no configurada</h3>
                                <div class="mt-1 text-sm text-amber-200/80">
                                    <p>Aún no has subido tu foto de perfil. Por favor, ve a tu perfil y sube una para tener tu ficha completada.</p>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-amber-400 hover:text-amber-300">
                                        Subir foto ahora <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <h3 class="text-xl font-bold mb-4 text-white">¡Hola, {{ $user->name }}!</h3>
                
                @if(isset($currentFiscalYear))
                    <div class="mb-8 p-6 rounded-lg border {{ $currentFiscalYear->balance >= 0 ? 'bg-amber-900/20 border-amber-500/30' : 'bg-red-900/20 border-red-500/30' }}">
                        <h4 class="text-lg font-semibold mb-2 border-b {{ $currentFiscalYear->balance >= 0 ? 'border-amber-800/50 text-amber-500' : 'border-red-800/50 text-red-400' }} pb-2">
                            Resumen Económico ({{ $currentFiscalYear->name }})
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center mt-4">
                            <div>
                                <span class="block text-sm text-gray-400">Total Ingresos</span>
                                <span class="block text-xl font-bold text-green-400">{{ number_format($currentFiscalYear->total_income, 2, ',', '.') }} €</span>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-400">Total Gastos</span>
                                <span class="block text-xl font-bold text-red-400">{{ number_format($currentFiscalYear->total_expense, 2, ',', '.') }} €</span>
                            </div>
                            <div class="border-t border-gray-700 pt-2 md:border-t-0 md:border-l md:pt-0">
                                <span class="block text-sm text-gray-400">Saldo Actual</span>
                                <span class="block text-2xl font-bold {{ $currentFiscalYear->balance >= 0 ? 'text-amber-500' : 'text-red-500' }}">{{ number_format($currentFiscalYear->balance, 2, ',', '.') }} €</span>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="mb-8 bg-gray-950 p-6 rounded-lg border border-gray-800">
                    <h4 class="text-lg font-semibold mb-4 border-b border-gray-800 pb-2 text-white">Tus Datos Personales</h4>
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Foto de perfil -->
                        <div class="flex-shrink-0">
                            @if($user->photo_path)
                                <img src="{{ $user->photo_url }}" alt="Foto de {{ $user->name }}" class="w-28 h-28 rounded-lg object-cover border border-gray-700 shadow-md">
                            @else
                                <div class="w-28 h-28 rounded-lg bg-gray-800 border border-gray-700 flex items-center justify-center text-gray-500 shadow-md">
                                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Datos -->
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500 block">Nombre:</span> <span class="text-white">{{ $user->name }}</span></div>
                            <div><span class="text-gray-500 block">Apellidos:</span> <span class="text-white">{{ $user->last_name }}</span></div>
                            <div><span class="text-gray-500 block">DNI / NIF:</span> <span class="text-white font-mono text-amber-400">{{ $user->nif ?: '-' }}</span></div>
                            <div><span class="text-gray-500 block">Email:</span> <span class="text-white">{{ $user->email }}</span></div>
                            <div><span class="text-gray-500 block">Teléfono Móvil:</span> <span class="text-white">{{ $user->phone ?: '-' }}</span></div>
                            <div><span class="text-gray-500 block">Fecha de Nacimiento:</span> <span class="text-white">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : '-' }}</span></div>
                            <div><span class="text-gray-500 block">Dirección:</span> <span class="text-white">{{ $user->address ?: '-' }}, {{ $user->postal_code ?: '-' }} {{ $user->city ?: '-' }} ({{ $user->province ?: '-' }})</span></div>
                            <div>
                                <span class="text-gray-500 block">Año de Incorporación:</span> 
                                <span class="text-white">{{ $user->joining_year ? 'Año ' . $user->joining_year : '-' }}</span>
                            </div>

                            <div>
                                <span class="text-gray-500 block">Protección de Datos (RGPD):</span>
                                @if($user->privacy_accepted_at)
                                    <span class="inline-flex items-center gap-1 text-emerald-400 text-xs font-semibold">
                                        <svg class="w-4 h-4 text-emerald-400 inline" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        Aceptada el {{ \Carbon\Carbon::parse($user->privacy_accepted_at)->format('d/m/Y H:i') }} (En vigor)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-amber-400 text-xs font-semibold">
                                        <svg class="w-4 h-4 text-amber-400 inline" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        Aceptada por condición de socio / En vigor
                                    </span>
                                @endif
                            </div>

                            @if($user->father_phone || $user->mother_phone || $user->guardian_phone)
                                <div class="md:col-span-2 p-3 bg-gray-900 rounded border border-gray-800 text-xs">
                                    <span class="text-amber-500 block font-semibold mb-1">Teléfonos de Contacto Familiar / Tutores:</span>
                                    <div class="flex flex-wrap gap-4 text-gray-300">
                                        @if($user->father_phone) <span><strong>Padre:</strong> {{ $user->father_phone }}</span> @endif
                                        @if($user->mother_phone) <span><strong>Madre:</strong> {{ $user->mother_phone }}</span> @endif
                                        @if($user->guardian_phone) <span><strong>Tutor/a:</strong> {{ $user->guardian_phone }}</span> @endif
                                    </div>
                                </div>
                            @endif
                            
                            @if($user->canViewIban())
                                <div class="md:col-span-2 mt-2 pt-2 border-t border-gray-800">
                                    <span class="text-amber-500 block">Cuenta Bancaria (IBAN):</span> 
                                    <span class="text-white font-mono">{{ $user->iban ?: 'No registrada' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @php
                        $age = $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->age : null;
                    @endphp
                    @if($age === null || $age < 18)
                        <div class="mt-6 p-4 rounded-md bg-amber-900/20 border border-amber-500/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div>
                                <h5 class="text-amber-500 font-semibold flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Justificante Parental Requerido
                                </h5>
                                <p class="text-sm text-gray-400 mt-1">Al ser menor de edad, necesitas entregar firmado el justificante parental para poder asistir a los eventos de la banda.</p>
                            </div>
                            <button type="button" onclick="document.getElementById('modal-parental-consent-musician').classList.remove('hidden')" class="shrink-0 rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                                Descargar Justificante
                            </button>
                        </div>
                    @endif
                </div>
                
                <!-- BLOQUE: TUS INSTRUMENTOS -->
                <div class="mb-8 bg-gray-950 p-6 rounded-lg border border-gray-800" x-data="{ openRegisterModal: false }">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 border-b border-gray-800 pb-3 gap-3">
                        <div>
                            <h4 class="text-lg font-semibold text-white">Tus Instrumentos</h4>
                            <p class="text-xs text-gray-400 mt-0.5">Instrumentos que tienes asignados en la banda o propios que utilizas.</p>
                        </div>
                        @if($allowMusicianInstruments ?? false)
                            <button type="button" @click="openRegisterModal = true" class="inline-flex items-center gap-1.5 rounded-md bg-amber-600 hover:bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition-colors cursor-pointer self-start sm:self-auto">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                Añadir mi Instrumento
                            </button>
                        @endif
                    </div>

                    @if($user->inventories->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($user->inventories as $inventory)
                                <div class="bg-gray-900 border {{ !$inventory->is_verified ? 'border-yellow-600/40 bg-yellow-950/10' : 'border-gray-800' }} rounded-lg p-4 shadow-sm relative">
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="font-bold text-amber-500">{{ $inventory->instrument->name ?? 'Desconocido' }}</h5>
                                        <div class="flex flex-col items-end gap-1">
                                            @if(!$inventory->is_verified)
                                                <span class="inline-flex items-center rounded-md bg-yellow-400/10 px-2 py-0.5 text-xs font-medium text-yellow-400 ring-1 ring-inset ring-yellow-400/30" title="Pendiente de aprobación por la directiva">
                                                    Pendiente Validación
                                                </span>
                                            @elseif($inventory->is_active)
                                                <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-0.5 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/20">Activo</span>
                                            @else
                                                <span class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-0.5 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">Inactivo</span>
                                            @endif
                                        </div>
                                    </div>
                                    <ul class="text-xs text-gray-400 space-y-1.5">
                                        @php
                                            $brand = $inventory->instrument_brand_id ? \App\Models\InstrumentBrand::find($inventory->instrument_brand_id) : null;
                                        @endphp
                                        <li><strong class="text-gray-300">Marca/Modelo:</strong> {{ $brand ? $brand->name : '-' }} {{ $inventory->model ? ' / ' . $inventory->model : '' }}</li>
                                        <li><strong class="text-gray-300">Nº Serie:</strong> {{ $inventory->serial_number ?: '-' }}</li>
                                        <li><strong class="text-gray-300">Propiedad:</strong> <span class="capitalize">{{ $inventory->propiedad ?: '-' }}</span></li>
                                        <li><strong class="text-gray-300">Partitura:</strong> {{ $inventory->tipo_partitura ?: '-' }}</li>
                                        @if($inventory->purchase_year)
                                            <li><strong class="text-gray-300">Año de Compra:</strong> {{ $inventory->purchase_year }}</li>
                                        @endif
                                        @if($inventory->invoice_path)
                                            <li class="pt-1">
                                                <a href="{{ asset('storage/' . $inventory->invoice_path) }}" target="_blank" class="inline-flex items-center gap-1 text-indigo-400 hover:text-indigo-300 underline font-medium">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                    Factura de compra
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                    @php
                                        $instrumentPhotos = \App\Models\InstrumentPhoto::where('inventory_id', $inventory->id)->get();
                                    @endphp
                                    @if($instrumentPhotos->count() > 0)
                                        <div class="mt-3 pt-3 border-t border-gray-800 flex gap-2 overflow-x-auto pb-1">
                                            @foreach($instrumentPhotos as $photo)
                                                <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank" class="flex-shrink-0" title="{{ $photo->description ?: 'Foto del instrumento' }}">
                                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" class="h-14 w-14 rounded object-cover border border-gray-700 hover:border-amber-500 transition-colors" alt="{{ $photo->description ?: 'Foto instrumento' }}">
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-md bg-gray-900/60 border border-gray-800 p-4 text-center">
                            <p class="text-sm text-gray-400">No tienes ningún instrumento asignado actualmente.</p>
                            @if($allowMusicianInstruments ?? false)
                                <p class="text-xs text-gray-500 mt-1">Puedes registrar tu propio instrumento usando el botón superior "Añadir mi Instrumento".</p>
                            @endif
                        </div>
                    @endif

                    <!-- Modal Registro de Instrumento por el Músico -->
                    @if($allowMusicianInstruments ?? false)
                    <div x-show="openRegisterModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                        <div class="flex min-h-screen items-center justify-center p-4 text-center">
                            <div class="fixed inset-0 bg-gray-950/80 backdrop-blur-sm transition-opacity" @click="openRegisterModal = false"></div>

                            <div class="relative w-full max-w-xl transform overflow-hidden rounded-2xl bg-gray-900 p-6 text-left shadow-2xl ring-1 ring-white/10 transition-all">
                                <div class="flex items-center justify-between border-b border-gray-800 pb-3 mb-4">
                                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                        <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                        Registrar mi Instrumento
                                    </h3>
                                    <button type="button" @click="openRegisterModal = false" class="text-gray-400 hover:text-white">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>

                                <form action="{{ route('musician.instruments.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="space-y-4 text-sm">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-300 mb-1">Tipo de Instrumento *</label>
                                                <select name="instrument_catalog_id" required class="w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-amber-500 text-sm">
                                                    <option value="">-- Seleccionar --</option>
                                                    @foreach($instrumentCatalogs ?? [] as $cat)
                                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-300 mb-1">Marca</label>
                                                <select name="instrument_brand_id" class="w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-amber-500 text-sm">
                                                    <option value="">-- Seleccionar Marca --</option>
                                                    @foreach($instrumentBrands ?? [] as $br)
                                                        <option value="{{ $br->id }}">{{ $br->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-300 mb-1">Modelo</label>
                                                <input type="text" name="model" placeholder="Ej: YAS-280, Custom..." class="w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-amber-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-300 mb-1">Nº de Serie (Recomendado)</label>
                                                <input type="text" name="serial_number" placeholder="Ej: 123456" class="w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-amber-500 text-sm font-mono uppercase">
                                            </div>
                                        </div>

                                        <!-- Recomendación visible para el número de serie -->
                                        <div class="p-3 bg-amber-950/40 border border-amber-500/40 rounded-lg flex items-start gap-2.5 text-xs text-amber-200/90 leading-relaxed">
                                            <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <div>
                                                <strong class="text-amber-300 font-semibold">Recomendación importante:</strong> Aunque el número de serie no es estrictamente obligatorio, <strong>te aconsejamos encarecidamente localizarlo e introducirlo</strong>. Disponer del número de serie registrado permite una identificación inequívoca de tu instrumento para un mayor control y facilitará su localización o reclamación en caso de extravío o robo.
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-300 mb-1">Propiedad *</label>
                                                <select name="propiedad" required class="w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-amber-500 text-sm">
                                                    <option value="musico" selected>Propio (mío)</option>
                                                    <option value="banda">De la banda</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-300 mb-1">Tipo de Partitura habitual</label>
                                                <input type="text" name="tipo_partitura" placeholder="Ej: 1º, 2º, 3º, Principal..." class="w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-amber-500 text-sm">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-300 mb-1">Año de Compra (Opcional)</label>
                                                <input type="number" name="purchase_year" min="1950" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}" class="w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-amber-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-300 mb-1">Factura de Compra (PDF o Imagen)</label>
                                                <input type="file" name="invoice" accept=".pdf,image/*" class="w-full text-xs text-gray-400 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-gray-800 file:text-amber-400">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-300 mb-1">Observaciones</label>
                                            <textarea name="notes" rows="2" placeholder="Cualquier detalle relevante..." class="w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-amber-500 text-sm"></textarea>
                                        </div>

                                        <!-- Captcha Antirrobot / Confirmación de Seguridad -->
                                        <div class="p-3.5 bg-gray-950 rounded-lg border border-gray-800">
                                            <label for="modal_instrument_captcha" class="block text-xs font-semibold text-amber-400 mb-1.5">
                                                Control de seguridad: ¿Cuánto es {{ $captchaNum1 ?? rand(1,9) }} + {{ $captchaNum2 ?? rand(1,9) }}? *
                                            </label>
                                            <div class="flex items-center gap-3">
                                                <input type="number" id="modal_instrument_captcha" name="captcha" required placeholder="Introduce el resultado" class="w-48 rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-amber-500 text-sm">
                                                <span class="text-[11px] text-gray-400">Introduce la suma para confirmar el alta y evitar registros por error.</span>
                                            </div>
                                        </div>

                                        <div class="p-3 bg-amber-500/10 border border-amber-500/20 rounded-lg text-xs text-amber-200/90 leading-relaxed">
                                            Al guardar, el instrumento quedará registrado y <strong>pendiente de validación</strong> por parte de los administradores de la banda para el control de inventario.
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end gap-3 border-t border-gray-800 pt-4">
                                        <button type="button" @click="openRegisterModal = false" class="rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold text-gray-300 hover:bg-gray-700">Cancelar</button>
                                        <button type="submit" class="rounded-md bg-amber-600 px-4 py-2 text-xs font-semibold text-white hover:bg-amber-500 shadow-sm">Guardar Instrumento</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 border-b border-gray-800 pb-2">
                        <h4 class="text-lg font-semibold text-white">Tus Partituras Disponibles</h4>
                        @if($availableParts->count() > 0)
                        <div class="mt-2 sm:mt-0 w-full sm:w-64">
                            <input type="text" id="sheet-music-search" placeholder="Buscar partitura..." class="block w-full rounded-md border-0 bg-gray-900 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @endif
                    </div>
                    
                    @if($availableParts->count() > 0)
                        <div id="sheet-music-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($availableParts as $part)
                                <div class="bg-gray-950 border border-gray-800 rounded-lg p-5 shadow-sm hover:border-gray-700 transition-colors">
                                    <h5 class="font-bold text-lg text-amber-500">{{ $part->title }}</h5>
                                    <p class="text-sm text-gray-400 mb-1">{{ $part->composer ?? 'Compositor desconocido' }}</p>
                                    @if($part->work_type)
                                        <p class="text-xs text-gray-500 mb-2 italic">{{ $part->work_type }}</p>
                                    @else
                                        <div class="mb-2"></div>
                                    @endif
                                    <p class="text-xs text-gray-500 mb-6 font-mono">Tipo: {{ $part->tipo_partitura }}</p>
                                    
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <a href="{{ route('musician.sheet-music.view', $part->id) }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
                                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Ver en Atril
                                        </a>
                                        <a href="{{ route('musician.sheet-music.download', $part->id) }}" class="inline-flex items-center justify-center rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 transition-colors">
                                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            Descargar
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No hay partituras asignadas a tus instrumentos en este momento.</p>
                    @endif
            </div>
        </div>

        @if(isset($missedAttendances))
        <div class="bg-gray-900 border border-gray-800 shadow-sm sm:rounded-lg mt-8">
            <div class="p-6 text-gray-300">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 border-b border-gray-800 pb-2">
                    <h4 class="text-lg font-semibold text-white">Historial de Faltas de Asistencia</h4>
                    <form action="{{ route('dashboard') }}" method="GET" class="mt-3 sm:mt-0 flex items-center gap-2">
                        <input type="date" name="start_date" value="{{ request('start_date', now()->subYear()->toDateString()) }}" class="block w-full sm:w-auto rounded-md border-0 bg-gray-900 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" style="color-scheme: dark;">
                        <span class="text-gray-500">-</span>
                        <input type="date" name="end_date" value="{{ request('end_date', now()->toDateString()) }}" class="block w-full sm:w-auto rounded-md border-0 bg-gray-900 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" style="color-scheme: dark;">
                        <button type="submit" class="inline-flex items-center rounded-md bg-gray-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-600">Filtrar</button>
                    </form>
                </div>
                
                @if($missedAttendances->count() > 0)
                <div class="overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-900">
                            <tr>
                                <th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold text-white sm:pl-6">Fecha</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-white">Evento</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-white">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 bg-gray-950">
                            @foreach($missedAttendances as $attendance)
                                <tr>
                                    <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                                        {{ \Carbon\Carbon::parse($attendance->event->event_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-400">
                                        {{ $attendance->event->name }} <span class="text-xs text-gray-500 capitalize ml-2">({{ $attendance->event->type }})</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-400">
                                        @if($attendance->status === 'absent')
                                            <span class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/30">Falta Injustificada</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-yellow-400/10 px-2 py-1 text-xs font-medium text-yellow-400 ring-1 ring-inset ring-yellow-400/30">Falta Justificada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-gray-500 italic mt-4">No tienes faltas registradas en este periodo.</p>
                @endif
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('sheet-music-search');
            const grid = document.getElementById('sheet-music-grid');
            
            if (searchInput && grid) {
                const cards = grid.querySelectorAll('div.bg-gray-950');
                
                searchInput.addEventListener('input', function(e) {
                    const term = e.target.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    
                    cards.forEach(card => {
                        const text = card.textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                        if (text.includes(term)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</x-admin-layout>

<!-- Modal Parental Consent para Músicos -->
<div id="modal-parental-consent-musician" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <form action="{{ route('musician.parental-consent.download') }}" method="GET" target="_blank">
                    <div>
                        <h3 class="text-base font-semibold leading-6 text-white" id="modal-title">Generar Justificante Parental</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-400">Selecciona el evento para autorellenar los datos. Si no seleccionas ninguno, se dejará un espacio en blanco para que lo rellenes a mano.</p>
                            <div class="mt-4">
                                <label for="event_id" class="block text-sm font-medium leading-6 text-white">Evento (Opcional)</label>
                                <select id="event_id" name="event_id" class="mt-2 block w-full rounded-md border-0 bg-gray-900 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                                    <option value="">-- Dejar en blanco --</option>
                                    @php
                                        $events = \App\Models\Event::where('event_date', '>=', now()->toDateString())->orderBy('event_date', 'asc')->get();
                                    @endphp
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->name }} ({{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                        <button type="submit" onclick="document.getElementById('modal-parental-consent-musician').classList.add('hidden')" class="inline-flex w-full justify-center rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 sm:col-start-2">Generar PDF</button>
                        <button type="button" onclick="document.getElementById('modal-parental-consent-musician').classList.add('hidden')" class="mt-3 inline-flex w-full justify-center rounded-md bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-300 shadow-sm ring-1 ring-inset ring-gray-600 hover:bg-gray-600 sm:col-start-1 sm:mt-0">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
