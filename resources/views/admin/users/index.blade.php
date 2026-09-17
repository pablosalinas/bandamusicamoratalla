<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Músicos y Usuarios</h2>
                <p class="mt-2 text-sm text-gray-400">Listado de todos los miembros de la banda, junta directiva y administradores.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 flex flex-wrap items-center gap-2">
                <!-- Botón Excel / CSV -->
                <a href="{{ route('admin.users.export.csv', ['status' => $status, 'search' => $search]) }}" class="inline-flex items-center gap-1.5 rounded-md bg-emerald-700 hover:bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition-colors" title="Descargar listado en formato Excel / CSV">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Excel / CSV
                </a>

                <!-- Botón PDF / Imprimir Papel -->
                <a href="{{ route('admin.users.export.pdf', ['status' => $status, 'search' => $search]) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-md bg-red-700 hover:bg-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition-colors" title="Ver listado compacto para PDF o impresión en papel">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    PDF / Papel
                </a>

                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-1.5 rounded-md bg-amber-600 px-3 py-2 text-center text-xs font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Añadir Nuevo
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Pestañas de estado -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.users.index', ['status' => 'all', 'search' => $search]) }}" class="px-3 py-1.5 rounded-md text-xs font-semibold transition-colors {{ $status === 'all' ? 'bg-amber-600 text-white' : 'bg-gray-800 text-gray-400 hover:text-white hover:bg-gray-700' }}">
                Todos los Miembros
            </a>
            <a href="{{ route('admin.users.index', ['status' => 'pending', 'search' => $search]) }}" class="px-3 py-1.5 rounded-md text-xs font-semibold transition-colors flex items-center gap-1.5 {{ $status === 'pending' ? 'bg-amber-500 text-gray-950 font-bold' : 'bg-gray-800 text-gray-400 hover:text-white hover:bg-gray-700' }}">
                <span>Pendientes de Validación</span>
                @if($pendingCount > 0)
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-extrabold rounded-full {{ $status === 'pending' ? 'bg-gray-950 text-amber-400' : 'bg-amber-500 text-gray-950' }}">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.users.index', ['status' => 'active', 'search' => $search]) }}" class="px-3 py-1.5 rounded-md text-xs font-semibold transition-colors {{ $status === 'active' ? 'bg-green-600 text-white' : 'bg-gray-800 text-gray-400 hover:text-white hover:bg-gray-700' }}">
                Activos
            </a>
            <a href="{{ route('admin.users.index', ['status' => 'inactive', 'search' => $search]) }}" class="px-3 py-1.5 rounded-md text-xs font-semibold transition-colors {{ $status === 'inactive' ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-400 hover:text-white hover:bg-gray-700' }}">
                Inactivos / Bajas
            </a>
        </div>

        <!-- Buscador -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre, NIF, teléfono..." class="block w-full sm:w-64 rounded-md border-0 bg-gray-900 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs leading-6">
            <button type="submit" class="rounded-md bg-gray-800 hover:bg-gray-700 px-3 py-1.5 text-xs font-semibold text-white border border-gray-700">Buscar</button>
            @if(!empty($search))
                <a href="{{ route('admin.users.index', ['status' => $status]) }}" class="text-xs text-gray-400 hover:text-amber-400">Limpiar</a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="mt-4 p-4 rounded-lg bg-green-900/20 border border-green-500/30 text-green-400 text-sm flex items-center gap-2">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="mt-6 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-900">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Músico / Miembro</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">NIF / Contacto</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Rol / Estado</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Instrumentos</th>
                                @if(auth()->user()->canViewIban())
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">IBAN</th>
                                @endif
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-right text-sm font-semibold text-white">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 bg-gray-950">
                            @forelse ($users as $user)
                                <tr class="{{ !$user->is_active ? 'bg-amber-950/10' : '' }}">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <img class="h-10 w-10 rounded-full bg-gray-800 object-cover border border-gray-700" src="{{ $user->photo_url }}" alt="Foto de {{ $user->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-white flex items-center gap-2">
                                                    {{ $user->name }} {{ $user->last_name }}
                                                    @if($user->joining_year)
                                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-800 text-amber-400 font-normal">Desde {{ $user->joining_year }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-xs text-gray-300">
                                        <div class="font-mono text-amber-300/90 font-medium">{{ $user->nif ?: 'Sin NIF' }}</div>
                                        <div class="text-gray-400 mt-0.5">{{ $user->phone ?: '-' }}</div>
                                        @php
                                            $age = $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->age : null;
                                        @endphp
                                        @if($age !== null && $age < 18)
                                            <div class="mt-1">
                                                <span class="inline-flex items-center rounded bg-amber-900/40 px-1.5 py-0.5 text-[10px] font-medium text-amber-300 ring-1 ring-inset ring-amber-500/30">
                                                    Menor ({{ $age }} años)
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                        @if($user->role === 'admin')
                                            <span class="inline-flex items-center rounded-md bg-purple-400/10 px-2 py-1 text-xs font-medium text-purple-400 ring-1 ring-inset ring-purple-400/30">Administrador</span>
                                        @elseif($user->role === 'treasurer')
                                            <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/30">Tesorero</span>
                                        @elseif($user->role === 'director')
                                            <span class="inline-flex items-center rounded-md bg-amber-400/10 px-2 py-1 text-xs font-medium text-amber-400 ring-1 ring-inset ring-amber-400/30">Director</span>
                                        @elseif($user->role === 'external')
                                            <span class="inline-flex items-center rounded-md bg-gray-400/10 px-2 py-1 text-xs font-medium text-gray-400 ring-1 ring-inset ring-gray-400/30">Externo</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-1 text-xs font-medium text-blue-400 ring-1 ring-inset ring-blue-400/30">Músico</span>
                                        @endif

                                        @if(!$user->is_active)
                                            <span class="ml-1 inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/30">
                                                {{ $user->privacy_accepted_at ? 'Pendiente' : 'Inactivo' }}
                                            </span>
                                        @else
                                            <span class="ml-1 inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/30">Activo</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-300 max-w-xs truncate">
                                        @if($user->inventories->count() > 0)
                                            {{ $user->inventories->map(fn($inv) => $inv->instrument->name ?? 'Desconocido')->implode(', ') }}
                                        @else
                                            <span class="text-gray-600 italic">Ninguno asignado</span>
                                        @endif
                                    </td>
                                    @if(auth()->user()->canViewIban())
                                    <td class="px-3 py-4 text-sm text-gray-300 whitespace-nowrap font-mono">
                                        {{ $user->iban ?: '-' }}
                                    </td>
                                    @endif
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <!-- Validación directa si está inactivo -->
                                        @if(!$user->is_active)
                                            <form action="{{ route('admin.users.validate', $user) }}" method="POST" class="inline-block mr-3" onsubmit="return confirm('¿Confirmas la validación y activación del músico {{ $user->name }} {{ $user->last_name }}?');">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-green-600/20 px-2.5 py-1 text-xs font-semibold text-green-400 ring-1 ring-inset ring-green-500/30 hover:bg-green-600 hover:text-white transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    Validar Alta
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-amber-500 hover:text-amber-400 mr-3">Editar</a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('⚠️ AVISO: Es preferible DESACTIVAR el registro (cambiar su estado a inactivo) en lugar de borrarlo para no perder el historial. ¿Estás completamente seguro de que deseas ELIMINARLO definitivamente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-8 text-sm text-gray-400 text-center">
                                        @if($status === 'pending')
                                            No hay ninguna solicitud de músico pendiente de validación.
                                        @else
                                            No se encontraron miembros registrados con los filtros seleccionados.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-admin-layout>
