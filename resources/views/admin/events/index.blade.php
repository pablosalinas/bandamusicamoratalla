<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Eventos y Asistencia</h2>
                <p class="mt-2 text-sm text-gray-400">Listado de ensayos, conciertos y actividades de la banda. Gestiona la asistencia de los músicos.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.events.create') }}" class="block rounded-md bg-amber-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                    Añadir Nuevo Evento
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-900">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Fecha del Evento</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Nombre</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Tipo</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Estado</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Asistencia</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 bg-gray-950">
                            @forelse ($events as $event)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                        {{ $event->name }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400 capitalize">
                                        <span class="inline-flex items-center rounded-md bg-{{ $event->color }}-400/10 px-2 py-1 text-xs font-medium text-{{ $event->color }}-400 ring-1 ring-inset ring-{{ $event->color }}-400/30">{{ $event->type }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                        @if($event->is_active)
                                            <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/30">Activo</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-gray-400/10 px-2 py-1 text-xs font-medium text-gray-400 ring-1 ring-inset ring-gray-400/30">Oculto</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                        <a href="{{ route('admin.events.attendance', $event) }}" class="inline-flex items-center rounded-md bg-blue-500/10 px-3 py-2 text-sm font-medium text-blue-400 ring-1 ring-inset ring-blue-500/20 hover:bg-blue-500/20">
                                            Pasar Lista
                                        </a>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="{{ route('admin.events.edit', $event) }}" class="text-amber-500 hover:text-amber-400 mr-4">Editar</a>
                                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline-block" onsubmit="return confirm('⚠️ AVISO: Es preferible DESACTIVAR el registro (cambiar su estado a inactivo) en lugar de borrarlo para no perder el historial. ¿Estás completamente seguro de que deseas ELIMINARLO definitivamente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-8 text-sm text-gray-400 text-center">
                                        No hay eventos registrados.<br>
                                        <a href="{{ route('admin.events.create') }}" class="text-amber-500 hover:underline mt-2 inline-block">Crear el primer evento</a>
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
        {{ $events->links() }}
    </div>
</x-admin-layout>
