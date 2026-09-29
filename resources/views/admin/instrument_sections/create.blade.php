<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Añadir Cuerda o Subcuerda</h2>
                <p class="mt-2 text-sm text-gray-400">Si seleccionas una cuerda superior, se creará como una subcuerda (por ejemplo: Viento Madera -> Clarinetes).</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.instrument-sections.index') }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 max-w-xl">
        <form action="{{ route('admin.instrument-sections.store') }}" method="POST" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            @csrf
            
            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-6">
                    
                    <div>
                        <label for="name" class="block text-sm font-medium leading-6 text-white">Nombre de la Cuerda / Subcuerda *</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Ej: Viento Madera, Clarinetes, Trompetas..." required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="parent_id" class="block text-sm font-medium leading-6 text-white">Cuerda Superior (Opcional)</label>
                        <p class="text-xs text-gray-400 mb-2">Déjalo en blanco si es una Cuerda Principal (ej. Viento Madera, Percusión). Selecciónala si es una Subcuerda (ej. Clarinetes dentro de Viento Madera).</p>
                        <div class="mt-2">
                            <select name="parent_id" id="parent_id" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                                <option value="">-- Ninguna (Es Cuerda Principal) --</option>
                                @foreach($mainSections as $ms)
                                    <option value="{{ $ms->id }}" {{ old('parent_id') == $ms->id ? 'selected' : '' }}>
                                        {{ $ms->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('parent_id') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="order_index" class="block text-sm font-medium leading-6 text-white">Número de Orden (Para listas de asistencia)</label>
                        <p class="text-xs text-gray-400 mb-2">Las cuerdas y subcuerdas con menor número aparecerán primero en la lista de pasar lista y filtros.</p>
                        <div class="mt-2">
                            <input type="number" name="order_index" id="order_index" value="{{ old('order_index', 1) }}" min="0" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('order_index') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium leading-6 text-white">Descripción / Observaciones (Opcional)</label>
                        <div class="mt-2">
                            <textarea name="description" id="description" rows="3" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">{{ old('description') }}</textarea>
                        </div>
                        @error('description') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center mt-2 border-t border-gray-800 pt-4">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                        <label for="is_active" class="ml-3 block text-sm font-medium leading-6 text-white">Sección Activa</label>
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-800 px-4 py-4 sm:px-8">
                <a href="{{ route('admin.instrument-sections.index') }}" class="text-sm font-semibold leading-6 text-white">Cancelar</a>
                <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                    Guardar Cuerda
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
