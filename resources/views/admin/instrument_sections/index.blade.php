<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Cuerdas y Subcuerdas de la Banda</h2>
                <p class="mt-2 text-sm text-gray-400">
                    Estructura organizativa de la banda por familias (viento madera, viento metal, percusión, etc.) y subcuerdas (clarinetes, flautas, saxofones, etc.).
                    El orden definido aquí determina la ordenación de las listas de asistencia y organización de los músicos.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 flex gap-2">
                <a href="{{ route('admin.instrument-sections.create') }}" class="inline-flex items-center gap-1.5 rounded-md bg-amber-600 px-3 py-2 text-center text-xs font-semibold text-white shadow-sm hover:bg-amber-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nueva Cuerda / Subcuerda
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 space-y-6">
        @forelse ($mainSections as $section)
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-sm">
                <!-- Cabecera de la Cuerda Principal -->
                <div class="bg-gray-850 px-6 py-4 border-b border-gray-800 flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500/20 text-amber-400 font-bold text-sm">
                            {{ $section->order_index }}
                        </span>
                        <div>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wider flex items-center gap-2">
                                {{ $section->name }}
                                @if(!$section->is_active)
                                    <span class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-0.5 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">Inactiva</span>
                                @endif
                            </h3>
                            @if($section->description)
                                <p class="text-xs text-gray-400">{{ $section->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.instrument-sections.edit', $section) }}" class="rounded bg-gray-800 px-2.5 py-1.5 text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-700">
                            Editar Cuerda
                        </a>
                        <form action="{{ route('admin.instrument-sections.destroy', $section) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar esta cuerda principal?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded bg-red-950/40 text-red-400 px-2.5 py-1.5 text-xs font-semibold hover:bg-red-900/60">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Lista de Subcuerdas -->
                <div class="p-6">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-amber-500/90 mb-3">Subcuerdas asociadas:</h4>

                    @if($section->children->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($section->children as $sub)
                                <div class="bg-gray-950/60 border border-gray-800/80 rounded-lg p-4 flex flex-col justify-between hover:border-gray-700 transition-colors">
                                    <div>
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-mono bg-gray-800 text-amber-400 px-1.5 py-0.5 rounded font-semibold">
                                                    #{{ $sub->order_index }}
                                                </span>
                                                <h5 class="text-sm font-semibold text-white">{{ $sub->name }}</h5>
                                            </div>
                                            @if(!$sub->is_active)
                                                <span class="text-[10px] text-red-400">Inactiva</span>
                                            @endif
                                        </div>

                                        <!-- Instrumentos del catálogo en esta subcuerda -->
                                        <div class="mt-2 text-xs text-gray-400">
                                            <span class="text-gray-500">Instrumentos:</span>
                                            @if($sub->instruments->count() > 0)
                                                <div class="flex flex-wrap gap-1 mt-1">
                                                    @foreach($sub->instruments as $inst)
                                                        <span class="inline-flex items-center rounded-md bg-gray-800 px-2 py-0.5 text-[11px] text-gray-300">
                                                            {{ $inst->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="italic text-gray-600">Ningún instrumento vinculado</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-3 border-t border-gray-850 flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.instrument-sections.edit', $sub) }}" class="text-xs text-amber-400 hover:text-amber-300">
                                            Editar
                                        </a>
                                        <form action="{{ route('admin.instrument-sections.destroy', $sub) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar esta subcuerda?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-400 hover:text-red-300 ml-2">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-500 italic">No hay subcuerdas definidas para esta sección todavía.</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center text-gray-400">
                <p>No se han configurado cuerdas todavía.</p>
                <a href="{{ route('admin.instrument-sections.create') }}" class="inline-block mt-4 rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-500">
                    Crear la primera cuerda
                </a>
            </div>
        @endforelse
    </div>
</x-admin-layout>
