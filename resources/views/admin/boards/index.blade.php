<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Junta Directiva</h2>
                <p class="mt-2 text-sm text-gray-400">Listado de las legislaturas de la banda. Desde aquí puedes crear una nueva legislatura y asignar los cargos de los músicos.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.boards.create') }}" class="block rounded-md bg-amber-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                    Nueva Legislatura
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
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Periodo</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Estado</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 bg-gray-950">
                            @forelse ($boards as $board)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                                        {{ $board->start_date->format('d/m/Y') }} 
                                        - 
                                        {{ $board->end_date ? $board->end_date->format('d/m/Y') : 'Actualidad' }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                        @if($board->is_active)
                                            <span class="inline-flex items-center rounded-md bg-green-500/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-500/20">Activa</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-gray-400/10 px-2 py-1 text-xs font-medium text-gray-400 ring-1 ring-inset ring-gray-400/20">Inactiva</span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="{{ route('admin.boards.minutes.index', $board) }}" class="text-emerald-400 hover:text-emerald-300 mr-4">Actas</a>
                                        <a href="{{ route('admin.boards.show', $board) }}" class="text-indigo-400 hover:text-indigo-300 mr-4">Gestionar Miembros</a>
                                        <a href="{{ route('admin.boards.edit', $board) }}" class="text-amber-500 hover:text-amber-400 mr-4">Editar</a>
                                        <form action="{{ route('admin.boards.destroy', $board) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar esta legislatura? Se perderá el historial de miembros.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-8 text-sm text-gray-400 text-center">
                                        No hay legislaturas registradas.<br>
                                        <a href="{{ route('admin.boards.create') }}" class="text-amber-500 hover:underline mt-2 inline-block">Crear la primera legislatura</a>
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
        {{ $boards->links() }}
    </div>
</x-admin-layout>
