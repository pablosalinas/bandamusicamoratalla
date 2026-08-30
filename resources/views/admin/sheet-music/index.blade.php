<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Partituras</h2>
                <p class="mt-2 text-sm text-gray-400">Listado del archivo musical. Aquí puedes gestionar las obras y los instrumentos asociados a cada una.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.sheet-music.create') }}" class="block rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                    Añadir Nueva Obra
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
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Obra (Título)</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Compositor / Arreglista</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Archivo PDF</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Instrumentos Vinculados</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Estado</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 bg-gray-950">
                            @forelse ($sheetMusics as $sheet)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        <div class="font-medium text-white">{{ $sheet->title }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                        {{ $sheet->composer ?? 'Desconocido' }}
                                        @if($sheet->arranger)
                                            <br><span class="text-xs text-gray-500">Arr: {{ $sheet->arranger }}</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                        @if($sheet->pdf_file_path)
                                            <a href="{{ route('admin.sheet-music.download', $sheet) }}" class="text-blue-500 hover:text-blue-400 inline-flex items-center">
                                                <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                                Descargar Guión
                                            </a>
                                        @else
                                            <span class="text-gray-600">Sin archivo</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-300 max-w-xs truncate">
                                        <span class="inline-flex items-center rounded-md bg-gray-800 px-2 py-1 text-xs font-medium text-gray-300 ring-1 ring-inset ring-gray-700">
                                            {{ $sheet->instruments->count() }} instrumentos
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                        @if($sheet->is_active)
                                            <span class="inline-flex items-center rounded-md bg-green-500/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-500/20">Activa</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-gray-400/10 px-2 py-1 text-xs font-medium text-gray-400 ring-1 ring-inset ring-gray-400/20">Inactiva</span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="{{ route('admin.sheet-music.edit', $sheet) }}" class="text-amber-500 hover:text-amber-400 mr-4">Editar</a>
                                        <form action="{{ route('admin.sheet-music.destroy', $sheet) }}" method="POST" class="inline-block" onsubmit="return confirm('⚠️ AVISO: Es preferible DESACTIVAR el registro (cambiar su estado a inactivo) en lugar de borrarlo para no perder el historial. ¿Estás completamente seguro de que deseas ELIMINARLO definitivamente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-8 text-sm text-gray-400 text-center">
                                        No hay partituras registradas en el archivo.<br>
                                        <a href="{{ route('admin.sheet-music.create') }}" class="text-blue-500 hover:underline mt-2 inline-block">Sube la primera obra</a>
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
        {{ $sheetMusics->links() }}
    </div>
</x-admin-layout>
