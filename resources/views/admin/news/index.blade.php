<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Noticias y Eventos</h2>
                <p class="mt-2 text-sm text-gray-400">Publica noticias y actividades de la banda para que los músicos estén al tanto.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.news.create') }}" class="block rounded-md bg-amber-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                    Crear Noticia
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
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Título</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Fecha del Evento</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Estado</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 bg-gray-950">
                            @forelse ($news as $item)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                                        {{ $item->title }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                        {{ $item->event_date ? $item->event_date->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                        @if($item->is_published)
                                            <span class="inline-flex items-center rounded-md bg-green-500/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-500/20">Publicado</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-yellow-400/10 px-2 py-1 text-xs font-medium text-yellow-500 ring-1 ring-inset ring-yellow-400/20">Borrador</span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="{{ route('admin.news.edit', $item) }}" class="text-amber-500 hover:text-amber-400 mr-4">Editar</a>
                                        <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('⚠️ AVISO: Es preferible DESACTIVAR el registro (cambiar su estado a inactivo) en lugar de borrarlo para no perder el historial. ¿Estás completamente seguro de que deseas ELIMINARLO definitivamente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-8 text-sm text-gray-400 text-center">
                                        No hay noticias ni eventos creados.<br>
                                        <a href="{{ route('admin.news.create') }}" class="text-amber-500 hover:underline mt-2 inline-block">Crea la primera publicación</a>
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
        {{ $news->links() }}
    </div>
</x-admin-layout>
