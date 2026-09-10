import os
import re

filepath = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\musician\sheet-music\viewer.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    text = f.read()

pattern = r'init\(\) \{.*?\}(?=\s*\,[\s\n]*goNext)'
replacement = """init() {
                    let hudTimeout;
                    const hideHudDelayed = () => {
                        clearTimeout(hudTimeout);
                        hudTimeout = setTimeout(() => { this.showHud = false; }, 3000);
                    };
                    hideHudDelayed();
                    
                    window.addEventListener('scroll', () => {
                        if (this.showHud) hideHudDelayed();
                    });
                }"""

new_text = re.sub(pattern, replacement, text, flags=re.DOTALL)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(new_text)
print("Done")
