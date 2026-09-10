import os
import re

filepath = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\musician\sheet-music\viewer.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    text = f.read()

# 1. Fix navigation
pattern_nav = r'goNext\(\) \{.*?\}\s+\}\)\);\s+\}\);'
replacement_nav = """goNext() {
                    if (this.halfPageMode) {
                        window.scrollBy({ top: window.innerHeight * 0.5, behavior: 'smooth' });
                    } else {
                        window.scrollBy({ top: window.innerHeight * 0.9, behavior: 'smooth' });
                    }
                },
                
                goPrev() {
                    if (this.halfPageMode) {
                        window.scrollBy({ top: -window.innerHeight * 0.5, behavior: 'smooth' });
                    } else {
                        window.scrollBy({ top: -window.innerHeight * 0.9, behavior: 'smooth' });
                    }
                }
            }));
        });"""
text = re.sub(pattern_nav, replacement_nav, text, flags=re.DOTALL)

# 2. Fix DPI scaling
pattern_dpi = r'const unscaledViewport = firstPage\.getViewport\(\{ scale: 1\.0 \}\);.*?for \(let pageNum = 1; pageNum <= pdf\.numPages; pageNum\+\+\) \{'
replacement_dpi = """const unscaledViewport = firstPage.getViewport({ scale: 1.0 });
                // We want the PDF to be as wide as the screen to maximize readability.
                // We multiply by devicePixelRatio to ensure it's sharp on mobile (Retina) displays!
                const pixelRatio = window.devicePixelRatio || 1;
                const scale = (window.innerWidth / unscaledViewport.width) * pixelRatio;
                
                for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {"""
text = re.sub(pattern_dpi, replacement_dpi, text, flags=re.DOTALL)

# 3. Fix rendering CSS
pattern_css = r'const canvas = document\.createElement\(\'canvas\'\);.*?page\.render\(renderContext\);'
replacement_css = """const canvas = document.createElement('canvas');
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
text = re.sub(pattern_css, replacement_css, text, flags=re.DOTALL)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(text)
print("Done")
