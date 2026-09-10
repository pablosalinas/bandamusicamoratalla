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
        document.addEventListener('DOMContentLoaded', function() {
            let formChanged = false;
            let isUserActive = true;
            let keepAliveInterval;

            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('change', () => formChanged = true);
                
                ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(evt => {
                    document.addEventListener(evt, () => isUserActive = true);
                });

                keepAliveInterval = setInterval(() => {
                    if (formChanged && isUserActive) {
                        fetch(window.location.href, { method: 'HEAD' }).catch(() => {});
                        isUserActive = false;
                    }
                }, 15 * 60 * 1000);
            }

            // Detección y creación de nuevos instrumentos
            let missingCandidates = {};
            
            function guessFamily(name) {
                let lower = name.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                if (lower.includes('sax') || lower.includes('flaut') || lower.includes('oboe') || lower.includes('fagot') || lower.includes('clarinete') || lower.includes('requinto') || lower.includes('corno ingles')) return 'VIENTO MADERA';
                if (lower.includes('tromp') || lower.includes('tromb') || lower.includes('tuba') || lower.includes('bombardino') || lower.includes('fliscorno') || lower.includes('corno')) return 'VIENTO METAL';
                if (lower.includes('viol') || lower.includes('contra') || lower.includes('arpa') || lower.includes('guitarra') || lower.includes('bajo') || lower.includes('cello')) return 'CUERDA';
                if (lower.includes('piano') || lower.includes('teclad') || lower.includes('sinteti')) return 'TECLA';
                return 'PERCUSIÓN';
            }
            
            function appendInstrumentRow(inst) {
                const grid = document.getElementById('instruments_grid');
                if (!grid) return;
                const div = document.createElement('div');
                div.id = 'card_' + inst.id;
                div.className = 'relative flex items-start space-x-3 rounded-lg border border-indigo-500 bg-indigo-900/40 px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 ring-1 ring-indigo-500 hover:border-gray-600 transition-colors';
                
                let selectHtml = '<select onchange="document.getElementById(\\\'file_' + inst.id + '\\\').name=\\\'files[' + inst.id + '][\\\' + this.value + \\\']\\\'" id="type_' + inst.id + '" class="mt-2 block w-full rounded-md border-0 bg-gray-900 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6 relative z-10">';
                selectHtml += '<option value="TODOS">TODOS (Por defecto)</option>';
                selectHtml += '<option value="1º">1º</option>';
                selectHtml += '<option value="2º">2º</option>';
                selectHtml += '<option value="3º">3º</option>';
                selectHtml += '<option value="PRINCIPAL">PRINCIPAL / SOLISTA</option>';
                selectHtml += '</select>';

                div.innerHTML = `
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-white mb-2 flex items-center justify-between">
                            <span>${inst.name}</span>
                            <span class="inline-flex items-center rounded-md bg-blue-500/10 px-2 py-1 text-xs font-medium text-blue-400 ring-1 ring-inset ring-blue-500/20">Nuevo</span>
                        </p>
                        <div class="mt-2 relative z-10">
                            <input type="file" name="files[${inst.id}][TODOS]" id="file_${inst.id}" accept=".pdf,image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-indigo-400 hover:file:bg-gray-600">
                        </div>
                        ${selectHtml}
                        <p id="label_${inst.id}" class="mt-2 text-xs text-gray-400"><span class="text-green-400 font-medium">✅ Archivos Locales Asignados</span></p>
                    </div>
                `;
                grid.appendChild(div);
            }

            const folderUpload = document.getElementById('smart_folder_upload');
            if (folderUpload) {
                folderUpload.addEventListener('change', function(e) {
                    const files = e.target.files;
                    if (files.length === 0) return;
                    
                    const resultsDiv = document.getElementById('smart_upload_results');
                    const logUl = document.getElementById('smart_upload_log');
                    resultsDiv.classList.remove('hidden');
                    logUl.innerHTML = '';
                    missingCandidates = {};
                    
                    const instruments = [
                        @foreach($instruments as $inst)
                            { id: {{ $inst->id }}, name: "{{ $inst->name }}", originalName: "{{ $inst->name }}" },
                        @endforeach
                    ];
                    
                    let matchedCount = 0;
                    let skippedCount = 0;
                    
                    const sortedInstruments = [...instruments].sort((a, b) => b.name.length - a.name.length);
                    
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        const filename = file.name.toLowerCase();
                        
                        if (filename.startsWith('.') || (!filename.endsWith('.pdf') && !filename.match(/\.(jpg|jpeg|png|bmp|webp)$/))) {
                            continue;
                        }
                        
                        const fileNormalized = filename.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]/g, ' ').trim();
                        
                        let matchedInstrumentsArr = [];
                        let matchedAliases = [];
                        
                        for (const inst of sortedInstruments) {
                            const instNormalized = inst.name.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]/g, ' ').trim();
                            
                            let instAliases = [instNormalized];
                            if (instNormalized.includes('saxofon') || instNormalized.includes('saxo')) {
                                instAliases.push(instNormalized.replace('saxofon', 'saxo'));
                                instAliases.push(instNormalized.replace('saxofon', 'sax'));
                                if (instNormalized.includes('alto')) {
                                    instAliases.push(instNormalized.replace('alto', 'contralto'));
                                    instAliases.push('contralto');
                                }
                                if (instNormalized.includes('baritono')) instAliases.push('baritono');
                                if (instNormalized.includes('tenor')) instAliases.push('tenor');
                                if (instNormalized.includes('soprano')) instAliases.push('soprano');
                            }
                            if (instNormalized.includes('flautin')) {
                                instAliases.push(instNormalized.replace('flautin', 'piccolo'));
                                instAliases.push(instNormalized.replace('flautin', 'fluati'));
                            }
                            if (instNormalized.includes('trompa')) {
                                instAliases.push(instNormalized.replace('trompa', 'corno'));
                                instAliases.push(instNormalized.replace('trompa', 'horn'));
                                instAliases.push(instNormalized.replace('trompa', 'tompa'));
                            }
                            if (instNormalized.includes('bombardino')) {
                                instAliases.push(instNormalized.replace('bombardino', 'bombardin'));
                                instAliases.push(instNormalized.replace('bombardino', 'eufonio'));
                                instAliases.push(instNormalized.replace('bombardino', 'euphonium'));
                            }
                            if (instNormalized.includes('trombon')) {
                                instAliases.push(instNormalized.replace('trombon', 'trombo'));
                                instAliases.push('trombo');
                            }
                            if (instNormalized.includes('timbales')) {
                                instAliases.push(instNormalized.replace('timbales', 'timbals'));
                            }
                            if (instNormalized.includes('fliscorno')) {
                                instAliases.push(instNormalized.replace('fliscorno', 'friscor'));
                            }
                            if (instNormalized.includes('requinto')) {
                                instAliases.push(instNormalized.replace('requinto', 'requin'));
                            }
                            if (instNormalized.includes('saxofon alto') || instNormalized.includes('saxo alto')) {
                                instAliases.push('saxo alt');
                            }
                            if (instNormalized.includes('tuba')) {
                                if (instNormalized.includes('do')) instAliases.push('tuba'); 
                            }
                            // Traducciones desde catalán / otros
                            if (instNormalized.includes('clarinete')) {
                                instAliases.push(instNormalized.replace('clarinete', 'clarinet'));
                            }
                            if (instNormalized.includes('violonchelo')) {
                                instAliases.push(instNormalized.replace('violonchelo', 'violoncel'));
                                instAliases.push(instNormalized.replace('violonchelo', 'cello'));
                            }
                            if (instNormalized.includes('contrabajo')) {
                                instAliases.push(instNormalized.replace('contrabajo', 'contrabaix'));
                            }
                            if (instNormalized.includes('fliscorno')) {
                                instAliases.push(instNormalized.replace('fliscorno', 'fiscorn'));
                            }
                            if (instNormalized.includes('platillos')) {
                                instAliases.push(instNormalized.replace('platillos', 'plats'));
                                instAliases.push(instNormalized.replace('platillos', 'platerets'));
                            }
                            if (instNormalized.includes('caja')) {
                                instAliases.push(instNormalized.replace('caja', 'caixa'));
                            }
                            
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
                                    if (alias.match(map.es)) {
                                        expandedAliases.push(alias.replace(map.es, map.en_str).replace(/\ben\b/g, '').replace(/\s+/g, ' ').trim());
                                    } else if (alias.match(map.en)) {
                                        expandedAliases.push(alias.replace(map.en, map.es_str).replace(/\ben\b/g, '').replace(/\s+/g, ' ').trim());
                                    }
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
                                        let regex = new RegExp('\\b' + p + '\\b');
                                        if (!regex.test(fileNormalized)) { allPartsFound = false; break; }
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
                                        if (!existingParts.includes(p)) {
                                            allContained = false;
                                            break;
                                        }
                                    }
                                    if (allContained) {
                                        isSubset = true;
                                        break;
                                    }
                                }
                                
                                if (!isSubset) {
                                    matchedInstrumentsArr.push(inst);
                                    matchedAliases.push(foundAlias);
                                }
                            }
                        }
                        
                                                let matchedType = "TODOS"; 
                        if (fileNormalized.match(/(?:^|\s)1(?:st|o|a|er|\s|$)/) || fileNormalized.includes("primero") || fileNormalized.includes("primera")) {
                            matchedType = "1º";
                        } else if (fileNormalized.match(/(?:^|\s)2(?:nd|o|a|do|\s|$)/) || fileNormalized.includes("segundo") || fileNormalized.includes("segunda")) {
                            matchedType = "2º";
                        } else if (fileNormalized.match(/(?:^|\s)3(?:rd|o|a|er|\s|$)/) || fileNormalized.includes("tercero") || fileNormalized.includes("tercera")) {
                            matchedType = "3º";
                        } else if (fileNormalized.includes("principal") || fileNormalized.includes("pral") || fileNormalized.match(/\bsolo\b/)) {
                            matchedType = "PRINCIPAL";
                        }
                        
                        let uuid = "file_" + Math.random().toString(36).substr(2, 9);
                        let tr = document.createElement("tr");
                        
                        let tdFile = document.createElement("td");
                        tdFile.className = "py-3 pl-3 pr-3 text-xs font-medium text-white break-all";
                        tdFile.innerHTML = "\n" +
                            file.name + "\n" +
                            "<input type=\"file\" id=\"input_" + uuid + "\" name=\"smart_grid_files[" + uuid + "]\" class=\"hidden\">\n";
                        tr.appendChild(tdFile);
                        
                        for(let col = 0; col < 3; col++) {
                            let td = document.createElement("td");
                            td.className = "py-2 px-2";
                            
                            let instName = "";
                            let typeName = "";
                            
                            if (matchedInstrumentsArr[col]) {
                                instName = matchedInstrumentsArr[col].originalName;
                                typeName = matchedType;
                            }
                            
                            td.innerHTML = "\n" +
                                "<div class=\"flex flex-col gap-1\">\n" +
                                    "<input type=\"text\" list=\"instrument_catalog_list\" name=\"smart_grid_instruments[" + uuid + "][]\" value=\"" + instName + "\" class=\"bg-gray-800 text-xs text-white rounded border border-gray-600 px-2 py-1.5 w-full focus:ring-indigo-500 focus:border-indigo-500\" placeholder=\"Escribir...\">\n" +
                                    "<select name=\"smart_grid_types[" + uuid + "][]\" class=\"bg-gray-800 text-xs text-white rounded border border-gray-600 px-2 py-1 w-full focus:ring-indigo-500 focus:border-indigo-500\">\n" +
                                        "<option value=\"\">- Tipo -</option>\n" +
                                        "<option value=\"TODOS\" " + (typeName==='TODOS'?'selected':'') + ">TODOS</option>\n" +
                                        "<option value=\"1º\" " + (typeName==='1º'?'selected':'') + ">1º</option>\n" +
                                        "<option value=\"2º\" " + (typeName==='2º'?'selected':'') + ">2º</option>\n" +
                                        "<option value=\"3º\" " + (typeName==='3º'?'selected':'') + ">3º</option>\n" +
                                        "<option value=\"PRINCIPAL\" " + (typeName==='PRINCIPAL'?'selected':'') + ">PRINCIPAL</option>\n" +
                                    "</select>\n" +
                                "</div>\n";
                            tr.appendChild(td);
                        }
                        
                        document.getElementById("smart_grid_container").classList.remove("hidden");
                        document.getElementById("smart_grid_body").appendChild(tr);
                        
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        document.getElementById("input_" + uuid).files = dt.files;
                    }
                });
            }
        });
    </script>
</x-admin-layout>
