<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Inventario de Instrumentos Asignados</h2>
    </x-slot>

    <div class="mt-8 bg-gray-900 overflow-hidden shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.inventory.index') }}" class="mb-6 bg-gray-950 p-4 rounded-lg border border-gray-800 grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-400">Filtrar por Músico</label>
                    <select name="musician_id" class="mt-1 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                        <option value="">Todos los músicos</option>
                        @foreach($musiciansList as $musicianOption)
                            <option value="{{ $musicianOption->id }}" {{ request('musician_id') == $musicianOption->id ? 'selected' : '' }}>{{ $musicianOption->name }} {{ $musicianOption->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400">Filtrar por Propiedad</label>
                    <select name="propiedad" class="mt-1 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                        <option value="">Cualquiera</option>
                        <option value="Propio" {{ request('propiedad') === 'Propio' ? 'selected' : '' }}>Propio</option>
                        <option value="De la banda" {{ request('propiedad') === 'De la banda' ? 'selected' : '' }}>De la banda</option>
                        <option value="Prestado" {{ request('propiedad') === 'Prestado' ? 'selected' : '' }}>Prestado</option>
                        <option value="Reliquia" {{ request('propiedad') === 'Reliquia' ? 'selected' : '' }}>Reliquia</option>
                        <option value="Alquilado" {{ request('propiedad') === 'Alquilado' ? 'selected' : '' }}>Alquilado</option>
                    </select>
                </div>
                <div class="flex items-center h-full pb-2">
                    <input type="checkbox" name="show_all" id="show_all" value="1" {{ request('show_all') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                    <label for="show_all" class="ml-2 block text-sm font-medium text-gray-300">Mostrar Inactivos (Bajas)</label>
                </div>
                <div>
                    <button type="submit" class="w-full rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">Filtrar</button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gray-950">
                        <tr>
                            <th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400 sm:pl-0">Músico</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Instrumento</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Marca / Modelo</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Nº Serie</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Partitura / Propiedad</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Estado</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Fotos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800 bg-gray-900">
                        @forelse($inventory as $item)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-0">
                                {{ $item->musician->name }} {{ $item->musician->last_name }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                {{ $item->instrument->name }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                {{ $item->brand ? $item->brand->name : '-' }} 
                                {{ $item->modelo ? ' / ' . $item->modelo : '' }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                {{ $item->pivot->serial_number ?: 'N/A' }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                <span class="block">{{ $item->pivot->tipo_partitura ?: 'Sin partitura' }}</span>
                                <span class="block text-xs text-amber-500 mt-1">{{ $item->pivot->propiedad ?: 'No definida' }}</span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                @if($item->pivot->is_active && $item->musician->is_active)
                                    <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/20">Activo</span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-300">
                                @if($item->photos->count() > 0)
                                    <div class="flex gap-2 flex-wrap max-w-xs">
                                        @foreach($item->photos as $photo)
                                            <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $photo->photo_path) }}" class="h-10 w-10 object-cover rounded border border-gray-700" alt="Foto">
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-500">Sin fotos</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-sm text-gray-400 italic">No se encontraron instrumentos asignados con los filtros actuales.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
