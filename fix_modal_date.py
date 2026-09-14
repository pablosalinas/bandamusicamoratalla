import io
file_path = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\welcome.blade.php'
with io.open(file_path, 'r', encoding='utf-8') as f:
    c = f.read()

import re
c = re.sub(
    r'(<span>.*Publicado:\s*\{\{\s*\->created_at->format\(\'d/m/Y\'\)\s*\}\}</span>)',
    r'@if(!->event_date)\n                                                    \1\n                                                @endif',
    c
)

with io.open(file_path, 'w', encoding='utf-8') as f:
    f.write(c)