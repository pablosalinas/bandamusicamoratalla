import os
import re

filepath = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\musician\sheet-music\viewer.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    text = f.read()

pattern = r'const unscaledViewport = firstPage\.getViewport\(\{ scale: 1\.0 \}\);.*?const scale = Math\.min\(scaleWidth, scaleHeight\) \* 0\.98;'
replacement = """const unscaledViewport = firstPage.getViewport({ scale: 1.0 });
                // We want the PDF to be as wide as the screen to maximize readability.
                // Mobile and tablets need it to occupy the full width.
                const scale = (window.innerWidth / unscaledViewport.width);"""

new_text = re.sub(pattern, replacement, text, flags=re.DOTALL)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(new_text)
print("Done")
