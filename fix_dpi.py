import os
import re

filepath = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\musician\sheet-music\viewer.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    text = f.read()

pattern = r'const unscaledViewport = firstPage\.getViewport\(\{ scale: 1\.0 \}\);.*?for \(let pageNum = 1; pageNum <= pdf\.numPages; pageNum\+\+\) \{'
replacement = """const unscaledViewport = firstPage.getViewport({ scale: 1.0 });
                // We want the PDF to be as wide as the screen to maximize readability.
                // We multiply by devicePixelRatio to ensure it's sharp on mobile (Retina) displays!
                const pixelRatio = window.devicePixelRatio || 1;
                const scale = (window.innerWidth / unscaledViewport.width) * pixelRatio;
                
                for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {"""

new_text = re.sub(pattern, replacement, text, flags=re.DOTALL)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(new_text)
print("Done")
