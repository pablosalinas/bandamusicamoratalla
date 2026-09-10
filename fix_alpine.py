import os
import re

filepath = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\musician\sheet-music\viewer.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    text = f.read()

pattern = r"Alpine\.data\('viewerApp', \(\) => \(\{.*?\s+\}\)\);\s+\}\);"
replacement = """Alpine.data('viewerApp', () => ({
                showHud: true,
                halfPageMode: false,
                
                init() {
                    let hudTimeout;
                    const hideHudDelayed = () => {
                        clearTimeout(hudTimeout);
                        hudTimeout = setTimeout(() => { this.showHud = false; }, 3000);
                    };
                    hideHudDelayed();
                    
                    window.addEventListener('scroll', () => {
                        if (this.showHud) hideHudDelayed();
                    });
                },
                
                toggleHud() {
                    this.showHud = !this.showHud;
                },
                
                toggleHalfPage() {
                    this.halfPageMode = !this.halfPageMode;
                },
                
                toggleFullScreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(err => {
                            console.error(`Error full screen: ${err.message}`);
                        });
                    } else {
                        document.exitFullscreen();
                    }
                },
                
                goNext() {
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

new_text = re.sub(pattern, replacement, text, flags=re.DOTALL)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(new_text)
print("Done")
