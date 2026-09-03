<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Nuevo Instrumento en Inventario</h2>
    </x-slot>

    <div class="mt-8 bg-gray-900 overflow-hidden shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
        <div class="p-6">
            <form action="{{ route('admin.inventory.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Catálogo de Instrumento *</label>
                        <select name="instrument_catalog_id" required class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                            <option value="">-- Seleccionar --</option>
                            @foreach($catalogs as $catalog)
                                <option value="{{ $catalog->id }}" {{ old('instrument_catalog_id') == $catalog->id ? 'selected' : '' }}>{{ $catalog->name }}</option>
                            @endforeach
                        </select>
                        @error('instrument_catalog_id') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Marca</label>
                        <select name="instrument_brand_id" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                            <option value="">-- Seleccionar --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('instrument_brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('instrument_brand_id') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Modelo</label>
                        <input type="text" name="model" value="{{ old('model') }}" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                        @error('model') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Nº Serie</label>
                        <input type="text" name="serial_number" value="{{ old('serial_number') }}" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                        @error('serial_number') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Propiedad *</label>
                        <select name="propiedad" required class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                            <option value="banda" {{ old('propiedad') == 'banda' ? 'selected' : '' }}>De la banda</option>
                            <option value="musico" {{ old('propiedad') == 'musico' ? 'selected' : '' }}>Propio del músico</option>
                        </select>
                        @error('propiedad') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Estado Físico *</label>
                        <select name="status" required class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                            <option value="good" {{ old('status', 'good') == 'good' ? 'selected' : '' }}>Bueno</option>
                            <option value="repair" {{ old('status') == 'repair' ? 'selected' : '' }}>En Reparación</option>
                            <option value="bad" {{ old('status') == 'bad' ? 'selected' : '' }}>Malo / Baja</option>
                        </select>
                        @error('status') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Tipo Partitura</label>
                        <input type="text" name="tipo_partitura" value="{{ old('tipo_partitura') }}" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                        @error('tipo_partitura') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Asignación Inicial</label>
                        <select name="user_id" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                            <option value="">-- Sin asignar (En Stock) --</option>
                            @foreach($musicians as $mus)
                                @if($mus->is_active)
                                    <option value="{{ $mus->id }}" {{ old('user_id') == $mus->id ? 'selected' : '' }}>{{ $mus->name }} {{ $mus->last_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Si seleccionas un músico, se creará un movimiento automático de asignación.</p>
                        @error('user_id') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium leading-6 text-white">Notas / Observaciones</label>
                        <textarea name="notes" rows="3" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">{{ old('notes') }}</textarea>
                        @error('notes') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-300">Instrumento de Alta</label>
                    </div>

                </div>
                
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="{{ route('admin.inventory.index') }}" class="text-sm font-semibold leading-6 text-white">Cancelar</a>
                    <button type="submit" class="rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                        Guardar Instrumento
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
