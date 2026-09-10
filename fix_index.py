import os
import re

filepath = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\admin\sheet-music\index.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    text = f.read()

pattern = r'(@if\(\$sheet->pdf_file_path\))(.*?)(@endif)'
replacement = r"""\1
                                            <div class="flex flex-col gap-1">
                                                <a href="{{ route('admin.sheet-music.view', $sheet) }}" class="text-indigo-400 hover:text-indigo-300 inline-flex items-center text-xs">
                                                    <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                    Ver Atril
                                                </a>
                                                <a href="{{ route('admin.sheet-music.download', $sheet) }}" class="text-blue-500 hover:text-blue-400 inline-flex items-center text-xs">
                                                    <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                    </svg>
                                                    Guión
                                                </a>
                                            </div>
                                        \3"""

new_text = re.sub(pattern, replacement, text, flags=re.DOTALL)
if text != new_text:
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_text)
    print("Done index regex")
else:
    print("No changes made via regex")

