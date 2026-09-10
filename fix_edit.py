import os
filepath = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\admin\sheet-music\edit.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    text = f.read()

target = """                                                    @foreach($assignedTypes as $type)
                                                        <span class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-0.5 text-xs font-medium text-blue-400 ring-1 ring-inset ring-blue-400/30">{{ $type }}</span>
                                                    @endforeach"""

replacement = """                                                    @foreach($assignedTypes as $type)
                                                        @php
                                                            $pivotHdr = isset($filesIndexed[$instrument->id][$type]) ? $filesIndexed[$instrument->id][$type] : null;
                                                        @endphp
                                                        @if($pivotHdr)
                                                            <a href="{{ route('admin.sheet-music.view-part', $pivotHdr->id) }}" @click.stop class="inline-flex items-center gap-1 rounded-md bg-indigo-500/20 px-2 py-0.5 text-xs font-medium text-indigo-300 ring-1 ring-inset ring-indigo-500/40 hover:bg-indigo-500/40 transition" title="Ver Atril">
                                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                                {{ $type }}
                                                            </a>
                                                        @else
                                                            <span class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-0.5 text-xs font-medium text-blue-400 ring-1 ring-inset ring-blue-400/30">{{ $type }}</span>
                                                        @endif
                                                    @endforeach"""

if target in text:
    text = text.replace(target, replacement)
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(text)
    print("Done")
else:
    print("Target not found")
