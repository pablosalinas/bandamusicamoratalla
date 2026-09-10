import os
filepath = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\musician\sheet-music\viewer.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    text = f.read()

target = """    <!-- Floating Back Button -->
    <a href="{{ $backUrl }}" class="fixed bottom-4 left-4 z-50 bg-gray-900/60 hover:bg-gray-800/80 text-white p-3 rounded-full shadow-lg backdrop-blur-sm border border-gray-700/50 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>"""

replacement = """    <!-- Floating Back Button -->
    <a href="{{ $backUrl }}" class="fixed bottom-6 left-6 z-50 bg-indigo-600/90 hover:bg-indigo-500 text-white p-5 rounded-full shadow-2xl backdrop-blur-md border border-indigo-400 transition" title="Volver">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>"""

text = text.replace(target, replacement)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(text)
print("Done")
