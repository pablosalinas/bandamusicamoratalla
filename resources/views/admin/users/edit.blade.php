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
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
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

                    <div class="sm:col-span-4">
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
                            <select id="role" name="role" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6 [&_*]:text-black">
                                <option value="musician" {{ old('role', $user->role) == 'musician' ? 'selected' : '' }}>Músico / Componente / Externo</option>
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
                            <input list="user_leave_reasons" name="leave_reason" id="leave_reason" value="{{ old('leave_reason', $user->leave_reason) }}" placeholder="Selecciona o escribe un motivo..." class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
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

                    <div class="sm:col-span-6 border-t border-gray-800 pt-6 mt-2">
                        <label class="block text-base font-semibold leading-6 text-white mb-4">Instrumentos Asignados</label>
                        <p class="text-sm text-gray-400 mb-4">Selecciona los instrumentos que toca este músico e indica su número de serie si dispone del instrumento físico de la banda.</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @php 
                                $userInstruments = $user->instruments->pluck('id')->toArray(); 
                                $userInstrumentsData = $user->instruments->keyBy('id');
                            @endphp
                            @foreach($instruments as $instrument)
                                @php
                                    $isChecked = in_array($instrument->id, old('instruments', $userInstruments));
                                    $serialValue = old('serial_numbers.'.$instrument->id, $userInstrumentsData->has($instrument->id) ? $userInstrumentsData[$instrument->id]->pivot->serial_number : '');
                                @endphp
                                <div class="relative flex flex-col p-4 bg-gray-950 rounded-lg border border-gray-800" x-data="{ checked: {{ $isChecked ? 'true' : 'false' }} }">
                                    <div class="flex items-start mb-3">
                                        <div class="flex h-6 items-center">
                                            <input id="instrument_{{ $instrument->id }}" name="instruments[]" type="checkbox" value="{{ $instrument->id }}" x-model="checked" class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                                        </div>
                                        <div class="ml-3 text-sm leading-6">
                                            <label for="instrument_{{ $instrument->id }}" class="font-medium text-gray-300">{{ $instrument->name }}</label>
                                        </div>
                                    </div>
                                    <div x-show="checked" class="mt-1">
                                        <label for="serial_{{ $instrument->id }}" class="sr-only">Número de Serie</label>
                                        <input type="text" name="serial_numbers[{{ $instrument->id }}]" id="serial_{{ $instrument->id }}" value="{{ $serialValue }}" placeholder="Nº Serie (Opcional)" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs sm:leading-6">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="sm:col-span-6 border-t border-gray-800 pt-6 mt-2">
                        <label class="block text-base font-semibold leading-6 text-white mb-4">Historial de Faltas de Asistencia</label>
                        <p class="text-sm text-gray-400 mb-4">Registro de las faltas de asistencia (justificadas e injustificadas) del músico a los ensayos y actos.</p>
                        
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
                            <p class="text-sm text-gray-400 italic bg-gray-950 p-4 rounded-lg border border-gray-800">Este músico no tiene faltas de asistencia registradas.</p>
                        @endif
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
