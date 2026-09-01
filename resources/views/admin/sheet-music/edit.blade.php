<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Editar Obra: {{ $sheetMusic->title }}</h2>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.sheet-music.index') }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Volver al archivo
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 max-w-3xl">
        <form action="{{ route('admin.sheet-music.update', $sheetMusic) }}" method="POST" enctype="multipart/form-data" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            @csrf
            @method('PUT')
            
            <div class="px-4 py-6 sm:p-8">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    
                    <div class="sm:col-span-4">
                        <label for="title" class="block text-sm font-medium leading-6 text-white">Título de la Obra *</label>
                        <div class="mt-2">
                            <input type="text" name="title" id="title" value="{{ old('title', $sheetMusic->title) }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('title') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="composer" class="block text-sm font-medium leading-6 text-white">Compositor</label>
                        <div class="mt-2">
                            <input type="text" name="composer" id="composer" value="{{ old('composer', $sheetMusic->composer) }}" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('composer') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="arranger" class="block text-sm font-medium leading-6 text-white">Arreglista</label>
                        <div class="mt-2">
                            <input type="text" name="arranger" id="arranger" value="{{ old('arranger', $sheetMusic->arranger) }}" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('arranger') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="work_type" class="block text-sm font-medium leading-6 text-white">Tipo de obra</label>
                        <div class="mt-2">
                            <input list="work_types" name="work_type" id="work_type" value="{{ old('work_type', $sheetMusic->work_type) }}" placeholder="Selecciona o escribe..." class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6" style="color-scheme: dark;">
                            <datalist id="work_types">
                                @php
                                    $defaultTypes = ['Pasodoble', 'Marcha Procesión', 'Marcha Fúnebre', 'Marcha Militar', 'Himno', 'Ópera', 'Zarzuela', 'Rock', 'Pop', 'Soul', 'Jazz'];
                                    $existingTypes = \App\Models\SheetMusic::whereNotNull('work_type')->where('work_type', '!=', '')->distinct()->pluck('work_type')->toArray();
                                    $allTypes = array_unique(array_merge($defaultTypes, $existingTypes));
                                    sort($allTypes);
                                @endphp
                                @foreach($allTypes as $wt)
                                    <option value="{{ $wt }}">
                                @endforeach
                            </datalist>
                        </div>
                        @error('work_type') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="pdf_file" class="block text-sm font-medium leading-6 text-white">Reemplazar Archivo PDF (Guión General / Partitura Completa)</label>
                        
                        @if($sheetMusic->pdf_file_path)
                            <div class="mt-2 mb-3">
                                <span class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-400/10 px-2 py-1 text-sm font-medium text-blue-400">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                    Archivo actual subido
                                </span>
                            </div>
                        @endif

                        <div class="mt-2">
                            <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500 cursor-pointer">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Sube un nuevo PDF solo si quieres reemplazar el actual. Tamaño máximo: 20MB.</p>
                        @error('pdf_file') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6 flex flex-col border-t border-gray-800 pt-6 mt-2">
                        <div class="flex items-center mb-4">
                            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $sheetMusic->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-blue-600 focus:ring-blue-600 focus:ring-offset-gray-900">
                            <label for="is_active" class="ml-3 block text-sm font-medium leading-6 text-white">Obra Activa (Visible en el archivo general)</label>
                        </div>
                        
                        <div>
                            <label for="leave_reason" class="block text-sm font-medium leading-6 text-white">Motivo de Baja (Si está inactiva)</label>
                            <div class="mt-2">
                                <input list="sheet_leave_reasons" name="leave_reason" id="leave_reason" value="{{ old('leave_reason', $sheetMusic->leave_reason) }}" placeholder="Selecciona o escribe un motivo..." class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6" style="color-scheme: dark;">
                                <datalist id="sheet_leave_reasons">
                                    <option value="Retirada del repertorio">
                                    <option value="Partitura ilegible / Dañada">
                                    <option value="Sustituida por nueva edición">
                                    <option value="Error en la instrumentación">
                                </datalist>
                            </div>
                            @error('leave_reason') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-6 border-t border-gray-800 pt-6 mt-2">
                        <label class="block text-xl font-semibold leading-6 text-white mb-4">Archivos por Instrumento y Tipo</label>
                        <div class="rounded-md bg-blue-900/50 p-4 mb-6 border border-blue-800">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1 md:flex md:justify-between">
                                    <p class="text-sm text-blue-300">
                                        <strong>Consejo:</strong> El sistema mantiene tu sesión activa automáticamente mientras estés en esta página. Sin embargo, si vas a subir muchos archivos, es recomendable <strong>guardar periódicamente</strong> (pulsando Actualizar) para evitar que el tamaño total del envío supere el límite del servidor, lo que causaría un error 419 (Página caducada).
                                    </p>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-400 mb-6">Selecciona el instrumento y asigna el archivo PDF o Imagen correspondiente a cada tipo. Si subes imágenes, se les aplicará automáticamente una marca de agua.</p>
                        
                        <div class="space-y-6">
                            @foreach($instruments as $instrument)
                                <div class="bg-gray-800/50 rounded-lg p-4 border border-gray-700" x-data="{ expanded: false }">
                                    <div class="flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
                                        <h4 class="text-lg font-medium text-gray-200">{{ $instrument->name }}</h4>
                                        <svg class="h-5 w-5 text-gray-400 transform transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                    
                                    <div x-show="expanded" x-transition class="mt-4 border-t border-gray-700 pt-4 grid grid-cols-1 md:grid-cols-2 gap-4" style="display: none;">
                                        @foreach(['1º', '2º', '3º', 'PRINCIPAL', 'TODOS'] as $tipo)
                                            @php
                                                $pivot = isset($filesIndexed[$instrument->id][$tipo]) ? $filesIndexed[$instrument->id][$tipo] : null;
                                            @endphp
                                            <div class="bg-gray-900 p-3 rounded border border-gray-700">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-sm font-semibold text-gray-300">Tipo: <span class="text-amber-500">{{ $tipo }}</span></span>
                                                    @if($pivot)
                                                        <a href="{{ route('admin.sheet-music.download-part', $pivot->id) }}" target="_blank" class="text-xs text-blue-400 hover:text-blue-300 flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                            Ver archivo
                                                        </a>
                                                    @else
                                                        <span class="text-xs text-gray-500">Sin archivo</span>
                                                    @endif
                                                </div>
                                                
                                                <div class="mt-2">
                                                    <input type="file" name="files[{{ $instrument->id }}][{{ $tipo }}]" accept=".pdf,.jpg,.jpeg,.png,.bmp,.webp" class="block w-full text-xs text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600">
                                                </div>
                                                
                                                @if($pivot)
                                                    <div class="mt-3 flex items-center">
                                                        <input id="delete_{{ $instrument->id }}_{{ $tipo }}" name="delete_files[{{ $instrument->id }}][{{ $tipo }}]" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-red-600 focus:ring-red-600">
                                                        <label for="delete_{{ $instrument->id }}_{{ $tipo }}" class="ml-2 block text-xs font-medium text-red-400">Eliminar archivo actual</label>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-800 px-4 py-4 sm:px-8">
                <a href="{{ route('admin.sheet-music.index') }}" class="text-sm font-semibold leading-6 text-white">Cancelar</a>
                <button type="submit" class="rounded-md bg-blue-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                    Actualizar Partitura
                </button>
            </div>
        </form>
    </div>
    
    <script>
        // Mantener la sesión activa haciendo una pequeña petición cada 15 minutos
        setInterval(function() {
            fetch('{{ route('admin.dashboard') }}', { 
                method: 'GET', 
                headers: { 'X-Requested-With': 'XMLHttpRequest' } 
            }).catch(e => console.log('Keep-alive ping failed'));
        }, 15 * 60 * 1000);
    </script>
</x-admin-layout>
