<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Editar Usuario: {{ $user->name }} {{ $user->last_name }}</h2>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.users.index') }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 max-w-3xl">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            @csrf
            @method('PUT')
            
            <div class="px-4 py-6 sm:p-8">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    
                    <div class="sm:col-span-3">
                        <label for="name" class="block text-sm font-medium leading-6 text-white">Nombre</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="last_name" class="block text-sm font-medium leading-6 text-white">Apellidos</label>
                        <div class="mt-2">
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('last_name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="nif" class="block text-sm font-medium leading-6 text-white">NIF / NIE (Opcional)</label>
                        <div class="mt-2">
                            <input type="text" name="nif" id="nif" value="{{ old('nif', $user->nif) }}" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" placeholder="12345678A">
                        </div>
                        @error('nif') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="email" class="block text-sm font-medium leading-6 text-white">Email (Usuario)</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('email') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="password" class="block text-sm font-medium leading-6 text-white">Contraseña (Dejar en blanco para no cambiar)</label>
                        <div class="mt-2">
                            <input type="password" name="password" id="password" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('password') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="birth_date" class="block text-sm font-medium leading-6 text-white">Fecha de Nacimiento</label>
                        <div class="mt-2">
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" style="color-scheme: dark;">
                        </div>
                        @error('birth_date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="role" class="block text-sm font-medium leading-6 text-white">Rol del Usuario</label>
                        <div class="mt-2">
                            <select id="role" name="role" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                                <option value="musician" {{ old('role', $user->role) == 'musician' ? 'selected' : '' }}>Músico / Componente / Externo</option>
                                <option value="treasurer" {{ old('role', $user->role) == 'treasurer' ? 'selected' : '' }}>Tesorero</option>
                                <option value="director" {{ old('role', $user->role) == 'director' ? 'selected' : '' }}>Director</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                        </div>
                        @error('role') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3 flex flex-col justify-center mt-4">
                        <div class="flex items-center">
                            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                            <label for="is_active" class="ml-3 block text-sm font-medium leading-6 text-white">Usuario Activo en la Banda</label>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="leave_reason" class="block text-sm font-medium leading-6 text-white">Motivo de Baja (Si está inactivo)</label>
                        <div class="mt-2">
                            <input list="user_leave_reasons" name="leave_reason" id="leave_reason" value="{{ old('leave_reason', $user->leave_reason) }}" placeholder="Selecciona o escribe un motivo..." class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" style="color-scheme: dark;">
                            <datalist id="user_leave_reasons">
                                <option value="Falta de tiempo">
                                <option value="Motivos de salud">
                                <option value="Motivos personales">
                                <option value="Cambio de residencia">
                                <option value="Pérdida de interés">
                                <option value="Expulsión">
                            </datalist>
                        </div>
                        @error('leave_reason') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6 grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 border-t border-gray-800 pt-6">
                        @if($user->photo_path)
                        <div class="md:col-span-2 flex items-center gap-4 mb-4">
                            <img src="{{ $user->photo_url }}" alt="Foto de {{ $user->name }}" class="h-16 w-16 rounded-full object-cover border-2 border-amber-500">
                            <div>
                                <h3 class="text-sm font-medium text-white">Foto de Perfil</h3>
                                <p class="text-xs text-gray-400">El usuario ha subido su fotografía.</p>
                            </div>
                        </div>
                        @else
                        <div class="md:col-span-2 flex items-center gap-4 mb-4">
                            <div class="h-16 w-16 rounded-full bg-gray-800 flex items-center justify-center border-2 border-gray-700 text-gray-500">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-white">Sin foto de perfil</h3>
                                <p class="text-xs text-gray-400">El usuario aún no ha subido una fotografía.</p>
                            </div>
                        </div>
                        @endif

                        <div>
                            <label for="address" class="block text-sm font-medium leading-6 text-white">Dirección Postal</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                            @error('address') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium leading-6 text-white">Código Postal</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                            @error('postal_code') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium leading-6 text-white">Población</label>
                            <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                            @error('city') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="province" class="block text-sm font-medium leading-6 text-white">Provincia</label>
                            <input type="text" name="province" id="province" value="{{ old('province', $user->province) }}" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                            @error('province') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium leading-6 text-white">Teléfono</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                            @error('phone') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        @if(auth()->user()->canViewIban())
                        <div class="md:col-span-2 mt-4 p-4 bg-gray-950 rounded-lg border border-amber-500/30 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-2 opacity-10">
                                <svg class="w-24 h-24 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
                            </div>
                            <label for="iban" class="block text-sm font-medium leading-6 text-amber-500 relative z-10">Cuenta Bancaria (IBAN)</label>
                            <input type="text" name="iban" id="iban" value="{{ old('iban', $user->iban) }}" class="mt-2 block w-full rounded-md border-0 bg-gray-900 py-1.5 text-amber-100 shadow-sm ring-1 ring-inset ring-amber-500/50 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6 relative z-10" placeholder="ES00 0000 0000 0000 0000 0000">
                            <p class="text-xs text-gray-400 mt-2 relative z-10">Este campo se guarda <strong>encriptado</strong> en la base de datos y sólo es visible para el rol de Tesorero.</p>
                            @error('iban') <p class="mt-2 text-sm text-red-400 relative z-10">{{ $message }}</p> @enderror
                        </div>
                        @endif
                    </div>

                    <div class="sm:col-span-6 border-t border-gray-800 pt-6 mt-2">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <label class="block text-base font-semibold leading-6 text-white">Instrumentos Asignados</label>
                                <p class="text-sm text-gray-400">Los instrumentos ahora se gestionan desde el Inventario Central.</p>
                            </div>
                            <a href="{{ route('admin.inventory.index', ['musician_id' => $user->id]) }}" class="rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                                Gestionar en Inventario
                            </a>
                        </div>
                        
                        <div class="flex flex-col gap-4">
                            @forelse($userInstruments as $inst)
                                <div class="flex flex-col sm:flex-row items-center justify-between p-4 bg-gray-950 rounded-lg border {{ $inst->is_active ? 'border-gray-800' : 'border-red-900/50 opacity-70' }}">
                                    <div class="flex-1">
                                        <p class="text-lg font-bold text-amber-500">
                                            {{ $inst->instrument->name ?? 'Desconocido' }}
                                            @if(!$inst->is_active) <span class="text-xs text-red-500 ml-2">(Dado de baja)</span> @endif
                                        </p>
                                        <p class="text-sm text-gray-400 mt-1">
                                            Marca/Modelo: {{ $inst->brand->name ?? '-' }} {{ $inst->model ? ' / ' . $inst->model : '' }} | 
                                            Nº Serie: {{ $inst->serial_number ?: 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="mt-4 sm:mt-0 flex gap-2">
                                        <a href="{{ route('admin.inventory.show', $inst) }}" class="text-xs bg-gray-800 hover:bg-gray-700 text-white px-3 py-1.5 rounded transition-colors">
                                            Ver Ficha y Trazabilidad
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 italic">No hay instrumentos asignados a este músico.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="sm:col-span-6 border-t border-gray-800 pt-6 mt-2">
                        <div class="sm:flex sm:items-center sm:justify-between mb-4">
                            <div>
                                <label class="block text-base font-semibold leading-6 text-white">Historial de Asistencia</label>
                                <p class="text-sm text-gray-400">Registro de asistencia del músico a los ensayos y actos.</p>
                            </div>
                            <div class="mt-3 sm:ml-4 sm:mt-0">
                                <span class="isolate inline-flex rounded-md shadow-sm">
                                    <button type="button" onclick="window.location.href='{{ route('admin.users.edit', ['user' => $user->id, 'attendance_filter' => 'absent']) }}'" class="relative inline-flex items-center rounded-l-md px-3 py-2 text-sm font-semibold {{ $filter === 'absent' ? 'bg-amber-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }} ring-1 ring-inset ring-gray-700 focus:z-10">Faltas Injustificadas</button>
                                    <button type="button" onclick="window.location.href='{{ route('admin.users.edit', ['user' => $user->id, 'attendance_filter' => 'excused']) }}'" class="relative -ml-px inline-flex items-center px-3 py-2 text-sm font-semibold {{ $filter === 'excused' ? 'bg-amber-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }} ring-1 ring-inset ring-gray-700 focus:z-10">Faltas Justificadas</button>
                                    <button type="button" onclick="window.location.href='{{ route('admin.users.edit', ['user' => $user->id, 'attendance_filter' => 'present']) }}'" class="relative -ml-px inline-flex items-center rounded-r-md px-3 py-2 text-sm font-semibold {{ $filter === 'present' ? 'bg-amber-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }} ring-1 ring-inset ring-gray-700 focus:z-10">Asistencias</button>
                                </span>
                            </div>
                        </div>
                        
                        @if($attendances->count() > 0)
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
                                        @foreach($attendances as $attendance)
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
                                                    @elseif($attendance->status === 'excused')
                                                        <span class="inline-flex items-center rounded-md bg-yellow-400/10 px-2 py-1 text-xs font-medium text-yellow-400 ring-1 ring-inset ring-yellow-400/30">Falta Justificada</span>
                                                    @else
                                                        <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/30">Asiste</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic bg-gray-950 p-4 rounded-lg border border-gray-800">No hay registros para este filtro.</p>
                        @endif
                    </div>

                    <div class="sm:col-span-6 border-t border-gray-800 pt-6 mt-2">
                        <div class="sm:flex sm:items-center sm:justify-between mb-4">
                            <div>
                                <label class="block text-base font-semibold leading-6 text-white">Justificante Parental</label>
                                <p class="text-sm text-gray-400">Genera un justificante parental autorellenado para este músico.</p>
                            </div>
                            <div class="mt-3 sm:ml-4 sm:mt-0">
                                <button type="button" onclick="document.getElementById('modal-parental-consent').classList.remove('hidden')" class="rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                                    Generar Justificante
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-800 px-4 py-4 sm:px-8">
                <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold leading-6 text-white">Cancelar</a>
                <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

    <!-- Modal Parental Consent -->
    <div id="modal-parental-consent" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <form action="{{ route('admin.users.parental-consent', $user) }}" method="GET" target="_blank">
                        <div>
                            <h3 class="text-base font-semibold leading-6 text-white" id="modal-title">Generar Justificante Parental</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-400">Selecciona el evento para autorellenar los datos. Si no seleccionas ninguno, se dejará un espacio en blanco.</p>
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
                            <button type="submit" onclick="document.getElementById('modal-parental-consent').classList.add('hidden')" class="inline-flex w-full justify-center rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 sm:col-start-2">Generar PDF</button>
                            <button type="button" onclick="document.getElementById('modal-parental-consent').classList.add('hidden')" class="mt-3 inline-flex w-full justify-center rounded-md bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-300 shadow-sm ring-1 ring-inset ring-gray-600 hover:bg-gray-600 sm:col-start-1 sm:mt-0">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
