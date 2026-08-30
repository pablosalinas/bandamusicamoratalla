<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Actas de la Junta Directiva</h2>
                <p class="mt-2 text-sm text-gray-400">Junta desde {{ $board->start_date->format('d/m/Y') }} hasta {{ $board->end_date ? $board->end_date->format('d/m/Y') : 'Actualidad' }}</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 flex gap-2">
                <a href="{{ route('admin.boards.index') }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Volver a Juntas
                </a>
                <a href="{{ route('admin.boards.minutes.create', $board) }}" class="block rounded-md bg-amber-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    Nueva Acta
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
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Fecha</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Título</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Documentos</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 bg-gray-950">
                            @forelse($minutes as $minute)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                                    {{ $minute->date->format('d/m/Y') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                    {{ $minute->title }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                    <div class="flex flex-col gap-2">
                                        <a href="{{ route('admin.boards.minutes.pdf', [$board, $minute]) }}" class="text-amber-500 hover:text-amber-400">📄 Generar PDF del texto</a>
                                        @if($minute->signed_pdf_path)
                                            <a href="{{ asset('storage/' . $minute->signed_pdf_path) }}" target="_blank" class="text-amber-500 hover:text-amber-400">✍️ Ver PDF Firmado</a>
                                        @else
                                            <span class="text-gray-500">Sin PDF firmado</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="{{ route('admin.boards.minutes.edit', [$board, $minute]) }}" class="text-amber-500 hover:text-amber-400 mr-4">Editar<span class="sr-only">, {{ $minute->title }}</span></a>
                                    
                                    <form action="{{ route('admin.boards.minutes.destroy', [$board, $minute]) }}" method="POST" class="inline-block" onsubmit="return confirm('⚠️ AVISO: Es preferible DESACTIVAR el registro (cambiar su estado a inactivo) en lugar de borrarlo para no perder el historial. ¿Estás completamente seguro de que deseas ELIMINARLO definitivamente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400">Eliminar<span class="sr-only">, {{ $minute->title }}</span></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-sm text-gray-400 text-center">No hay actas registradas para esta junta.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $minutes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
