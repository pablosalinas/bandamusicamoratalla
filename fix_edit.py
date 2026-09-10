import os
filepath = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\admin\sheet-music\edit.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    text = f.read()

target = """                                                    @if($pivot)
                                                        <a href="{{ route('admin.sheet-music.download-part', $pivot->id) }}" target="_blank" class="text-xs text-blue-400 hover:text-blue-300 flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                            Ver archivo
                                                        </a>
                                                    @endif"""

replacement = """                                                    @if($pivot)
                                                        <div class="flex gap-2">
                                                            <a href="{{ route('admin.sheet-music.view-part', $pivot->id) }}" class="text-xs text-indigo-400 hover:text-indigo-300 flex items-center">
                                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                                Ver Atril
                                                            </a>
                                                            <a href="{{ route('admin.sheet-music.download-part', $pivot->id) }}" class="text-xs text-amber-400 hover:text-amber-300 flex items-center">
                                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                                PDF
                                                            </a>
                                                        </div>
                                                    @endif"""

text = text.replace(target, replacement)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(text)
print("Done")
