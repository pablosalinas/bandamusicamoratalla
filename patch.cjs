const fs = require('fs');
let content = fs.readFileSync('resources/views/admin/sheet-music/edit.blade.php', 'utf8');

const newScript = `
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
                let lower = name.toLowerCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '');
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
                
                let selectHtml = '<select name=\"types[' + inst.id + ']\" id=\"type_' + inst.id + '\" class=\"mt-2 block w-full rounded-md border-0 bg-gray-900 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6 relative z-10\">';
                selectHtml += '<option value=\"TODOS\">TODOS (Por defecto)</option>';
                selectHtml += '<option value=\"1º\">1º</option>';
                selectHtml += '<option value=\"2º\">2º</option>';
                selectHtml += '<option value=\"3º\">3º</option>';
                selectHtml += '<option value=\"PRINCIPAL\">PRINCIPAL / SOLISTA</option>';
                selectHtml += '</select>';

                div.innerHTML = \`
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-white mb-2 flex items-center justify-between">
                            <span>\${inst.name}</span>
                            <span class="inline-flex items-center rounded-md bg-blue-500/10 px-2 py-1 text-xs font-medium text-blue-400 ring-1 ring-inset ring-blue-500/20">Nuevo</span>
                        </p>
                        <div class="mt-2 relative z-10">
                            <input type="file" name="files[\${inst.id}]" id="file_\${inst.id}" accept=".pdf,image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-indigo-400 hover:file:bg-gray-600">
                        </div>
                        \${selectHtml}
                        <p id="label_\${inst.id}" class="mt-2 text-xs text-gray-400"><span class="text-green-400 font-medium">✅ Archivos Locales Asignados</span></p>
                    </div>
                \`;
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
                        
                        if (filename.startsWith('.') || (!filename.endsWith('.pdf') && !filename.match(/\\.(jpg|jpeg|png|bmp|webp)$/))) {
                            continue;
                        }
                        
                        const fileNormalized = filename.normalize('NFD').replace(/[\\u0300-\\u036f]/g, '').replace(/[^a-z0-9]/g, ' ').trim();
                        
                        let matchedInstrumentsArr = [];
                        let matchedAliases = [];
                        
                        for (const inst of sortedInstruments) {
                            const instNormalized = inst.name.toLowerCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '').replace(/[^a-z0-9]/g, ' ').trim();
                            
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
                            }
                            if (instNormalized.includes('trompa')) {
                                instAliases.push(instNormalized.replace('trompa', 'corno'));
                                instAliases.push(instNormalized.replace('trompa', 'horn'));
                            }
                            if (instNormalized.includes('bombardino')) {
                                instAliases.push(instNormalized.replace('bombardino', 'eufonio'));
                                instAliases.push(instNormalized.replace('bombardino', 'euphonium'));
                            }
                            if (instNormalized.includes('tuba')) {
                                if (instNormalized.includes('do')) instAliases.push('tuba'); 
                            }
                            
                            const toneMappings = [
                                { es: /\\b(do)\\b/g, en: /\\b(c)\\b/g, es_str: 'do', en_str: 'c' },
                                { es: /\\b(re)\\b/g, en: /\\b(d)\\b/g, es_str: 're', en_str: 'd' },
                                { es: /\\b(mi\\s*b|mib|mi\\s*bemol)\\b/g, en: /\\b(eb|e\\s*flat|e\\s*b)\\b/g, es_str: 'mib', en_str: 'eb' },
                                { es: /\\b(mi)\\b/g, en: /\\b(e)\\b/g, es_str: 'mi', en_str: 'e' },
                                { es: /\\b(fa)\\b/g, en: /\\b(f)\\b/g, es_str: 'fa', en_str: 'f' },
                                { es: /\\b(sol)\\b/g, en: /\\b(g)\\b/g, es_str: 'sol', en_str: 'g' },
                                { es: /\\b(la\\s*b|lab|la\\s*bemol)\\b/g, en: /\\b(ab|a\\s*flat|a\\s*b)\\b/g, es_str: 'lab', en_str: 'ab' },
                                { es: /\\b(la)\\b/g, en: /\\b(a)\\b/g, es_str: 'la', en_str: 'a' },
                                { es: /\\b(si\\s*b|sib|si\\s*bemol)\\b/g, en: /\\b(bb|b\\s*flat|b\\s*b)\\b/g, es_str: 'sib', en_str: 'bb' },
                                { es: /\\b(si)\\b/g, en: /\\b(b)\\b/g, es_str: 'si', en_str: 'b' }
                            ];
        
                            let expandedAliases = [];
                            for (let alias of instAliases) {
                                expandedAliases.push(alias);
                                let noEn = alias.replace(/\\ben\\b/g, '').replace(/\\s+/g, ' ').trim();
                                if (noEn !== alias) expandedAliases.push(noEn);
        
                                for (let map of toneMappings) {
                                    if (alias.match(map.es)) {
                                        expandedAliases.push(alias.replace(map.es, map.en_str).replace(/\\ben\\b/g, '').replace(/\\s+/g, ' ').trim());
                                    } else if (alias.match(map.en)) {
                                        expandedAliases.push(alias.replace(map.en, map.es_str).replace(/\\ben\\b/g, '').replace(/\\s+/g, ' ').trim());
                                    }
                                }
                            }
                            
                            let found = false;
                            let foundAlias = '';
                            for (let alias of expandedAliases) {
                                alias = alias.replace(/\\s+/g, ' ').trim();
                                if (fileNormalized.includes(alias)) {
                                    found = true; foundAlias = alias; break;
                                }
                                
                                let parts = alias.split(' ');
                                if (parts.length > 1) {
                                    let allPartsFound = true;
                                    for (let p of parts) {
                                        let regex = new RegExp('\\\\b' + p + '\\\\b');
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
                        
                        // FALTANTES
                        let baseName = file.name.replace(/\\.[a-z0-9]+$/i, '');
                        baseName = baseName.replace(/[0-9]+/g, '');
                        baseName = baseName.replace(/\\b(en|do|re|mi|fa|sol|la|si|sib|mib|b|bb|solista|principal|pral)\\b/gi, '');
                        let tokens = baseName.split(/,| y | and | e |&|-|_|\\//i);
                        
                        for (let token of tokens) {
                            let cleanToken = token.trim().replace(/\\s+/g, ' ');
                            if (cleanToken.length > 2) {
                                let isCovered = false;
                                let tokenLower = cleanToken.toLowerCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '').replace(/[^a-z0-9]/g, ' ').trim();
                                for (let matched of matchedInstrumentsArr) {
                                    let matchedLower = matched.originalName.toLowerCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '').replace(/[^a-z0-9]/g, ' ').trim();
                                    if (matchedLower.includes(tokenLower) || tokenLower.includes(matchedLower) || matchedAliases.some(a => a.includes(tokenLower) || tokenLower.includes(a))) {
                                        isCovered = true; break;
                                    }
                                }
                                if (!isCovered && tokenLower.length > 2) {
                                    let isInDB = sortedInstruments.some(dbI => dbI.name.toLowerCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '').includes(tokenLower));
                                    if (!isInDB) {
                                        let titleName = cleanToken.charAt(0).toUpperCase() + cleanToken.slice(1).toLowerCase();
                                        if (!missingCandidates[titleName]) {
                                            missingCandidates[titleName] = { name: titleName, family: guessFamily(titleName), files: [] };
                                        }
                                        missingCandidates[titleName].files.push(file);
                                    }
                                }
                            }
                        }
                        
                        let matchedType = 'TODOS'; 
                        if (fileNormalized.match(/(?:^|\\s)1(?:st|o|a|er|\\s|$)/) || fileNormalized.includes('primero') || fileNormalized.includes('primera')) {
                            matchedType = '1º';
                        } else if (fileNormalized.match(/(?:^|\\s)2(?:nd|o|a|do|\\s|$)/) || fileNormalized.includes('segundo') || fileNormalized.includes('segunda')) {
                            matchedType = '2º';
                        } else if (fileNormalized.match(/(?:^|\\s)3(?:rd|o|a|er|\\s|$)/) || fileNormalized.includes('tercero') || fileNormalized.includes('tercera')) {
                            matchedType = '3º';
                        } else if (fileNormalized.includes('principal') || fileNormalized.includes('pral') || fileNormalized.match(/\\bsolo\\b/)) {
                            matchedType = 'PRINCIPAL';
                        }
                        
                        if (matchedInstrumentsArr.length > 0) {
                            matchedCount++;
                            let instNames = matchedInstrumentsArr.map(i => i.originalName).join(', ');
                            let li = document.createElement('li');
                            li.className = 'text-green-400';
                            li.innerHTML = \`✅ <b>\${file.name}</b> ➞ \${instNames} (\${matchedType})\`;
                            logUl.appendChild(li);
                            
                            for (const inst of matchedInstrumentsArr) {
                                const input = document.getElementById('file_' + inst.id);
                                if (input) {
                                    if (window.uploadedInstrumentIds && window.uploadedInstrumentIds.includes(inst.id.toString())) {
                                        let liWarn = document.createElement('li');
                                        liWarn.className = 'text-amber-400 text-xs ml-4';
                                        liWarn.innerHTML = \`⚠️ Omitido: el instrumento <b>\${inst.originalName}</b> ya tiene partitura.\`;
                                        logUl.appendChild(liWarn);
                                        skippedCount++;
                                        continue;
                                    }
                                    
                                    const dt = new DataTransfer();
                                    if (input.files.length > 0) {
                                        for (let j=0; j<input.files.length; j++) dt.items.add(input.files[j]);
                                    }
                                    dt.items.add(file);
                                    input.files = dt.files;
                                    
                                    const card = document.getElementById('card_' + inst.id);
                                    if (card) {
                                        card.classList.remove('border-gray-700', 'bg-gray-800');
                                        card.classList.add('border-indigo-500', 'bg-indigo-900/40', 'ring-1', 'ring-indigo-500');
                                    }
                                    
                                    const label = document.getElementById('label_' + inst.id);
                                    if (label) {
                                        label.innerHTML = \`<span class="text-green-400 font-medium">✅ Archivos Locales: \${dt.files.length}</span>\`;
                                    }
                                    
                                    const select = document.getElementById('type_' + inst.id);
                                    if (select) {
                                        let foundOption = Array.from(select.options).find(opt => opt.value === matchedType);
                                        if (foundOption) {
                                            select.value = matchedType;
                                        } else {
                                            let todosOption = Array.from(select.options).find(opt => opt.value === 'TODOS');
                                            if (todosOption) select.value = 'TODOS';
                                        }
                                    }
                                }
                            }
                        } else {
                            let li = document.createElement('li');
                            li.className = 'text-gray-400';
                            li.innerHTML = \`❌ <b>\${file.name}</b> ➞ No se encontró instrumento.\`;
                            logUl.appendChild(li);
                        }
                    }
                    
                    let liSummary = document.createElement('li');
                    liSummary.className = 'text-white font-bold mt-2 pt-2 border-t border-gray-600';
                    liSummary.innerHTML = \`Proceso completado: \${matchedCount} archivos enlazados, \${files.length - matchedCount} no encontrados, \${skippedCount} omitidos.\`;
                    logUl.appendChild(liSummary);
                    
                    renderMissingInstruments(missingCandidates, instruments);
                });
            }
            
            function renderMissingInstruments(candidates, instrumentsArray) {
                const container = document.getElementById('missing_instruments_container');
                if (!container) return;
                
                const list = document.getElementById('missing_instruments_list');
                list.innerHTML = '';
                
                let count = 0;
                for (let key in candidates) {
                    count++;
                    let cand = candidates[key];
                    let div = document.createElement('div');
                    div.className = 'flex items-center gap-3 bg-gray-800 p-3 rounded-md border border-gray-700';
                    div.innerHTML = \`
                        <input type="checkbox" id="chk_missing_\${count}" value="\${key}" class="missing-chk w-5 h-5 rounded border-gray-500 text-indigo-600 focus:ring-indigo-600 bg-gray-700">
                        <label for="chk_missing_\${count}" class="text-white font-semibold flex-1 cursor-pointer select-none">\${cand.name}</label>
                        <select id="sel_missing_\${count}" class="bg-gray-900 border border-gray-600 text-white text-sm rounded-md px-3 py-1.5">
                            <option value="VIENTO MADERA" \${cand.family==='VIENTO MADERA'?'selected':''}>Viento Madera</option>
                            <option value="VIENTO METAL" \${cand.family==='VIENTO METAL'?'selected':''}>Viento Metal</option>
                            <option value="PERCUSIÓN" \${cand.family==='PERCUSIÓN'?'selected':''}>Percusión</option>
                            <option value="CUERDA" \${cand.family==='CUERDA'?'selected':''}>Cuerda</option>
                            <option value="TECLA" \${cand.family==='TECLA'?'selected':''}>Tecla</option>
                        </select>
                        <span class="text-xs text-gray-400 w-24 text-right">(\${cand.files.length} archivo/s)</span>
                    \`;
                    list.appendChild(div);
                }
                
                if (count > 0) {
                    container.classList.remove('hidden');
                    const btn = document.getElementById('btn_create_missing');
                    const newBtn = btn.cloneNode(true);
                    btn.parentNode.replaceChild(newBtn, btn);
                    
                    newBtn.addEventListener('click', function() {
                        let toCreate = [];
                        let chks = list.querySelectorAll('.missing-chk:checked');
                        chks.forEach(chk => {
                            let key = chk.value;
                            let selId = chk.id.replace('chk_', 'sel_');
                            let family = document.getElementById(selId).value;
                            toCreate.push({ name: candidates[key].name, type: family, files: candidates[key].files });
                        });
                        
                        if (toCreate.length === 0) return alert('Selecciona al menos un instrumento para crear.');
                        if (!confirm('Se crearán ' + toCreate.length + ' instrumentos nuevos en el catálogo general. ¿Estás seguro?')) return;
                        
                        this.innerText = 'Creando...';
                        this.disabled = true;
                        this.classList.add('opacity-50');
                        
                        fetch("{{ route('instruments.ajax-create') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ instruments: toCreate.map(c => ({ name: c.name, type: c.type })) })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert("Instrumentos creados y asignados correctamente.");
                                data.instruments.forEach(newInst => {
                                    instrumentsArray.push({ id: newInst.id, name: newInst.name, originalName: newInst.name });
                                    
                                    let matchedCandidate = toCreate.find(c => c.name.toUpperCase() === newInst.name.toUpperCase());
                                    appendInstrumentRow(newInst);
                                    
                                    if (matchedCandidate) {
                                        let input = document.getElementById('file_' + newInst.id);
                                        if (input) {
                                            const dt = new DataTransfer();
                                            matchedCandidate.files.forEach(f => dt.items.add(f));
                                            input.files = dt.files;
                                            
                                            // trigger visual changes
                                            const card = document.getElementById('card_' + newInst.id);
                                            if (card) {
                                                card.classList.remove('border-gray-700', 'bg-gray-800');
                                                card.classList.add('border-indigo-500', 'bg-indigo-900/40', 'ring-1', 'ring-indigo-500');
                                            }
                                        }
                                    }
                                });
                                chks.forEach(chk => chk.closest('.flex').remove());
                                if (list.children.length === 0) container.classList.add('hidden');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert("Hubo un error al crear los instrumentos.");
                        })
                        .finally(() => {
                            this.innerText = 'Crear Instrumentos Seleccionados';
                            this.disabled = false;
                            this.classList.remove('opacity-50');
                        });
                    });
                } else {
                    container.classList.add('hidden');
                }
            }
        });
    </script>
`;

content = content.replace(/<script>.*?<\/script>/s, newScript);
fs.writeFileSync('resources/views/admin/sheet-music/edit.blade.php', content);
