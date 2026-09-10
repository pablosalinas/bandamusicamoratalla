<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ $sheetMusic->title }} - Atril Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @if(in_array($extension, ['pdf']))
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
        <script>pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';</script>
    @endif
    <style>
        body { margin: 0; padding: 0; background-color: #111; color: #fff; overflow-x: hidden; touch-action: pan-y; overscroll-behavior-y: none; }
        .page-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        canvas, img {
            max-width: 100%;
            height: auto;
            display: block;
            margin-bottom: 2px;
            background-color: white;
        }
        .hud {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: rgba(0,0,0,0.85);
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 50;
            transition: opacity 0.3s;
            backdrop-filter: blur(5px);
            border-bottom: 1px solid #333;
        }
        .touch-zones {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            display: flex;
            z-index: 10;
        }
        .zone { flex: 1; }
        .zone-left { max-width: 25%; }
        .zone-right { max-width: 25%; }
        .zone-center { flex: 1; }
    </style>
</head>
<body x-data="viewerApp()">

    <!-- HUD -->
    <div id="hud" class="hud" :class="{ 'opacity-0 pointer-events-none': !showHud }">
        <div class="flex items-center gap-4">
            <a href="{{ $backUrl }}" class="text-white bg-gray-700 hover:bg-gray-600 rounded px-3 py-1.5 text-sm font-medium transition">
                ← Volver
            </a>
            <div class="hidden sm:block">
                <h1 class="font-bold text-sm truncate max-w-xs">{{ $sheetMusic->title }}</h1>
                <p class="text-xs text-gray-400">{{ $instrument->name }} - {{ $sheetMusicInstrument->tipo_partitura }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-2 sm:gap-4">
            <button @click="toggleHalfPage" class="flex items-center gap-2 px-3 py-1.5 rounded text-sm transition"
                    :class="halfPageMode ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                <span class="hidden sm:inline" x-text="halfPageMode ? 'Medio Avance: ON' : 'Medio Avance: OFF'"></span>
            </button>
            <button @click="toggleFullScreen" class="p-1.5 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
            </button>
        </div>
    </div>


    <!-- Floating Back Button -->
    <a href="{{ $backUrl }}" class="fixed bottom-6 left-6 z-50 bg-indigo-600/90 hover:bg-indigo-500 text-white p-5 rounded-full shadow-2xl backdrop-blur-md border border-indigo-400 transition" title="Volver">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>

    <!-- Zones -->

    <div class="touch-zones">
        <div class="zone zone-left" @click="goPrev" @dblclick.prevent=""></div>
        <div class="zone zone-center" @click="toggleHud" @dblclick.prevent=""></div>
        <div class="zone zone-right" @click="goNext" @dblclick.prevent=""></div>
    </div>

    <!-- Render Container -->
    <div id="render-container" class="page-container mt-0 pb-32">
        @if(in_array($extension, ['pdf']))
            <div id="loading" class="mt-20 text-gray-400 animate-pulse flex flex-col items-center">
                <svg class="animate-spin h-8 w-8 text-indigo-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Preparando partitura...
            </div>
        @else
            <img src="{{ $downloadRoute }}" alt="Partitura" class="w-full">
        @endif
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('viewerApp', () => ({
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
        });
        
        @if(in_array($extension, ['pdf']))
        // Renderizar PDF de forma continua
        const url = "{!! $downloadRoute !!}";
        
        const container = document.getElementById('render-container');
        
        let pdfDoc = null;
        
        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            document.getElementById('loading').style.display = 'none';
            pdfDoc = pdf;
            
            pdfDoc.getPage(1).then(function(firstPage) {
                const unscaledViewport = firstPage.getViewport({ scale: 1.0 });
                // We want the PDF to be as wide as the screen to maximize readability.
                // We multiply by devicePixelRatio to ensure it's sharp on mobile (Retina) displays!
                const pixelRatio = window.devicePixelRatio || 1;
                const scale = (window.innerWidth / unscaledViewport.width) * pixelRatio;
                
                for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                    renderPage(pageNum, scale);
                }
            });
        }).catch(function(err) {
            console.error('Error al cargar PDF:', err);
            document.getElementById('loading').innerHTML = 'Error al cargar el PDF. Puede estar dañado o no disponible.';
        });

        function renderPage(num, scale) {
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({scale: scale});
                
                const canvas = document.createElement('canvas');
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
                
                page.render(renderContext);
            });
        }
        @endif
    </script>
</body>
</html>
