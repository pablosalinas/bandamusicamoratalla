<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ $sheetMusic->title }} - Atril Digital e Intérprete Musical</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/music-interpreter.js') }}"></script>
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
            position: relative;
        }
        .page-container canvas, .page-container img {
            max-width: 100%;
            height: auto;
            display: block;
            margin-bottom: 2px;
            background-color: white;
        }
        #music-interpreter-overlay {
            display: none !important;
            background: transparent !important;
            background-color: transparent !important;
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
        <div class="flex items-center gap-3 sm:gap-4">
            <a href="{{ $backUrl }}" class="text-white bg-gray-700 hover:bg-gray-600 rounded px-3 py-1.5 text-sm font-medium transition flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span class="hidden sm:inline">Volver</span>
            </a>
            <div class="hidden sm:block">
                <h1 class="font-bold text-sm truncate max-w-xs text-white">{{ $sheetMusic->title }}</h1>
                <p class="text-xs text-indigo-300 font-medium">{{ $instrument->name }} - {{ $sheetMusicInstrument->tipo_partitura }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Botón Intérprete Musical -->
            <button @click="toggleInterpreter" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm transition shadow-sm"
                    :class="showInterpreter ? 'bg-amber-600 text-white font-bold ring-2 ring-amber-400 shadow-amber-600/30' : 'bg-gray-700 text-amber-300 hover:bg-gray-600'"
                    title="Mostrar / Ocultar Intérprete Musical">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                </svg>
                <span class="hidden md:inline" x-text="showInterpreter ? 'Intérprete: ON' : 'Intérprete: OFF'"></span>
                <span class="md:hidden text-xs font-bold">🎵 Intérprete</span>
            </button>

            <button @click="toggleHalfPage" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm transition"
                    :class="halfPageMode ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                <span class="hidden sm:inline" x-text="halfPageMode ? 'Medio Avance: ON' : 'Medio Avance: OFF'"></span>
            </button>

            <button @click="toggleFullScreen" class="p-1.5 rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600 transition" title="Pantalla Completa">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
            </button>
        </div>
    </div>

    <!-- Floating Back Button -->
    <a href="{{ $backUrl }}" class="fixed bottom-6 left-6 z-40 bg-indigo-600/90 hover:bg-indigo-500 text-white p-4 sm:p-5 rounded-full shadow-2xl backdrop-blur-md border border-indigo-400 transition" title="Volver">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>

    <!-- Zones -->
    <div class="touch-zones">
        <div class="zone zone-left" @click="goPrev" @dblclick.prevent=""></div>
        <div class="zone zone-center" @click="toggleHud" @dblclick.prevent=""></div>
        <div class="zone zone-right" @click="goNext" @dblclick.prevent=""></div>
    </div>

    <!-- Render Container -->
    <div id="render-container" class="page-container mt-0 pb-36">
        @if(in_array($extension, ['pdf']))
            <div id="loading" class="mt-20 text-gray-400 animate-pulse flex flex-col items-center">
                <svg class="animate-spin h-8 w-8 text-indigo-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Preparando partitura y cargando intérprete...
            </div>
        @else
            <img id="score-image" src="{{ $downloadRoute }}" alt="Partitura" class="w-full">
        @endif
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('viewerApp', () => ({
                showHud: true,
                halfPageMode: false,
                showInterpreter: true,
                
                init() {
                    let hudTimeout;
                    const hideHudDelayed = () => {
                        clearTimeout(hudTimeout);
                        hudTimeout = setTimeout(() => { this.showHud = false; }, 4000);
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
                
                toggleInterpreter() {
                    this.showInterpreter = !this.showInterpreter;
                    const dock = document.getElementById('music-interpreter-dock');
                    if (dock) {
                        dock.style.display = this.showInterpreter ? 'flex' : 'none';
                    }
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

        function setupInterpreter() {
            const container = document.getElementById('render-container');
            const instrumentName = "{{ addslashes($instrument->name ?? 'General') }}";
            
            if (window.MusicInterpreter) {
                window.interpreter = window.MusicInterpreter.createInstance(container, instrumentName);
                
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('interpreter') === '1' || urlParams.get('autoplay') === '1') {
                    setTimeout(() => {
                        if (window.interpreter) {
                            window.interpreter.scanCurrentScore();
                            if (urlParams.get('autoplay') === '1') {
                                window.interpreter.play();
                            }
                        }
                    }, 500);
                }
            }
        }
        
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
                const pixelRatio = window.devicePixelRatio || 1;
                const scale = (window.innerWidth / unscaledViewport.width) * pixelRatio;
                
                let pagesLoaded = 0;
                for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                    renderPage(pageNum, scale, function() {
                        pagesLoaded++;
                        if (pagesLoaded === pdf.numPages) {
                            setupInterpreter();
                        }
                    });
                }
            });
        }).catch(function(err) {
            console.error('Error al cargar PDF:', err);
            document.getElementById('loading').innerHTML = 'Error al cargar el PDF. Puede estar dañado o no disponible.';
        });

        function renderPage(num, scale, onComplete) {
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({scale: scale});
                
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                canvas.style.width = '100%';
                
                container.appendChild(canvas);
                
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                
                page.render(renderContext).promise.then(function() {
                    if (onComplete) onComplete();
                });
            });
        }
        @else
        window.addEventListener('load', function() {
            setupInterpreter();
        });
        @endif
    </script>
</body>
</html>
