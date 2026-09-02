<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Inventario de Instrumentos</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.inventory.create') }}" class="no-print bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow flex items-center">
                    Nuevo Instrumento
                </a>
                <a href="{{ route('admin.inventory.pdf', request()->all()) }}" target="_blank" class="no-print bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow flex items-center">
                    PDF
                </a>
                <button onclick="window.print()" class="no-print bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded shadow flex items-center">
                    Imprimir
                </button>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 bg-gray-900 overflow-hidden shadow-sm ring-1 ring-gray-800 sm:rounded-xl print:shadow-none print:ring-0">
        <div class="p-6 print:p-0">
            <form method="GET" action="{{ route('admin.inventory.index') }}" class="no-print mb-6 bg-gray-950 p-4 rounded-lg border border-gray-800 grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-400">Filtrar por Músico Asignado</label>
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
                        <option value="banda" {{ request('propiedad') === 'banda' ? 'selected' : '' }}>De la banda</option>
                        <option value="musico" {{ request('propiedad') === 'musico' ? 'selected' : '' }}>Propio del músico</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400">Estado de Asignación</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                        <option value="">Todos</option>
                        <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Asignados</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Disponibles (En Stock)</option>
                    </select>
                </div>
                <div class="flex items-center h-full pb-2">
                    <input type="checkbox" name="show_inactive" id="show_inactive" value="1" {{ request('show_inactive') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                    <label for="show_inactive" class="ml-2 block text-sm font-medium text-gray-300">Mostrar Inactivos (Bajas)</label>
                </div>
                <div class="sm:col-span-4 flex justify-end">
                    <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">Aplicar Filtros</button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gray-950">
                        <tr>
                            <th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400 sm:pl-0">Asignado A</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Instrumento</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Marca / Modelo</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Nº Serie</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Propiedad</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Fotos</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800 bg-gray-900">
                        @forelse($inventory as $item)
                        <tr class="{{ !$item->is_active ? 'opacity-50' : '' }}">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-0">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        @if($item->users->count() > 0)
                                            @foreach($item->users as $u)
                                                <div class="font-medium text-white">{{ $u->name }} {{ $u->last_name }}</div>
                                                @if(!$loop->last) <br> @endif
                                            @endforeach
                                        @else
                                            <div class="font-medium text-gray-500 italic">En stock (Disponible)</div>
                                        @endif
                                    </div>
                                </div>
                                @if(!$item->is_active)
                                    <span class="ml-2 inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">Dado de baja</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                {{ $item->instrument->name ?? '-' }}
                                <div class="text-xs text-gray-500 mt-1">Partitura: {{ $item->tipo_partitura ?: 'N/A' }}</div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                {{ $item->brand ? $item->brand->name : '-' }} 
                                {{ $item->model ? ' / ' . $item->model : '' }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                {{ $item->serial_number ?: 'N/A' }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300 capitalize">
                                {{ $item->propiedad }}
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-300">
                                @if($item->photos && $item->photos->count() > 0)
                                    <div class="flex gap-2 flex-wrap max-w-[120px]">
                                        @foreach($item->photos as $photo)
                                            <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $photo->photo_path) }}" class="h-8 w-8 object-cover rounded border border-gray-700" title="{{ $photo->description }}">
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-500">Sin fotos</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300 space-x-2">
                                <a href="{{ route('admin.inventory.show', $item) }}" class="text-amber-500 hover:text-amber-400">Ficha</a>
                                <span class="text-gray-700">|</span>
                                <a href="{{ route('admin.inventory.edit', $item) }}" class="text-blue-500 hover:text-blue-400">Editar</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-sm text-gray-400 italic">No se encontraron instrumentos en el inventario con los filtros actuales.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
