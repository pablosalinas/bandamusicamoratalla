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
                            <div><span class="text-gray-500 block">Nombre Completo:</span> <span class="text-white">{{ $user->name }} {{ $user->last_name }}</span></div>
                            <div><span class="text-gray-500 block">Email:</span> <span class="text-white">{{ $user->email }}</span></div>
                            <div><span class="text-gray-500 block">Teléfono:</span> <span class="text-white">{{ $user->phone ?: '-' }}</span></div>
                            <div><span class="text-gray-500 block">DNI / NIF:</span> <span class="text-white">{{ $user->dni ?: '-' }}</span></div>
                            <div><span class="text-gray-500 block">Dirección:</span> <span class="text-white">{{ $user->address ?: '-' }}, {{ $user->postal_code ?: '-' }} {{ $user->city ?: '-' }} ({{ $user->province ?: '-' }})</span></div>
                            <div><span class="text-gray-500 block">Fecha de Nacimiento:</span> <span class="text-white">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : '-' }}</span></div>
                            
                            @if($user->canViewIban())
                                <div class="md:col-span-2 mt-2 pt-2 border-t border-gray-800">
                                    <span class="text-amber-500 block">Cuenta Bancaria (IBAN):</span> 
                                    <span class="text-white">{{ $user->iban ?: 'No registrada' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($user->instruments->count() > 0)
                    <div class="mb-8 bg-gray-950 p-6 rounded-lg border border-gray-800">
                        <h4 class="text-lg font-semibold mb-4 border-b border-gray-800 pb-2 text-white">Tus Instrumentos</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($user->instruments as $instrument)
                                <div class="bg-gray-900 border border-gray-800 rounded-lg p-4 shadow-sm">
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="font-bold text-amber-500">{{ $instrument->name }}</h5>
                                        @if($instrument->pivot->is_active)
                                            <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/20">Activo</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">Inactivo</span>
                                        @endif
                                    </div>
                                    <ul class="text-xs text-gray-400 space-y-1">
                                        @php
                                            $brand = $instrument->pivot->instrument_brand_id ? \App\Models\InstrumentBrand::find($instrument->pivot->instrument_brand_id) : null;
                                        @endphp
                                        <li><strong class="text-gray-300">Marca/Modelo:</strong> {{ $brand ? $brand->name : '-' }} {{ $instrument->pivot->modelo ? ' / ' . $instrument->pivot->modelo : '' }}</li>
                                        <li><strong class="text-gray-300">Nº Serie:</strong> {{ $instrument->pivot->serial_number ?: '-' }}</li>
                                        <li><strong class="text-gray-300">Propiedad:</strong> <span class="capitalize">{{ $instrument->pivot->propiedad ?: '-' }}</span></li>
                                        <li><strong class="text-gray-300">Partitura:</strong> {{ $instrument->pivot->tipo_partitura ?: '-' }}</li>
                                    </ul>
                                    @php
                                        $instrumentPhotos = \App\Models\InstrumentPhoto::where('musician_instrument_id', $instrument->pivot->id)->get();
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
                    </div>
                    
                    <h4 class="text-lg font-semibold mb-4 border-b border-gray-800 pb-2 text-white">Tus Partituras Disponibles</h4>
                    
                    @if($availableParts->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                                    
                                    <a href="{{ route('musician.sheet-music.download', $part->id) }}" class="inline-flex items-center rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 transition-colors">
                                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                        Descargar Archivo
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No hay partituras asignadas a tus instrumentos en este momento.</p>
                    @endif
                @else
                    <div class="rounded-md bg-yellow-900/30 border border-yellow-600/30 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-200">
                                    No tienes ningún instrumento asignado. Contacta con un administrador para que asigne tu instrumento y puedas ver tus partituras.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if(isset($missedAttendances) && $missedAttendances->count() > 0)
        <div class="bg-gray-900 border border-gray-800 shadow-sm sm:rounded-lg mt-8">
            <div class="p-6 text-gray-300">
                <h4 class="text-lg font-semibold mb-4 border-b border-gray-800 pb-2 text-white">Historial de Faltas de Asistencia</h4>
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
            </div>
        </div>
        @endif
    </div>
</x-admin-layout>
