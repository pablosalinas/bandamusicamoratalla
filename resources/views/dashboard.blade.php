<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">
            {{ __('Área del Músico') }}
        </h2>
    </x-slot>

    <div class="mt-8 flow-root">
        <div class="bg-gray-900 border border-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-300">
                <h3 class="text-xl font-bold mb-4 text-white">¡Hola, {{ $user->name }}!</h3>
                
                @if($user->instruments->count() > 0)
                    <p class="mb-6">Tus instrumentos registrados son: <strong class="text-amber-400">{{ $user->instruments->pluck('name')->implode(', ') }}</strong></p>
                    
                    <h4 class="text-lg font-semibold mb-4 border-b border-gray-800 pb-2 text-white">Tus Partituras Disponibles</h4>
                    
                    @if($sheetMusics->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($sheetMusics as $sheet)
                                <div class="bg-gray-950 border border-gray-800 rounded-lg p-5 shadow-sm hover:border-gray-700 transition-colors">
                                    <h5 class="font-bold text-lg text-amber-500">{{ $sheet->title }}</h5>
                                    <p class="text-sm text-gray-400 mb-6">{{ $sheet->composer ?? 'Compositor desconocido' }}</p>
                                    
                                    @if($sheet->pdf_file_path)
                                        <a href="{{ route('musician.sheet-music.download', $sheet) }}" class="inline-flex items-center rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 transition-colors">
                                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            Descargar PDF
                                        </a>
                                    @else
                                        <span class="text-sm text-red-400">PDF no disponible</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No hay partituras asignadas a tus instrumentos en este momento.</p>
                    @endif
                @else
                    <div class="rounded-md bg-yellow-900/30 border border-yellow-600/30 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-200">
                                    No tienes ningún instrumento asignado. Contacta con un administrador para que asigne tu instrumento y puedas ver tus partituras.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if(isset($missedAttendances) && $missedAttendances->count() > 0)
        <div class="bg-gray-900 border border-gray-800 shadow-sm sm:rounded-lg mt-8">
            <div class="p-6 text-gray-300">
                <h4 class="text-lg font-semibold mb-4 border-b border-gray-800 pb-2 text-white">Historial de Faltas de Asistencia</h4>
                <div class="overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-900">
                            <tr>
                                <th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold text-white sm:pl-6">Fecha</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-white">Evento</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-white">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 bg-gray-950">
                            @foreach($missedAttendances as $attendance)
                                <tr>
                                    <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                                        {{ \Carbon\Carbon::parse($attendance->event->event_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-400">
                                        {{ $attendance->event->name }} <span class="text-xs text-gray-500 capitalize ml-2">({{ $attendance->event->type }})</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-400">
                                        @if($attendance->status === 'absent')
                                            <span class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/30">Falta Injustificada</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-yellow-400/10 px-2 py-1 text-xs font-medium text-yellow-400 ring-1 ring-inset ring-yellow-400/30">Falta Justificada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-admin-layout>
