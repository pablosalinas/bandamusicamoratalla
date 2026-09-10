import os

filepath = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\admin\sheet-music\edit.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    text = f.read()

text = text.replace("{{ route('admin.sheet-music.upload-grid-row-ajax', ) }}", "{{ route('admin.sheet-music.upload-grid-row-ajax', $sheetMusic) }}")

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(text)
print("Done")
