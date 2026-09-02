<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.inventory.index') }}" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Ficha de Instrumento</h2>
        </div>
    </x-slot>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Detalle del Instrumento -->
        <div class="lg:col-span-1">
            <div class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-white">{{ $inventory->instrument->name ?? 'Desconocido' }}</h3>
                    <a href="{{ route('admin.inventory.edit', $inventory) }}" class="text-amber-500 hover:text-amber-400 text-sm font-semibold">Editar</a>
                </div>
                
                <dl class="divide-y divide-gray-800 text-sm">
                    <div class="py-3 grid grid-cols-3 gap-4">
                        <dt class="text-gray-400">Marca / Modelo</dt>
                        <dd class="text-white col-span-2">{{ $inventory->brand->name ?? '-' }} / {{ $inventory->model ?? '-' }}</dd>
                    </div>
                    <div class="py-3 grid grid-cols-3 gap-4">
                        <dt class="text-gray-400">Nº Serie</dt>
                        <dd class="text-white col-span-2 font-mono text-amber-500">{{ $inventory->serial_number ?: 'Sin registrar' }}</dd>
                    </div>
                    <div class="py-3 grid grid-cols-3 gap-4">
                        <dt class="text-gray-400">Estado Fisico</dt>
                        <dd class="text-white col-span-2">{{ $inventory->status }}</dd>
                    </div>
                    <div class="py-3 grid grid-cols-3 gap-4">
                        <dt class="text-gray-400">Propiedad</dt>
                        <dd class="text-white col-span-2 capitalize">{{ $inventory->propiedad }}</dd>
                    </div>
                    <div class="py-3 grid grid-cols-3 gap-4">
                        <dt class="text-gray-400">Partitura</dt>
                        <dd class="text-white col-span-2">{{ $inventory->tipo_partitura ?: 'No especificado' }}</dd>
                    </div>
                    <div class="py-3 grid grid-cols-3 gap-4">
                        <dt class="text-gray-400">Alta/Baja</dt>
                        <dd class="text-white col-span-2">
                            @if($inventory->is_active)
                                <span class="text-green-400">Activo</span>
                            @else
                                <span class="text-red-400">Dado de baja</span>
                            @endif
                        </dd>
                    </div>
                    <div class="py-3">
                        <dt class="text-gray-400 mb-2">Notas Adicionales</dt>
                        <dd class="text-white bg-gray-950 p-3 rounded border border-gray-800">{{ $inventory->notes ?: 'Sin notas.' }}</dd>
                    </div>
                </dl>

                @if($inventory->photos && $inventory->photos->count() > 0)
                    <div class="mt-6 border-t border-gray-800 pt-6">
                        <h4 class="text-sm font-semibold text-gray-400 mb-4">Fotos</h4>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($inventory->photos as $photo)
                                <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank" class="block aspect-square relative group">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" class="w-full h-full object-cover rounded border border-gray-700">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Panel de Acción (Asignar / Devolver) -->
            <div class="mt-8 bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Gestión de Asignación</h3>
                
                @if($inventory->user_id)
                    <!-- Asignado actualmente -->
                    <div class="bg-amber-900/20 border border-amber-600/30 rounded p-4 mb-6">
                        <p class="text-sm text-amber-200/80 mb-2">Asignado actualmente a:</p>
                        <p class="text-lg font-bold text-amber-500">{{ $inventory->currentUser->name }} {{ $inventory->currentUser->last_name }}</p>
                    </div>

                    <!-- Botón de Devolver -->
                    <form action="{{ route('admin.inventory.return', $inventory) }}" method="POST" class="mb-6 border-b border-gray-800 pb-6" onsubmit="return confirm('¿Seguro que deseas registrar la devolución de este instrumento al inventario?');">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-400 mb-1">Notas de la devolución (opcional)</label>
                            <input type="text" name="notes" class="block w-full rounded-md border-0 bg-gray-950 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 sm:text-sm">
                        </div>
                        <button type="submit" class="w-full rounded-md bg-gray-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-600">
                            Devolver al Stock de la Banda
                        </button>
                    </form>

                    <!-- Form de Transferencia directa -->
                    <form action="{{ route('admin.inventory.transfer', $inventory) }}" method="POST" onsubmit="return confirm('Esto registrará la devolución por parte del músico actual y se lo asignará al nuevo músico. ¿Continuar?');">
                        @csrf
                        <label class="block text-sm font-medium text-white mb-2">O transferir directamente a otro músico:</label>
                        <select name="user_id" required class="block w-full rounded-md border-0 bg-gray-950 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm mb-3">
                            <option value="">-- Seleccionar nuevo músico --</option>
                            @foreach($musicians as $mus)
                                @if($mus->id != $inventory->user_id && $mus->is_active)
                                    <option value="{{ $mus->id }}">{{ $mus->name }} {{ $mus->last_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-400 mb-1">Motivo / Notas</label>
                            <input type="text" name="notes" class="block w-full rounded-md border-0 bg-gray-950 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 sm:text-sm">
                        </div>
                        <button type="submit" class="w-full rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                            Transferir Instrumento
                        </button>
                    </form>

                @else
                    <!-- En Stock -->
                    <div class="bg-green-900/20 border border-green-600/30 rounded p-4 mb-6">
                        <p class="text-green-400 font-semibold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Disponible en Inventario
                        </p>
                    </div>

                    <form action="{{ route('admin.inventory.assign', $inventory) }}" method="POST">
                        @csrf
                        <label class="block text-sm font-medium text-white mb-2">Asignar a músico:</label>
                        <select name="user_id" required class="block w-full rounded-md border-0 bg-gray-950 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm mb-3">
                            <option value="">-- Seleccionar músico --</option>
                            @foreach($musicians as $mus)
                                @if($mus->is_active)
                                    <option value="{{ $mus->id }}">{{ $mus->name }} {{ $mus->last_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-gray-400 mb-1">Notas de asignación</label>
                            <input type="text" name="notes" class="block w-full rounded-md border-0 bg-gray-950 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 sm:text-sm">
                        </div>
                        <button type="submit" class="w-full rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                            Asignar Instrumento
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Trazabilidad / Histórico -->
        <div class="lg:col-span-2">
            <div class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Trazabilidad (Historial de Movimientos)</h3>
                    <a href="{{ route('admin.inventory.traceability-pdf', $inventory) }}" target="_blank" class="rounded-md bg-amber-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 inline-flex items-center">
                        <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Informe PDF
                    </a>
                </div>
                
                @if($inventory->movements->count() > 0)
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($inventory->movements as $movement)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-800" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                @if($movement->type === 'assigned')
                                                    <span class="h-8 w-8 rounded-full bg-green-900 flex items-center justify-center ring-8 ring-gray-900">
                                                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                                    </span>
                                                @else
                                                    <span class="h-8 w-8 rounded-full bg-gray-800 flex items-center justify-center ring-8 ring-gray-900">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-300">
                                                        @if($movement->type === 'assigned')
                                                            Asignado a <span class="font-medium text-white">{{ $movement->toUser->name ?? 'Usuario Desconocido' }} {{ $movement->toUser->last_name ?? '' }}</span>
                                                        @elseif($movement->type === 'returned')
                                                            Devuelto al inventario por <span class="font-medium text-white">{{ $movement->fromUser->name ?? 'Usuario Desconocido' }} {{ $movement->fromUser->last_name ?? '' }}</span>
                                                        @endif
                                                    </p>
                                                    @if($movement->notes)
                                                        <p class="mt-1 text-sm text-gray-500">{{ $movement->notes }}</p>
                                                    @endif
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                    <time datetime="{{ $movement->created_at }}">{{ $movement->created_at->format('d/m/Y H:i') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-gray-500 italic">No hay historial de movimientos para este instrumento.</p>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
