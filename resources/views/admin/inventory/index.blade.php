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
            <!-- Pestañas de filtrado rápido: Todos / Pendientes de Validación -->
            <div class="flex border-b border-gray-800 mb-6 gap-4">
                <a href="{{ route('admin.inventory.index') }}" class="pb-3 px-1 text-sm font-medium border-b-2 {{ !request('verification') ? 'border-amber-500 text-amber-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                    Todos los Instrumentos
                </a>
                <a href="{{ route('admin.inventory.index', array_merge(request()->query(), ['verification' => 'pending'])) }}" class="pb-3 px-1 text-sm font-medium border-b-2 flex items-center gap-2 {{ request('verification') === 'pending' ? 'border-amber-500 text-amber-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                    <span>Pendientes de Validación</span>
                    @if($pendingCount > 0)
                        <span class="inline-flex items-center rounded-full bg-amber-500/20 px-2 py-0.5 text-xs font-bold text-amber-300 ring-1 ring-inset ring-amber-500/40">{{ $pendingCount }}</span>
                    @endif
                </a>
            </div>

            <form method="GET" action="{{ route('admin.inventory.index') }}" class="no-print mb-6 bg-gray-950 p-4 rounded-lg border border-gray-800 grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                @if(request('verification'))
                    <input type="hidden" name="verification" value="{{ request('verification') }}">
                @endif
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
                <div class="sm:col-span-4 flex justify-end gap-2">
                    @if(request()->hasAny(['musician_id', 'propiedad', 'status', 'show_inactive', 'verification']))
                        <a href="{{ route('admin.inventory.index') }}" class="rounded-md bg-gray-800 hover:bg-gray-700 px-4 py-2 text-sm font-semibold text-gray-300">Limpiar filtros</a>
                    @endif
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
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Nº Serie / Compra</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Propiedad</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Estado / Validación</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Fotos / Factura</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-400">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800 bg-gray-900">
                        @forelse($inventory as $item)
                        <tr class="{{ !$item->is_active ? 'opacity-50' : '' }} {{ !$item->is_verified ? 'bg-amber-950/20' : '' }}">
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
                                    <span class="ml-4 inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">Dado de baja</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                <div class="font-semibold text-white">{{ $item->instrument->name ?? '-' }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">Partitura: <span class="text-amber-400">{{ $item->tipo_partitura ?: 'N/A' }}</span></div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                {{ $item->brand ? $item->brand->name : '-' }} 
                                {{ $item->model ? ' / ' . $item->model : '' }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                <div><strong class="text-gray-400 text-xs">Serie:</strong> {{ $item->serial_number ?: 'N/A' }}</div>
                                @if($item->purchase_year)
                                    <div class="text-xs text-gray-400 mt-0.5"><strong class="text-gray-500">Año compra:</strong> {{ $item->purchase_year }}</div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300 capitalize">
                                {{ $item->propiedad }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                @if(!$item->is_verified)
                                    <span class="inline-flex items-center rounded-md bg-yellow-400/10 px-2 py-1 text-xs font-semibold text-yellow-400 ring-1 ring-inset ring-yellow-400/20">
                                        Pendiente Validación
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/20">
                                        Validado
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-300">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($item->photos && $item->photos->count() > 0)
                                        @foreach($item->photos as $photo)
                                            <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $photo->photo_path) }}" class="h-8 w-8 object-cover rounded border border-gray-700 hover:border-amber-500" title="{{ $photo->description ?: 'Foto instrumento' }}">
                                            </a>
                                        @endforeach
                                    @endif

                                    @if($item->invoice_path)
                                        <a href="{{ asset('storage/' . $item->invoice_path) }}" target="_blank" class="inline-flex items-center gap-1 rounded bg-indigo-900/40 border border-indigo-500/40 px-2 py-1 text-xs font-medium text-indigo-300 hover:bg-indigo-900/70" title="Ver Factura">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            Factura
                                        </a>
                                    @endif

                                    @if((!$item->photos || $item->photos->count() === 0) && !$item->invoice_path)
                                        <span class="text-xs text-gray-500">Ninguno</span>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300 space-x-2">
                                @if(!$item->is_verified)
                                    <form action="{{ route('admin.inventory.verify', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Confirmas que deseas validar este instrumento y agregarlo formalmente al inventario?');">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 rounded bg-emerald-600 hover:bg-emerald-500 px-2.5 py-1 text-xs font-semibold text-white shadow-sm transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                            Validar
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.inventory.show', $item) }}" class="text-amber-500 hover:text-amber-400">Ficha</a>
                                <span class="text-gray-700">|</span>
                                <a href="{{ route('admin.inventory.edit', $item) }}" class="text-blue-500 hover:text-blue-400">Editar</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-4 text-center text-sm text-gray-400 italic">No se encontraron instrumentos en el inventario con los filtros actuales.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
