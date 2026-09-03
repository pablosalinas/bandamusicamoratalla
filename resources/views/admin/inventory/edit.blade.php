<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Editar Instrumento (Inventario)</h2>
    </x-slot>

    <div class="mt-8 bg-gray-900 overflow-hidden shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
        <div class="p-6">
            <form action="{{ route('admin.inventory.update', $inventory) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Catálogo de Instrumento *</label>
                        <select name="instrument_catalog_id" required class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                            <option value="">-- Seleccionar --</option>
                            @foreach($catalogs as $catalog)
                                <option value="{{ $catalog->id }}" {{ old('instrument_catalog_id', $inventory->instrument_catalog_id) == $catalog->id ? 'selected' : '' }}>{{ $catalog->name }}</option>
                            @endforeach
                        </select>
                        @error('instrument_catalog_id') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Marca</label>
                        <select name="instrument_brand_id" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                            <option value="">-- Seleccionar --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('instrument_brand_id', $inventory->instrument_brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('instrument_brand_id') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Modelo</label>
                        <input type="text" name="model" value="{{ old('model', $inventory->model) }}" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                        @error('model') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Nº Serie</label>
                        <input type="text" name="serial_number" value="{{ old('serial_number', $inventory->serial_number) }}" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                        @error('serial_number') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Propiedad *</label>
                        <select name="propiedad" required class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                            <option value="banda" {{ old('propiedad', $inventory->propiedad) == 'banda' ? 'selected' : '' }}>De la banda</option>
                            <option value="musico" {{ old('propiedad', $inventory->propiedad) == 'musico' ? 'selected' : '' }}>Propio del músico</option>
                        </select>
                        @error('propiedad') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Estado Físico *</label>
                        <select name="status" required class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                            <option value="good" {{ old('status', $inventory->status) == 'good' ? 'selected' : '' }}>Bueno</option>
                            <option value="repair" {{ old('status', $inventory->status) == 'repair' ? 'selected' : '' }}>En Reparación</option>
                            <option value="bad" {{ old('status', $inventory->status) == 'bad' ? 'selected' : '' }}>Malo / Baja</option>
                        </select>
                        @error('status') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-white">Tipo Partitura</label>
                        <input type="text" name="tipo_partitura" value="{{ old('tipo_partitura', $inventory->tipo_partitura) }}" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                        @error('tipo_partitura') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium leading-6 text-white">Notas / Observaciones</label>
                        <textarea name="notes" rows="3" class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">{{ old('notes', $inventory->notes) }}</textarea>
                        @error('notes') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $inventory->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-300">Instrumento de Alta</label>
                    </div>

                </div>
                
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="{{ route('admin.inventory.index') }}" class="text-sm font-semibold leading-6 text-white">Cancelar</a>
                    <button type="submit" class="rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                        Guardar Cambios
                    </button>
                </div>
            </form>

            <div class="mt-12 border-t border-gray-800 pt-8">
                <h3 class="text-lg font-bold text-white mb-4">Fotos del Instrumento</h3>
                
                <form action="{{ route('admin.instrument-photos.store', ['inventory_id' => $inventory->id]) }}" method="POST" enctype="multipart/form-data" class="mb-6 flex items-end gap-4">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Añadir nueva foto</label>
                        <input type="file" name="photo" required accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Descripción (opcional)</label>
                        <input type="text" name="description" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 sm:text-sm">
                    </div>
                    <button type="submit" class="rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">Subir</button>
                </form>

                @if($inventory->photos && $inventory->photos->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($inventory->photos as $photo)
                            <div class="bg-gray-950 p-3 rounded-lg border border-gray-800">
                                <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank" class="block aspect-square mb-3">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" class="w-full h-full object-cover rounded border border-gray-700">
                                </a>
                                
                                <form action="{{ route('admin.instrument-photos.update', $photo) }}" method="POST" class="flex gap-2 mb-2">
                                    @csrf @method('PUT')
                                    <input type="text" name="description" value="{{ $photo->description }}" class="block w-full rounded-md border-0 bg-gray-900 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 sm:text-xs" placeholder="Descripción...">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white rounded px-2 py-1 text-xs">Guardar</button>
                                </form>
                                
                                <form action="{{ route('admin.instrument-photos.destroy', $photo) }}" method="POST" onsubmit="return confirm('¿Borrar esta foto?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full text-red-500 hover:text-red-400 text-xs text-center py-1 border border-red-500/30 rounded">Eliminar Foto</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">No hay fotos guardadas para este instrumento.</p>
                @endif
            </div>

            <div class="mt-12 border-t border-gray-800 pt-8 flex justify-end">
                <form action="{{ route('admin.inventory.destroy', $inventory) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar definitivamente este instrumento del inventario? Esto borrará también sus fotos y su historial de movimientos.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                        Eliminar Instrumento
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
