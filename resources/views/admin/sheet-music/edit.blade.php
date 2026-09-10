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
        <form id="sheet-music-form" action="{{ route('admin.sheet-music.update', $sheetMusic) }}" method="POST" enctype="multipart/form-data" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
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

                        <!-- Smart Assignment Section -->
                        <div class="mb-6 bg-gray-800/80 border border-gray-700 rounded-lg p-5">
                            <div class="flex items-start gap-4">
                                <div class="p-2 bg-blue-500/20 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-white mb-1">Asignación Inteligente por Carpeta</h3>
                                    <p class="text-sm text-gray-400 mb-4">Selecciona una carpeta local de tu ordenador que contenga las partituras sueltas. El sistema analizará los nombres de archivo para deducir de forma automática el instrumento y la categoría, rellenando los campos de abajo por ti.</p>
                                    
                                    <input type="file" id="smart_folder_upload" webkitdirectory directory multiple class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500 cursor-pointer">
                                    
                                    <datalist id="instrument_catalog_list">
                                        @foreach($instruments as $inst)
                                            <option value="{{ $inst->name }}">
                                        @endforeach
                                    </datalist>

                                    <div id="smart_grid_container" class="mt-4 hidden border-t border-gray-700 pt-3 overflow-x-auto pb-4">
                                        <h4 class="text-sm font-medium text-amber-400 mb-2">Cuadrícula de Asignación de Archivos:</h4>
                                        <p class="text-xs text-gray-400 mb-3">Revisa las asignaciones automáticas. Puedes añadir o modificar instrumentos y tipos. Escribe el nombre de un instrumento existente o uno nuevo (se creará automáticamente en MAYÚSCULAS).</p>
                                        
                                        <div class="inline-block min-w-full align-middle">
                                            <table class="min-w-full divide-y divide-gray-700">
                                                <thead class="bg-gray-900/80">
                                                    <tr>
                                                        <th scope="col" class="py-2.5 pl-3 pr-3 text-left text-xs font-medium text-gray-300 w-1/4">Archivo detectado</th>
                                                        <th scope="col" class="py-2.5 px-3 text-left text-xs font-medium text-gray-300 w-1/4">Asignación 1</th>
                                                        <th scope="col" class="py-2.5 px-3 text-left text-xs font-medium text-gray-300 w-1/4">Asignación 2</th>
                                                        <th scope="col" class="py-2.5 px-3 text-left text-xs font-medium text-gray-300 w-1/4">Asignación 3</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="smart_grid_body" class="divide-y divide-gray-800 bg-gray-900/30">
                                                    <!-- Fila template generada por JS -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="text-sm text-gray-400 mb-6">Selecciona el instrumento y asigna el archivo PDF o Imagen correspondiente a cada tipo. Si subes imágenes, se les aplicará automáticamente una marca de agua.</p>
                        
                        <div class="space-y-6">
                            @foreach($instruments as $instrument)
                                @php
                                    $assignedTypes = isset($filesIndexed[$instrument->id]) ? array_keys($filesIndexed[$instrument->id]) : [];
                                    $hasFiles = count($assignedTypes) > 0;
                                @endphp
                                <div id="existing_card_{{ $instrument->id }}" class="{{ $hasFiles ? 'bg-blue-900/40 border-blue-700' : 'bg-gray-800/50 border-gray-700' }} rounded-lg p-4 border transition-colors" x-data="{ expanded: false }">
                                    <div class="flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
                                        <div class="flex items-center gap-3">
                                            <h4 class="text-lg font-medium {{ $hasFiles ? 'text-blue-100' : 'text-gray-200' }}">{{ $instrument->name }}</h4>
                                            @if($hasFiles)
                                                <div class="flex gap-1 flex-wrap">
                                                    @foreach($assignedTypes as $type)
                                                        <span class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-0.5 text-xs font-medium text-blue-400 ring-1 ring-inset ring-blue-400/30">{{ $type }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <svg class="h-5 w-5 {{ $hasFiles ? 'text-blue-300' : 'text-gray-400' }} transform transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                                    <input type="file" id="file_{{ $instrument->id }}_{{ $tipo }}" name="files[{{ $instrument->id }}][{{ $tipo }}]" accept=".pdf,.jpg,.jpeg,.png,.bmp,.webp" class="block w-full text-xs text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600">
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
    document.addEventListener('alpine:init', () => {
        // Inicializar alpine si hace falta
    });

    document.addEventListener('DOMContentLoaded', function() {
        let existingFiles = {};
        @foreach( as  => )
            existingFiles["{{  }}"] = {};
            @foreach( as  => )
                existingFiles["{{  }}"]["{{  }}"] = true;
            @endforeach
        @endforeach
        
        let existingNames = {};
        @foreach( as )
            existingNames["{{ ->name }}"] = "{{ ->id }}";
        @endforeach

        const folderUpload = document.getElementById('smart_folder_upload');
        if (folderUpload) {
            folderUpload.addEventListener('change', function(e) {
                const files = e.target.files;
                if (files.length === 0) return;
                
                const tbody = document.getElementById('smart_grid_body');
                tbody.innerHTML = '';
                
                const instruments = [
                    @foreach( as )
                        { id: {{ ->id }}, name: "{{ ->name }}", originalName: "{{ ->name }}" },
                    @endforeach
                ];
                
                const sortedInstruments = [...instruments].sort((a, b) => b.name.length - a.name.length);
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    let fileNormalized = file.name.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
                    
                    let matchedInstrumentsArr = [];
                    let matchedAliases = [];
                    
                    for (const inst of sortedInstruments) {
                        let instNormalized = inst.name.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
                        let instAliases = [instNormalized];
                        
                        if (instNormalized.includes('trompa')) instAliases.push(instNormalized.replace('trompa', 'tompa'));
                        if (instNormalized.includes('bombardino')) {
                            instAliases.push(instNormalized.replace('bombardino', 'bombardin'));
                            instAliases.push(instNormalized.replace('bombardino', 'eufonio'));
                            instAliases.push(instNormalized.replace('bombardino', 'euphonium'));
                        }
                        if (instNormalized.includes('tuba') && instNormalized.includes('do')) instAliases.push('tuba'); 
                        
                        if (instNormalized.includes('clarinete')) instAliases.push(instNormalized.replace('clarinete', 'clarinet'));
                        if (instNormalized.includes('violonchelo')) {
                            instAliases.push(instNormalized.replace('violonchelo', 'violoncel'));
                            instAliases.push(instNormalized.replace('violonchelo', 'cello'));
                        }
                        if (instNormalized.includes('contrabajo')) instAliases.push(instNormalized.replace('contrabajo', 'contrabaix'));
                        if (instNormalized.includes('fliscorno')) instAliases.push(instNormalized.replace('fliscorno', 'fiscorn'));
                        if (instNormalized.includes('platillos')) {
                            instAliases.push(instNormalized.replace('platillos', 'plats'));
                            instAliases.push(instNormalized.replace('platillos', 'platerets'));
                        }
                        if (instNormalized.includes('caja')) instAliases.push(instNormalized.replace('caja', 'caixa'));
                        
                        const toneMappings = [
                            { es: /\b(do)\b/g, en: /\b(c)\b/g, es_str: 'do', en_str: 'c' },
                            { es: /\b(re)\b/g, en: /\b(d)\b/g, es_str: 're', en_str: 'd' },
                            { es: /\b(mi\s*b|mib|mi\s*bemol)\b/g, en: /\b(eb|e\s*flat|e\s*b)\b/g, es_str: 'mib', en_str: 'eb' },
                            { es: /\b(mi)\b/g, en: /\b(e)\b/g, es_str: 'mi', en_str: 'e' },
                            { es: /\b(fa)\b/g, en: /\b(f)\b/g, es_str: 'fa', en_str: 'f' },
                            { es: /\b(sol)\b/g, en: /\b(g)\b/g, es_str: 'sol', en_str: 'g' },
                            { es: /\b(la\s*b|lab|la\s*bemol)\b/g, en: /\b(ab|a\s*flat|a\s*b)\b/g, es_str: 'lab', en_str: 'ab' },
                            { es: /\b(la)\b/g, en: /\b(a)\b/g, es_str: 'la', en_str: 'a' },
                            { es: /\b(si\s*b|sib|si\s*bemol)\b/g, en: /\b(bb|b\s*flat|b\s*b)\b/g, es_str: 'sib', en_str: 'bb' },
                            { es: /\b(si)\b/g, en: /\b(b)\b/g, es_str: 'si', en_str: 'b' }
                        ];
    
                        let expandedAliases = [];
                        for (let alias of instAliases) {
                            expandedAliases.push(alias);
                            let noEn = alias.replace(/\ben\b/g, '').replace(/\s+/g, ' ').trim();
                            if (noEn !== alias) expandedAliases.push(noEn);
                            for (let map of toneMappings) {
                                if (alias.match(map.es)) expandedAliases.push(alias.replace(map.es, map.en_str).replace(/\ben\b/g, '').replace(/\s+/g, ' ').trim());
                                else if (alias.match(map.en)) expandedAliases.push(alias.replace(map.en, map.es_str).replace(/\ben\b/g, '').replace(/\s+/g, ' ').trim());
                            }
                        }
                        
                        let found = false;
                        let foundAlias = '';
                        for (let alias of expandedAliases) {
                            alias = alias.replace(/\s+/g, ' ').trim();
                            if (fileNormalized.includes(alias)) {
                                found = true; foundAlias = alias; break;
                            }
                            let parts = alias.split(' ');
                            if (parts.length > 1) {
                                let allPartsFound = true;
                                for (let p of parts) {
                                    if (!new RegExp('\\b' + p + '\\b').test(fileNormalized)) { allPartsFound = false; break; }
                                }
                                if (allPartsFound) { found = true; foundAlias = alias; break; }
                            }
                        }
                        
                        if (found) {
                            let isSubset = false;
                            for (let existingAlias of matchedAliases) {
                                let foundParts = foundAlias.split(' ');
                                let existingParts = existingAlias.split(' ');
                                let allContained = true;
                                for (let p of foundParts) {
                                    if (!existingParts.includes(p)) { allContained = false; break; }
                                }
                                if (allContained) { isSubset = true; break; }
                            }
                            if (!isSubset) {
                                matchedInstrumentsArr.push(inst);
                                matchedAliases.push(foundAlias);
                            }
                        }
                    }
                    
                    let matchedType = 'TODOS'; 
                    if (fileNormalized.match(/(?:^|\s)1(?:st|o|a|er|\s|$)/) || fileNormalized.includes('primero') || fileNormalized.includes('primera')) matchedType = '1º';
                    else if (fileNormalized.match(/(?:^|\s)2(?:nd|o|a|do|\s|$)/) || fileNormalized.includes('segundo') || fileNormalized.includes('segunda')) matchedType = '2º';
                    else if (fileNormalized.match(/(?:^|\s)3(?:rd|o|a|er|\s|$)/) || fileNormalized.includes('tercero') || fileNormalized.includes('tercera')) matchedType = '3º';
                    else if (fileNormalized.match(/(?:^|\s)4(?:th|o|a|to|\s|$)/) || fileNormalized.includes('cuarto') || fileNormalized.includes('cuarta')) matchedType = '4º';
                    else if (fileNormalized.includes('principal') || fileNormalized.includes('pral') || fileNormalized.match(/\bsolo\b/)) matchedType = 'PRINCIPAL';
                    
                    let isAlreadyUploaded = false;
                    for (let col = 0; col < matchedInstrumentsArr.length; col++) {
                        let instName = matchedInstrumentsArr[col].originalName;
                        let instId = existingNames[instName];
                        if (instId && existingFiles[instId] && existingFiles[instId][matchedType]) {
                            isAlreadyUploaded = true;
                            break;
                        }
                    }

                    let uuid = 'file_' + Math.random().toString(36).substr(2, 9);
                    let tr = document.createElement('tr');
                    
                    if (isAlreadyUploaded) {
                        tr.className = "bg-green-900/30 border-l-4 border-green-500";
                    } else {
                        tr.className = "border-l-4 border-transparent";
                    }
                    
                    let tdFile = document.createElement('td');
                    tdFile.className = "py-3 pl-3 pr-3 text-xs font-medium text-white break-all border-b border-gray-700";
                    tdFile.innerHTML = file.name + '<input type="file" id="input_' + uuid + '" name="smart_grid_files[' + uuid + ']" class="hidden smart-file-input" data-uuid="' + uuid + '">';
                    tr.appendChild(tdFile);
                    
                    for(let col = 0; col < 3; col++) {
                        let td = document.createElement('td');
                        td.className = "py-2 px-2 border-b border-gray-700";
                        
                        let instName = '';
                        let typeName = '';
                        if (matchedInstrumentsArr[col]) {
                            instName = matchedInstrumentsArr[col].originalName;
                            typeName = matchedType;
                        }
                        
                        td.innerHTML = '<div class="flex flex-col gap-1">' +
                            '<input type="text" list="instrument_catalog_list" name="smart_grid_instruments[' + uuid + '][]" value="' + instName + '" class="bg-gray-800 text-xs text-white rounded border border-gray-600 px-2 py-1.5 w-full focus:ring-indigo-500 focus:border-indigo-500" placeholder="Escribir...">' +
                            '<select name="smart_grid_types[' + uuid + '][]" class="bg-gray-800 text-xs text-white rounded border border-gray-600 px-2 py-1 w-full focus:ring-indigo-500 focus:border-indigo-500">' +
                                '<option value="">- Tipo -</option>' +
                                '<option value="TODOS" ' + (typeName==='TODOS'?'selected':'') + '>TODOS</option>' +
                                '<option value="1º" ' + (typeName==='1º'?'selected':'') + '>1º</option>' +
                                '<option value="2º" ' + (typeName==='2º'?'selected':'') + '>2º</option>' +
                                '<option value="3º" ' + (typeName==='3º'?'selected':'') + '>3º</option>' +
                                '<option value="4º" ' + (typeName==='4º'?'selected':'') + '>4º</option>' +
                                '<option value="PRINCIPAL" ' + (typeName==='PRINCIPAL'?'selected':'') + '>PRINCIPAL</option>' +
                            '</select>' +
                        '</div>';
                        tr.appendChild(td);
                    }
                    
                    document.getElementById('smart_grid_container').classList.remove('hidden');
                    tbody.appendChild(tr);
                    
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    document.getElementById('input_' + uuid).files = dt.files;
                }
            });
        }

        const mainForm = document.querySelector('form');
        const submitBtn = mainForm.querySelector('button[type="submit"]');
        
        mainForm.addEventListener('submit', async function(e) {
            const gridFiles = document.querySelectorAll('.smart-file-input');
            let filesToUpload = [];
            gridFiles.forEach(input => {
                if (input.files.length > 0) {
                    filesToUpload.push(input);
                }
            });
            
            if (filesToUpload.length > 0) {
                e.preventDefault();
                submitBtn.disabled = true;
                
                const originalText = submitBtn.innerText;
                let current = 0;
                let total = filesToUpload.length;
                
                for (let input of filesToUpload) {
                    current++;
                    submitBtn.innerText = "Subiendo archivo " + current + " de " + total + " (pausa 3s)...";
                    
                    let uuid = input.getAttribute('data-uuid');
                    let formData = new FormData();
                    formData.append('_token', document.querySelector('input[name="_token"]').value);
                    formData.append('file', input.files[0]);
                    
                    let instInputs = document.querySelectorAll('input[name="smart_grid_instruments[' + uuid + '][]"]');
                    let typeInputs = document.querySelectorAll('select[name="smart_grid_types[' + uuid + '][]"]');
                    
                    let insts = [];
                    let types = [];
                    instInputs.forEach(el => insts.push(el.value));
                    typeInputs.forEach(el => types.push(el.value));
                    
                    formData.append('instruments', JSON.stringify(insts));
                    formData.append('types', JSON.stringify(types));
                    
                    try {
                        let response = await fetch("{{ route('admin.sheet-music.upload-grid-row-ajax', $sheetMusic) }}", {
                            method: "POST",
                            body: formData
                        });
                        
                        if (!response.ok) {
                            console.error("Error subiendo", input.files[0].name);
                        }
                    } catch(err) {
                        console.error(err);
                    }
                    
                    input.value = ''; 
                    
                    if (current < total) {
                        await new Promise(r => setTimeout(r, 3000));
                    }
                }
                
                submitBtn.innerText = "Guardando formulario principal...";
                mainForm.submit();
            }
        });
    });
</script>

</x-admin-layout>
