<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Área del Músico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">¡Hola, {{ $user->name }}!</h3>
                    
                    @if($user->instruments->count() > 0)
                        <p class="mb-6">Tus instrumentos registrados son: <strong>{{ $user->instruments->pluck('name')->implode(', ') }}</strong></p>
                        
                        <h4 class="text-md font-semibold mb-4 border-b pb-2">Tus Partituras Disponibles</h4>
                        
                        @if($sheetMusics->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($sheetMusics as $sheet)
                                    <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                                        <h5 class="font-bold text-lg text-indigo-700">{{ $sheet->title }}</h5>
                                        <p class="text-sm text-gray-600 mb-4">{{ $sheet->composer ?? 'Compositor desconocido' }}</p>
                                        
                                        @if($sheet->pdf_file_path)
                                            <a href="{{ route('musician.sheet-music.download', $sheet) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                                Descargar PDF
                                            </a>
                                        @else
                                            <span class="text-sm text-red-500">PDF no disponible</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic">No hay partituras asignadas a tus instrumentos en este momento.</p>
                        @endif
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        No tienes ningún instrumento asignado. Contacta con un administrador para que asigne tu instrumento y puedas ver tus partituras.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
