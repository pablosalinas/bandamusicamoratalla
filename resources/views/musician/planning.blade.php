<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Planning de Actividades</h2>
                <p class="mt-2 text-sm text-gray-400">Listado de ensayos, salidas y actividades programadas próximamente.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('musician.planning.pdf') }}" target="_blank" class="inline-flex items-center rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.5 2A1.5 1.5 0 003 3.5v13A1.5 1.5 0 004.5 18h11a1.5 1.5 0 001.5-1.5V7.621a1.5 1.5 0 00-.44-1.06l-4.12-4.122A1.5 1.5 0 0011.378 2H4.5zm2.25 8.5a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5zm0 3a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5z" clip-rule="evenodd" />
                    </svg>
                    Descargar PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 flow-root">
        <div class="space-y-12">
            @forelse($events as $month => $monthEvents)
                <div>
                    <h3 class="text-xl font-bold text-amber-500 mb-4 pb-2 border-b border-gray-800 capitalize">{{ $month }}</h3>
                    
                    <div class="overflow-hidden bg-gray-900 shadow-sm ring-1 ring-white/10 sm:rounded-lg">
                        <ul role="list" class="divide-y divide-gray-800">
                            @foreach($monthEvents as $event)
                                <li class="relative flex justify-between gap-x-6 py-5 px-4 sm:px-6 hover:bg-gray-800/50 transition-colors">
                                    <div class="flex gap-x-4">
                                        <div class="min-w-0 flex-auto">
                                            <p class="text-sm font-semibold leading-6 text-white">
                                                <span class="absolute inset-x-0 -top-px bottom-0"></span>
                                                {{ $event->name }}
                                            </p>
                                            <p class="mt-1 flex text-xs leading-5 text-gray-400">
                                                <svg class="mr-1.5 h-4 w-4 flex-shrink-0 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                </svg>
                                                {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d \d\e F \d\e Y \a \l\a\s H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-x-4">
                                        <div class="hidden sm:flex sm:flex-col sm:items-end">
                                            <p class="text-sm leading-6 text-white capitalize">
                                                <span class="inline-flex items-center rounded-md bg-{{ $event->color }}-400/10 px-2.5 py-1 text-xs font-medium text-{{ $event->color }}-400 ring-1 ring-inset ring-{{ $event->color }}-400/30">
                                                    {{ $event->type }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @empty
                <div class="text-center bg-gray-900 border border-gray-800 rounded-lg py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-white">Sin actividades</h3>
                    <p class="mt-1 text-sm text-gray-400">No hay ninguna actividad programada actualmente.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
