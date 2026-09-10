import os
filepath = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\musician\sheet-music\viewer.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    text = f.read()

text = text.replace(
    "{{ route('dashboard') }}", 
    "{{ $backUrl }}"
)

text = text.replace(
    "{{ route('musician.sheet-music.download', ['sheetMusicInstrument' => $sheetMusicInstrument->id, 'stream' => 1]) }}", 
    "{!! $downloadRoute !!}"
)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(text)
print("Done")
