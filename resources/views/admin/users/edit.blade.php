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
                            <select id="role" name="role" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                                <option value="musician" {{ old('role', $user->role) == 'musician' ? 'selected' : '' }}>Músico / Componente / Externo</option>
                                <option value="treasurer" {{ old('role', $user->role) == 'treasurer' ? 'selected' : '' }}>Tesorero</option>
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

                    <div class="sm:col-span-6 border-t border-gray-800 pt-6 mt-2" 
                         x-data="{
                            availableInstruments: {!! $instruments->map(fn($i) => ['id' => $i->id, 'name' => $i->name])->toJson() !!},
                            selectedInstruments: [
                                @foreach($instruments as $instrument)
                                    @php
                                        $isChecked = in_array($instrument->id, old('instruments', $userInstruments ?? []));
                                        $serialValue = old('serial_numbers.'.$instrument->id, isset($userInstrumentsData) && $userInstrumentsData->has($instrument->id) ? $userInstrumentsData[$instrument->id]->pivot->serial_number : '');
                                        $tipoValue = old('tipo_partitura.'.$instrument->id, isset($userInstrumentsData) && $userInstrumentsData->has($instrument->id) ? $userInstrumentsData[$instrument->id]->pivot->tipo_partitura : '');
                                        $propiedadValue = old('propiedad.'.$instrument->id, isset($userInstrumentsData) && $userInstrumentsData->has($instrument->id) ? $userInstrumentsData[$instrument->id]->pivot->propiedad : '');
                                        $isActiveValue = old('is_active_instrument.'.$instrument->id, isset($userInstrumentsData) && $userInstrumentsData->has($instrument->id) ? $userInstrumentsData[$instrument->id]->pivot->is_active : true);
                                    @endphp
                                    @if($isChecked)
                                    { id: {{ $instrument->id }}, name: '{{ $instrument->name }}', serial: '{{ $serialValue }}', tipo: '{{ $tipoValue }}', propiedad: '{{ $propiedadValue }}', active: {{ $isActiveValue ? 'true' : 'false' }} },
                                    @endif
                                @endforeach
                            ],
                            selectedToAdd: '',
                            addInstrument() {
                                if (!this.selectedToAdd) return;
                                const id = parseInt(this.selectedToAdd);
                                if (!this.selectedInstruments.find(i => i.id === id)) {
                                    const inst = this.availableInstruments.find(i => i.id === id);
                                    if (inst) {
                                        this.selectedInstruments.push({ id: inst.id, name: inst.name, serial: '', tipo: '', propiedad: '', active: true });
                                    }
                                }
                                this.selectedToAdd = '';
                            },
                            removeInstrument(id) {
                                this.selectedInstruments = this.selectedInstruments.filter(i => i.id !== id);
                            }
                         }">
                         
                        <label class="block text-base font-semibold leading-6 text-white mb-4">Instrumentos Asignados</label>
                        <p class="text-sm text-gray-400 mb-4">Selecciona los instrumentos que toca este músico e indica sus detalles adicionales.</p>
                        
                        <div class="flex items-center gap-4 mb-6">
                            <select x-model="selectedToAdd" class="block w-full sm:w-1/2 rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                                <option value="">-- Añadir instrumento --</option>
                                <template x-for="inst in availableInstruments" :key="inst.id">
                                    <option :value="inst.id" x-text="inst.name" x-show="!selectedInstruments.find(s => s.id === inst.id)"></option>
                                </template>
                            </select>
                            <button type="button" @click="addInstrument" class="rounded-md bg-amber-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                                Añadir
                            </button>
                        </div>

                        <div class="flex flex-col gap-4">
                            <template x-for="(inst, index) in selectedInstruments" :key="inst.id">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-gray-950 rounded-lg border border-gray-800 gap-4 relative">
                                    <button type="button" @click="removeInstrument(inst.id)" class="absolute top-2 right-2 text-red-500 hover:text-red-400 p-1">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                    
                                    <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4 sm:mt-0">
                                        <div class="col-span-full">
                                            <p class="text-lg font-bold text-amber-500 mb-2" x-text="inst.name"></p>
                                            <input type="hidden" name="instruments[]" :value="inst.id">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-xs font-medium text-gray-400">Tipo de Partitura</label>
                                            <select :name="'tipo_partitura[' + inst.id + ']'" x-model="inst.tipo" class="mt-1 block w-full rounded-md border-0 bg-gray-800 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs">
                                                <option value="">Selecciona...</option>
                                                <option value="1º">1º</option>
                                                <option value="2º">2º</option>
                                                <option value="3º">3º</option>
                                                <option value="PRINCIPAL">PRINCIPAL</option>
                                                <option value="TODOS">TODOS</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-400">Propiedad</label>
                                            <select :name="'propiedad[' + inst.id + ']'" x-model="inst.propiedad" class="mt-1 block w-full rounded-md border-0 bg-gray-800 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs">
                                                <option value="">Selecciona...</option>
                                                <option value="Propio">Propio</option>
                                                <option value="De la banda">De la banda</option>
                                                <option value="Prestado">Prestado</option>
                                                <option value="Reliquia">Reliquia</option>
                                                <option value="Alquilado">Alquilado</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-xs font-medium text-gray-400">Nº Serie (Opcional)</label>
                                            <input type="text" :name="'serial_numbers[' + inst.id + ']'" x-model="inst.serial" class="mt-1 block w-full rounded-md border-0 bg-gray-800 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs">
                                        </div>
                                        
                                        <div class="col-span-full">
                                            <label class="block text-xs font-medium text-gray-400">Subir Fotos (Múltiples)</label>
                                            <input type="file" :name="'photos[' + inst.id + '][]'" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700">
                                        </div>
                                        
                                        <div class="col-span-full flex items-center mt-2">
                                            <input type="checkbox" :name="'is_active_instrument[' + inst.id + ']'" x-model="inst.active" value="1" class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                                            <label class="ml-2 block text-xs font-medium text-gray-300">Instrumento Activo</label>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <p x-show="selectedInstruments.length === 0" class="text-sm text-gray-500 italic">No hay instrumentos asignados a este músico.</p>
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
