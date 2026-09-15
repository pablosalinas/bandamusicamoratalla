<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Eventos y Asistencia</h2>
                <p class="mt-2 text-sm text-gray-400">Listado de ensayos, conciertos y actividades de la banda. Gestiona la asistencia de los músicos.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 flex gap-2 sm:flex-none">
                <button type="button" onclick="document.getElementById('bulk-create-modal').classList.remove('hidden')" class="block rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                    Generar Periódicos
                </button>
                <button type="button" onclick="document.getElementById('bulk-delete-modal').classList.remove('hidden')" class="block rounded-md bg-red-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                    Borrar Futuros
                </button>
                <a href="{{ route('admin.events.create') }}" class="block rounded-md bg-amber-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                    Añadir Nuevo
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

    <!-- Modal Generar Periódicos -->
    <div id="bulk-create-modal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <form action="{{ route('admin.events.bulk-create') }}" method="POST">
                        @csrf
                        <div>
                            <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-white" id="modal-title">Generar Eventos Periódicos</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-400">Rellena este formulario para generar eventos (ej. ensayos) repetitivos de forma automática.</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium leading-6 text-white">Nombre del Evento</label>
                                <input type="text" name="name" value="Ensayo" required class="mt-1 block w-full rounded-md border-0 bg-gray-700 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium leading-6 text-white">Tipo de Evento</label>
                                <input type="text" name="type" value="Ensayo" required class="mt-1 block w-full rounded-md border-0 bg-gray-700 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium leading-6 text-white">Fecha Inicio</label>
                                    <input type="date" name="start_date" required class="mt-1 block w-full rounded-md border-0 bg-gray-700 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm" style="color-scheme: dark;">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-medium leading-6 text-white">Fecha Fin</label>
                                    <input type="date" name="end_date" required class="mt-1 block w-full rounded-md border-0 bg-gray-700 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm" style="color-scheme: dark;">
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium leading-6 text-white">Día de la Semana</label>
                                    <select name="day_of_week" required class="mt-1 block w-full rounded-md border-0 bg-gray-700 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm">
                                        <option value="1">Lunes</option>
                                        <option value="2">Martes</option>
                                        <option value="3">Miércoles</option>
                                        <option value="4">Jueves</option>
                                        <option value="5" selected>Viernes</option>
                                        <option value="6">Sábado</option>
                                        <option value="0">Domingo</option>
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-medium leading-6 text-white">Hora (HH:MM)</label>
                                    <input type="time" name="time" value="21:30" required class="mt-1 block w-full rounded-md border-0 bg-gray-700 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm" style="color-scheme: dark;">
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Generar</button>
                            <button type="button" onclick="document.getElementById('bulk-create-modal').classList.add('hidden')" class="mt-3 inline-flex w-full justify-center rounded-md bg-gray-700 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-600 hover:bg-gray-600 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Borrar Masivo -->
    <div id="bulk-delete-modal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <form action="{{ route('admin.events.bulk-delete') }}" method="POST">
                        @csrf
                        <div>
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-white" id="modal-title">Borrar Eventos Futuros</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-400">Elimina masivamente eventos en un periodo de fechas. Por seguridad, <strong class="text-white">solo se borrarán eventos que sean hoy o en el futuro</strong>. Los eventos pasados no se verán afectados, aunque la fecha de inicio caiga en el pasado.</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 space-y-4">
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium leading-6 text-white">Desde Fecha</label>
                                    <input type="date" name="from_date" required class="mt-1 block w-full rounded-md border-0 bg-gray-700 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm" style="color-scheme: dark;">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-medium leading-6 text-white">Hasta Fecha</label>
                                    <input type="date" name="to_date" required class="mt-1 block w-full rounded-md border-0 bg-gray-700 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm" style="color-scheme: dark;">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium leading-6 text-white">Filtro de Tipo (Opcional)</label>
                                <input type="text" name="type" placeholder="Ej. Ensayo (Dejar en blanco para todos)" class="mt-1 block w-full rounded-md border-0 bg-gray-700 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm">
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto" onclick="return confirm('¿Estás seguro de que quieres borrar masivamente estos eventos futuros?')">Confirmar Borrado</button>
                            <button type="button" onclick="document.getElementById('bulk-delete-modal').classList.add('hidden')" class="mt-3 inline-flex w-full justify-center rounded-md bg-gray-700 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-600 hover:bg-gray-600 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
