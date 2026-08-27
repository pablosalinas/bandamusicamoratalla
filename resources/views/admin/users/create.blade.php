<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Nuevo Músico / Usuario</h2>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.users.index') }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 max-w-3xl">
        <form action="{{ route('admin.users.store') }}" method="POST" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            @csrf
            
            <div class="px-4 py-6 sm:p-8">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    
                    <div class="sm:col-span-3">
                        <label for="name" class="block text-sm font-medium leading-6 text-white">Nombre</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="last_name" class="block text-sm font-medium leading-6 text-white">Apellidos</label>
                        <div class="mt-2">
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('last_name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-4">
                        <label for="email" class="block text-sm font-medium leading-6 text-white">Email (Usuario)</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('email') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="password" class="block text-sm font-medium leading-6 text-white">Contraseña</label>
                        <div class="mt-2">
                            <input type="password" name="password" id="password" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('password') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="birth_date" class="block text-sm font-medium leading-6 text-white">Fecha de Nacimiento</label>
                        <div class="mt-2">
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" style="color-scheme: dark;">
                        </div>
                        @error('birth_date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="role" class="block text-sm font-medium leading-6 text-white">Rol del Usuario</label>
                        <div class="mt-2">
                            <select id="role" name="role" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6 [&_*]:text-black">
                                <option value="musician" {{ old('role') == 'musician' ? 'selected' : '' }}>Músico / Componente / Externo</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                        </div>
                        @error('role') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3 flex items-center mt-8">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                        <label for="is_active" class="ml-3 block text-sm font-medium leading-6 text-white">Usuario Activo en la Banda</label>
                    </div>

                    <div class="sm:col-span-6 border-t border-gray-800 pt-6 mt-2"
                         x-data="{
                            availableInstruments: {{ $instruments->map(fn($i) => ['id' => $i->id, 'name' => $i->name])->toJson() }},
                            selectedInstruments: [
                                @foreach($instruments as $instrument)
                                    @php
                                        $isChecked = in_array($instrument->id, old('instruments', []));
                                        $serialValue = old('serial_numbers.'.$instrument->id, '');
                                    @endphp
                                    @if($isChecked)
                                    { id: {{ $instrument->id }}, name: '{{ $instrument->name }}', serial: '{{ $serialValue }}' },
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
                                        this.selectedInstruments.push({ id: inst.id, name: inst.name, serial: '' });
                                    }
                                }
                                this.selectedToAdd = '';
                            },
                            removeInstrument(id) {
                                this.selectedInstruments = this.selectedInstruments.filter(i => i.id !== id);
                            }
                         }">
                         
                        <label class="block text-base font-semibold leading-6 text-white mb-4">Instrumentos Asignados</label>
                        <p class="text-sm text-gray-400 mb-4">Selecciona los instrumentos que toca este músico e indica su número de serie si dispone del instrumento físico de la banda.</p>
                        
                        <div class="flex items-center gap-4 mb-6">
                            <select x-model="selectedToAdd" class="block w-full sm:w-1/2 rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6 [&_*]:text-black">
                                <option value="">-- Añadir instrumento --</option>
                                <template x-for="inst in availableInstruments" :key="inst.id">
                                    <option :value="inst.id" x-text="inst.name" x-show="!selectedInstruments.find(s => s.id === inst.id)"></option>
                                </template>
                            </select>
                            <button type="button" @click="addInstrument" class="rounded-md bg-amber-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                                Añadir
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <template x-for="(inst, index) in selectedInstruments" :key="inst.id">
                                <div class="flex items-center justify-between p-3 bg-gray-950 rounded-lg border border-gray-800">
                                    <div class="flex-1 mr-4">
                                        <p class="text-sm font-medium text-gray-200 mb-2" x-text="inst.name"></p>
                                        <input type="hidden" name="instruments[]" :value="inst.id">
                                        <input type="text" :name="'serial_numbers[' + inst.id + ']'" x-model="inst.serial" placeholder="Nº Serie (Opcional)" class="block w-full rounded-md border-0 bg-gray-800 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs">
                                    </div>
                                    <button type="button" @click="removeInstrument(inst.id)" class="text-red-500 hover:text-red-400 p-1">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <p x-show="selectedInstruments.length === 0" class="text-sm text-gray-500 italic col-span-full">No hay instrumentos asignados a este músico.</p>
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-800 px-4 py-4 sm:px-8">
                <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold leading-6 text-white">Cancelar</a>
                <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    Guardar Usuario
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
