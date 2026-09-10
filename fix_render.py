import os
import re

filepath = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\musician\sheet-music\viewer.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    text = f.read()

pattern = r'const canvas = document\.createElement\(\'canvas\'\);.*?page\.render\(renderContext\);'
replacement = """const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                
                // Asegurar que el canvas ocupe todo el ancho visualmente (CSS logical pixels)
                canvas.style.width = '100%';
                
                container.appendChild(canvas);
                
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                
                page.render(renderContext);"""

new_text = re.sub(pattern, replacement, text, flags=re.DOTALL)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(new_text)
print("Done")
