<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Banda de Música de Moratalla</title>
    
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <!-- SEO Meta Tags -->
    <meta name="description" content="Página oficial de la Banda de Música de Moratalla. Conoce nuestra historia, descubre a nuestros músicos y mantente al día de las últimas noticias y actuaciones.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,600,800&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .text-gold {
            background: linear-gradient(to right, #fbbf24, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        @keyframes slogan-shine {
            0% { background-position: 200% center; }
            100% { background-position: -200% center; }
        }
        @keyframes slogan-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-2px); }
        }
        .animate-slogan {
            background: linear-gradient(90deg, rgba(245, 158, 11, 0.8) 0%, rgba(245, 158, 11, 0.8) 40%, #ffffff 50%, rgba(245, 158, 11, 0.8) 60%, rgba(245, 158, 11, 0.8) 100%);
            background-size: 200% auto;
            color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
            animation: slogan-shine 4s linear infinite, slogan-float 3s ease-in-out infinite;
            display: inline-block;
        }
    </style>
</head>
<body class="antialiased bg-gray-950 text-gray-200" x-data="{ scrolled: false, mobileMenuOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <!-- Navigation -->
    <nav :class="{'bg-gray-950/90 backdrop-blur-md shadow-lg border-b border-gray-800': scrolled, 'bg-transparent': !scrolled}" class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-3 md:gap-4">
                    <x-logo-rotator class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full shadow-[0_0_15px_rgba(245,158,11,0.4)] shrink-0 overflow-hidden" />
                    <div class="flex flex-col">
                        <div class="flex flex-col lg:flex-row lg:items-baseline leading-none">
                            <span class="text-xl sm:text-2xl lg:text-4xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600">{{ $globalBandName }}</span>
                        </div>
                        <span class="text-xs sm:text-sm lg:text-lg font-medium italic tracking-widest mt-1 animate-slogan">{{ \App\Models\SiteSetting::getSetting('site_slogan', 'Tu banda') }}</span>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#inicio" class="text-gray-300 hover:text-white transition-colors font-medium">Inicio</a>
                    <a href="#historia" class="text-gray-300 hover:text-white transition-colors font-medium">Historia</a>
                    <a href="#noticias" class="text-gray-300 hover:text-white transition-colors font-medium">Noticias</a>
                    <a href="#archivo-sonoro" class="text-gray-300 hover:text-white transition-colors font-medium">Archivo Sonoro</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-amber-500 hover:text-amber-400 font-semibold transition-colors">Mi Panel</a>
                    @else
                        <a href="{{ route('login', ['v' => 1]) }}" class="px-5 py-2 rounded-full border border-amber-500/50 text-amber-500 hover:bg-amber-500 hover:text-gray-900 transition-all font-semibold shadow-[0_0_15px_rgba(245,158,11,0.2)] hover:shadow-[0_0_20px_rgba(245,158,11,0.4)] whitespace-nowrap">Acceso Músicos</a>
                    @endauth
                </div>
                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none transition-colors">
                        <span class="sr-only">Abrir menú</span>
                        <svg x-show="!mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu panel -->
        <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" class="md:hidden bg-gray-950 border-b border-gray-800" style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#inicio" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800">Inicio</a>
                <a href="#historia" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800">Historia</a>
                <a href="#noticias" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800">Noticias</a>
                <a href="#archivo-sonoro" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800">Archivo Sonoro</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-amber-500 hover:text-amber-400 hover:bg-gray-800">Mi Panel</a>
                @else
                    <a href="{{ route('login', ['v' => 1]) }}" class="block px-3 py-2 rounded-md text-base font-medium text-amber-500 hover:text-amber-400 hover:bg-gray-800">Acceso Músicos</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Carrusel Section -->
    @if(isset($carouselMedia) && $carouselMedia->count() > 0)
    <section id="carrusel" class="w-full bg-gray-950 border-b border-gray-900 relative pt-24 pb-8"
        @resize.window="windowWidth = window.innerWidth; currentIndex = Math.min(currentIndex, maxIndex)"
        x-data="{
            slides: [
                @foreach($carouselMedia as $media)
                {
                    id: {{ $media->id }},
                    type: '{{ $media->type }}',
                    src: '{{ asset('storage/' . $media->file_path) }}',
                    description: @js($media->description)
                },
                @endforeach
            ],
            currentIndex: 0,
            autoplayInterval: null,
            speed: {{ ($carouselSpeed ?? 4) * 1000 }},
            lightboxOpen: false,
            windowWidth: window.innerWidth,
            globalMuted: true,

            get visibleItems() {
                if (this.windowWidth < 640) return 1;
                if (this.windowWidth < 1024) return 2;
                return 3;
            },
            
            get maxIndex() {
                return Math.max(0, this.slides.length - this.visibleItems);
            },

            get hasVideos() {
                return this.slides.some(s => s.type === 'video');
            },

            init() {
                this.$nextTick(() => {
                    this.startAutoplay();
                });
            },

            stopAllVideos() {
                document.querySelectorAll('.carousel-video').forEach(v => {
                    v.pause();
                    v.currentTime = 0;
                    v.onended = null;
                });
            },
            
            startAutoplay() {
                this.stopAutoplay();
                this.$nextTick(() => {
                    let currentSlide = this.slides[this.currentIndex];
                    if (!currentSlide || currentSlide.type !== 'video') {
                        this.scheduleNext();
                    } else {
                        let videoEl = document.getElementById('carousel-video-' + this.currentIndex);
                        if (videoEl) {
                            videoEl.muted = true; // Siempre sin sonido en el carrusel principal
                            videoEl.currentTime = 0;
                            let playPromise = videoEl.play();
                            if (playPromise !== undefined) {
                                playPromise.catch(() => {});
                            }
                            videoEl.onended = () => { this.next(); };
                        } else {
                            this.scheduleNext();
                        }
                    }
                });
            },
            
            scheduleNext() {
                this.autoplayInterval = setTimeout(() => {
                    this.next();
                }, this.speed);
            },
            
            stopAutoplay() {
                if (this.autoplayInterval) {
                    clearTimeout(this.autoplayInterval);
                }
                this.stopAllVideos();
            },
            
            next() {
                this.stopAllVideos();
                this.currentIndex = (this.currentIndex + 1 > this.maxIndex) ? 0 : this.currentIndex + 1;
                this.startAutoplay();
            },
            
            prev() {
                this.stopAllVideos();
                this.currentIndex = (this.currentIndex - 1 < 0) ? this.maxIndex : this.currentIndex - 1;
                this.startAutoplay();
            },

            toggleMute() {
                this.globalMuted = !this.globalMuted;
                let videoEl = document.getElementById('carousel-video-' + this.currentIndex);
                if (videoEl) {
                    videoEl.muted = this.globalMuted;
                    if (!this.globalMuted) videoEl.play().catch(() => {});
                }
            },
            
            openLightbox(index) {
                // Remove forcing globalMuted = true here, preserve user's choice
                this.lightboxOpen = true;
                document.body.style.overflow = 'hidden';
            },
            
            closeLightbox() {
                this.lightboxOpen = false;
                document.body.style.overflow = '';
            }
        }">
        
        <!-- Carrusel Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative w-full h-56 sm:h-80 md:h-[400px] overflow-hidden group cursor-pointer" @click="openLightbox">
                
                <div class="flex h-full transition-transform duration-700 ease-in-out" 
                     :style="`transform: translateX(-${currentIndex * (100 / visibleItems)}%);`">
                     
                    <template x-for="(slide, index) in slides" :key="slide.id">
                        <div class="h-full flex-none px-2" 
                             :style="`width: ${100 / visibleItems}%`">
                             
                            <div class="relative w-full h-full sm:rounded-2xl shadow-lg ring-1 ring-white/10 overflow-hidden bg-gray-900 hover:shadow-xl transition-shadow">
                                <template x-if="slide.type === 'image'">
                                    <img :src="slide.src" class="w-full h-full object-cover">
                                </template>
                                
                                <template x-if="slide.type === 'video'">
                                    <div class="relative w-full h-full">
                                        <video :id="'carousel-video-' + index"
                                               :src="slide.src"
                                               class="carousel-video w-full h-full object-cover"
                                               muted
                                               playsinline>
                                        </video>
                                    </div>
                                </template>
                                
                                <template x-if="slide.description">
                                    <div class="absolute bottom-4 left-0 right-0 mx-auto w-11/12 text-center">
                                        <div class="inline-block bg-black/60 backdrop-blur-sm px-3 py-1.5 rounded-lg border border-white/10 shadow-lg">
                                            <p class="text-white text-sm md:text-base font-medium tracking-wide drop-shadow-md line-clamp-2" x-text="slide.description"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            
                <!-- Controls (Overlay) -->
                <button @click.stop="prev" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/80 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button @click.stop="next" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/80 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
            
            <div class="mt-6 flex justify-center space-x-2">
                <template x-for="i in (maxIndex + 1)" :key="i">
                    <button @click.stop="currentIndex = i - 1; startAutoplay();" :class="{'bg-amber-500 w-6': currentIndex === (i - 1), 'bg-gray-600 hover:bg-gray-400 w-2': currentIndex !== (i - 1)}" class="h-2 rounded-full transition-all duration-300"></button>
                </template>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div x-show="lightboxOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95" style="display: none;" @keydown.escape.window="closeLightbox">
            <button @click="closeLightbox" class="absolute top-6 right-6 text-white/70 hover:text-white z-50">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <button @click="prev" class="absolute left-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white p-4 z-50">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            
            <button @click="next" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white p-4 z-50">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
            
            <div class="relative w-full max-w-6xl max-h-[90vh] px-4 sm:px-12 flex items-center justify-center">
                <template x-for="(slide, index) in slides" :key="slide.id">
                    <div x-show="currentIndex === index"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="w-full flex justify-center">
                        
                        <template x-if="slide.type === 'image'">
                            <img :src="slide.src" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl">
                        </template>
                        
                        <template x-if="slide.type === 'video'">
                            <!-- Using a different ID for lightbox video so they don't clash -->
                            <video :id="'lightbox-video-' + index" :src="slide.src" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl" controls muted></video>
                        </template>
                        
                        <template x-if="slide.description">
                            <div class="absolute bottom-10 left-0 right-0 mx-auto w-11/12 max-w-3xl text-center">
                                <div class="inline-block bg-black/70 backdrop-blur-md px-8 py-4 rounded-xl border border-white/20 shadow-2xl">
                                    <p class="text-white text-xl md:text-2xl font-semibold tracking-wide drop-shadow-lg" x-text="slide.description"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
            
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/50 text-sm">
                <span x-text="(currentIndex + 1)"></span> / <span x-text="slides.length"></span>
            </div>
        </div>
    </section>
    @endif

    <!-- Hero Section -->
    <section id="inicio" class="relative min-h-[70vh] flex items-center justify-center overflow-hidden pt-10">
        <!-- Background Image & Gradient overlay -->
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1511192336575-5a79af67a629?q=80&w=2000&auto=format&fit=crop')] bg-cover bg-center opacity-30 transform scale-105 animate-[pulse_10s_ease-in-out_infinite_alternate]"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-gray-950/40 via-gray-950/80 to-gray-950"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-amber-900/20 to-transparent mix-blend-overlay"></div>

        <div class="relative z-10 text-center max-w-4xl px-6 mt-10">
            <div class="inline-flex flex-col sm:flex-row items-center justify-center px-5 py-2 mb-6 rounded-full border border-amber-500/30 bg-amber-500/10 text-amber-400 text-sm font-semibold tracking-wide uppercase shadow-[0_0_10px_rgba(245,158,11,0.1)] gap-2">
                <span>Desde 1854</span>
                <span class="hidden sm:inline text-amber-500/50">•</span>
                <span>{{ date('Y') - 1854 + 1 }} años de historia</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold mb-6 tracking-tight">
                El Alma Sonora de <br/>
                <span class="text-gold">Moratalla</span>
            </h1>
            
            <p class="text-lg md:text-2xl text-gray-300 mb-10 leading-relaxed font-light max-w-2xl mx-auto">
                Acompañamos cada momento especial de nuestro pueblo con pasión, dedicación y excelencia musical.
            </p>

            <div class="flex flex-col sm:flex-row gap-5 justify-center">
                <a href="#historia" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-full text-gray-950 bg-gradient-to-r from-amber-400 to-amber-600 hover:from-amber-300 hover:to-amber-500 transition-all transform hover:scale-105 hover:-translate-y-1 shadow-[0_10px_30px_rgba(245,158,11,0.3)]">
                    Descubre nuestra historia
                </a>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-amber-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>


    <!-- Historia Section -->
    <section id="historia" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-bold mb-4">Historia de la Banda</h2>
                <div class="h-1 w-20 bg-amber-500 mx-auto rounded-full"></div>
            </div>

            <div class="glass-panel p-8 rounded-2xl flex flex-col md:flex-row gap-8 items-start">
                @if(isset($bandHistoryImages) && $bandHistoryImages->count() > 0)
                    <div class="w-full md:w-1/3 flex-shrink-0" x-data="{ openLightbox: false, activeSlide: 0, slides: {{ json_encode($bandHistoryImages->map(function($i) { return ['url' => $i->url, 'desc' => $i->description]; })) }} }">
                        <div class="relative rounded-xl overflow-hidden shadow-lg border border-gray-800 cursor-pointer group" @click="openLightbox = true; activeSlide = 0">
                            <img src="{{ $bandHistoryImages->first()->url }}" alt="Historia" class="w-full aspect-[4/3] object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors"></div>
                            
                            @if($bandHistoryImages->count() > 1)
                                <div class="absolute bottom-4 right-4 bg-amber-600/90 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-sm font-semibold flex items-center shadow-lg">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Ver {{ $bandHistoryImages->count() }} fotos
                                </div>
                            @endif
                        </div>
                        
                        <template x-teleport="body">
                            <div x-show="openLightbox" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-sm" style="display: none;" @keydown.escape.window="openLightbox = false" @keydown.right.window="activeSlide = (activeSlide + 1) % slides.length" @keydown.left.window="activeSlide = (activeSlide - 1 + slides.length) % slides.length">
                                <button @click="openLightbox = false" class="absolute top-6 right-6 text-white/70 hover:text-white bg-black/50 hover:bg-amber-600 rounded-full p-2 transition-colors z-[110]">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                
                                <button x-show="slides.length > 1" @click="activeSlide = (activeSlide - 1 + slides.length) % slides.length" class="absolute left-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-black/50 hover:bg-amber-600 rounded-full p-3 transition-colors z-[110]">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                
                                <button x-show="slides.length > 1" @click="activeSlide = (activeSlide + 1) % slides.length" class="absolute right-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-black/50 hover:bg-amber-600 rounded-full p-3 transition-colors z-[110]">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>

                                <div class="w-full h-full flex flex-col items-center justify-center">
                                    <template x-for="(slide, index) in slides" :key="index">
                                        <div x-show="activeSlide === index" x-transition.opacity.duration.300ms class="absolute inset-0 flex flex-col items-center justify-center p-4 md:p-12 z-[105]">
                                            <img :src="slide.url" class="max-h-[75vh] max-w-full object-contain rounded-lg shadow-2xl">
                                            <p x-show="slide.desc" class="mt-4 text-white text-base md:text-lg font-medium text-center bg-black/70 px-6 py-2 rounded-full backdrop-blur-sm" x-text="slide.desc"></p>
                                        </div>
                                    </template>
                                </div>
                                
                                <div x-show="slides.length > 1" class="absolute bottom-8 left-1/2 -translate-x-1/2 flex space-x-3 bg-black/40 px-4 py-2 rounded-full backdrop-blur-sm z-[110]">
                                    <template x-for="(_, index) in slides" :key="index">
                                        <button @click="activeSlide = index" class="w-3 h-3 rounded-full transition-colors" :class="activeSlide === index ? 'bg-amber-500' : 'bg-white/40 hover:bg-white/60'"></button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                @endif

                <div class="prose prose-invert prose-amber flex-1 max-w-none text-gray-300 leading-relaxed">
                    @if(empty($band_history))
                        <p class="text-gray-400 italic text-center">La historia de la banda aún no ha sido publicada.</p>
                    @else
                        {!! $band_history !!}
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Noticias Section -->
    <section id="noticias" class="py-24 relative bg-gray-950/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-bold mb-4">Últimas Noticias</h2>
                <div class="h-1 w-20 bg-amber-500 mx-auto rounded-full"></div>
                <p class="mt-6 text-gray-400 max-w-2xl mx-auto text-lg">
                    Mantente informado sobre nuestros conciertos, eventos y novedades de la banda.
                </p>
            </div>

            @if($news->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($news as $item)
                        <div x-data="{ openNews: false, activeNewsSlide: 0, newsSlides: {{ json_encode($item->newsImages->map(function($i) { return ['url' => $i->url, 'desc' => $i->description]; })) }} }" class="glass-panel rounded-2xl overflow-hidden hover:shadow-[0_0_30px_rgba(245,158,11,0.1)] transition-all duration-500 transform hover:-translate-y-2 flex flex-col cursor-pointer group" @click="openNews = true">
                            @if($item->mainImage)
                                <div class="h-48 w-full overflow-hidden relative">
                                    <img src="{{ $item->mainImage->url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors"></div>
                                </div>
                            @endif
                            <div class="p-6 md:p-8 flex-1 flex flex-col">
                                <div class="text-xs text-amber-500 font-semibold tracking-wide uppercase mb-3">
                                    {{ $item->created_at->format('d/m/Y') }}
                                </div>
                                <h3 class="text-xl font-bold text-white mb-4 line-clamp-2 group-hover:text-amber-400 transition-colors">{{ $item->title }}</h3>
                                <p class="text-gray-400 leading-relaxed line-clamp-3 mb-6 flex-1">
                                    {{ Str::limit(strip_tags($item->content), 120) }}
                                </p>
                                @if($item->event_date)
                                <div class="mt-auto pt-4 border-t border-gray-800 flex items-center text-sm text-gray-300">
                                    <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Evento: {{ $item->event_date->format('d/m/Y') }}
                                </div>
                                @endif
                                <div class="mt-4 text-amber-500 text-sm font-semibold flex items-center group-hover:translate-x-2 transition-transform">
                                    Leer más <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </div>
                            </div>
                            
                            <!-- Modal Noticia -->
                            <template x-teleport="body">
                                <div x-show="openNews" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" style="display: none;">
                                    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="openNews = false"></div>
                                    <div class="relative bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden" @keydown.escape.window="openNews = false">
                                        <!-- Header Modal -->
                                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
                                            <h3 class="text-xl font-bold text-white truncate pr-4">{{ $item->title }}</h3>
                                            <button @click="openNews = false" class="text-gray-400 hover:text-white transition-colors bg-gray-800 p-2 rounded-full hover:bg-gray-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                        
                                        <!-- Body Modal -->
                                        <div class="p-6 overflow-y-auto">
                                            @if($item->newsImages->count() > 0)
                                                <!-- Carrusel dentro de modal -->
                                                <div class="relative rounded-xl overflow-hidden bg-black mb-8 aspect-video flex items-center justify-center group/carousel">
                                                    <template x-for="(slide, index) in newsSlides" :key="index">
                                                        <div x-show="activeNewsSlide === index" x-transition.opacity class="absolute inset-0 flex flex-col items-center justify-center">
                                                            <img :src="slide.url" class="max-w-full max-h-full object-contain">
                                                            <p x-show="slide.desc" class="absolute bottom-4 text-white text-sm md:text-base bg-black/70 px-4 py-1.5 rounded-full backdrop-blur-sm" x-text="slide.desc"></p>
                                                        </div>
                                                    </template>
                                                    
                                                    <button x-show="newsSlides.length > 1" @click.stop="activeNewsSlide = (activeNewsSlide - 1 + newsSlides.length) % newsSlides.length" class="absolute left-2 text-white/70 hover:text-white bg-black/50 hover:bg-amber-600 rounded-full p-2 transition-colors opacity-0 group-hover/carousel:opacity-100">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                                    </button>
                                                    
                                                    <button x-show="newsSlides.length > 1" @click.stop="activeNewsSlide = (activeNewsSlide + 1) % newsSlides.length" class="absolute right-2 text-white/70 hover:text-white bg-black/50 hover:bg-amber-600 rounded-full p-2 transition-colors opacity-0 group-hover/carousel:opacity-100">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                    </button>
                                                    
                                                    <div x-show="newsSlides.length > 1" class="absolute bottom-2 flex space-x-2">
                                                        <template x-for="(_, index) in newsSlides" :key="index">
                                                            <button @click.stop="activeNewsSlide = index" class="w-2 h-2 rounded-full transition-colors" :class="activeNewsSlide === index ? 'bg-amber-500' : 'bg-white/40'"></button>
                                                        </template>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="prose prose-invert prose-amber max-w-none">
                                                <div class="flex items-center gap-4 text-sm text-gray-400 mb-6 border-b border-gray-800 pb-4">
                                                    <span>📅 Publicado: {{ $item->created_at->format('d/m/Y') }}</span>
                                                    @if($item->event_date)
                                                        <span class="text-amber-500 font-medium">🎯 Evento: {{ $item->event_date->format('d/m/Y') }}</span>
                                                    @endif
                                                </div>
                                                {!! $item->content !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-12 glass-panel rounded-2xl">
                    <p>No hay noticias disponibles en este momento.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Archivo Sonoro Section -->
    <section id="archivo-sonoro" class="py-24 relative bg-gray-950">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-bold mb-4">Archivo Sonoro</h2>
                <div class="h-1 w-20 bg-amber-500 mx-auto rounded-full"></div>
                <p class="text-gray-400 mt-4 max-w-2xl mx-auto">Disfruta de nuestras interpretaciones y actuaciones multimedia.</p>
            </div>
            
            @if(isset($mediaArchives) && $mediaArchives->count() > 0)
                <div class="flex overflow-x-auto gap-6 pb-8 snap-x snap-mandatory scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-900" style="scrollbar-width: thin;">
                    @foreach($mediaArchives as $media)
                        <div class="glass-panel rounded-2xl overflow-hidden hover:-translate-y-1 transition-all duration-300 group flex flex-col flex-none w-[75vw] sm:w-[280px] lg:w-[300px] snap-center">
                            @if($media->images->count() > 0)
                                <!-- Carrusel de Imágenes -->
                                <div class="relative aspect-video bg-gray-900" x-data="{ activeSlide: 1, totalSlides: {{ $media->images->count() }} }">
                                    @foreach($media->images as $index => $image)
                                        <div x-show="activeSlide === {{ $index + 1 }}" class="absolute inset-0 transition-opacity duration-500 ease-in-out">
                                            <img src="{{ asset('storage/' . $image->file_path) }}" class="w-full h-full object-cover select-none pointer-events-none" oncontextmenu="return false;" draggable="false">
                                        </div>
                                    @endforeach
                                    
                                    @if($media->images->count() > 1)
                                        <button @click="activeSlide = activeSlide > 1 ? activeSlide - 1 : totalSlides" class="absolute left-2 top-1/2 -translate-y-1/2 p-2 bg-black/50 text-white rounded-full hover:bg-black/80 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                        </button>
                                        <button @click="activeSlide = activeSlide < totalSlides ? activeSlide + 1 : 1" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-black/50 text-white rounded-full hover:bg-black/80 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </button>
                                        <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1">
                                            @foreach($media->images as $index => $image)
                                                <button @click="activeSlide = {{ $index + 1 }}" :class="{'bg-amber-500': activeSlide === {{ $index + 1 }}, 'bg-white/50': activeSlide !== {{ $index + 1 }}}" class="w-2 h-2 rounded-full transition-colors"></button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="relative aspect-video bg-black/50 flex items-center justify-center shrink-0">
                                @if($media->type === 'video')
                                    <video src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-full object-cover" controls preload="metadata" controlsList="nodownload" oncontextmenu="return false;"></video>
                                @else
                                    <!-- Audio placeholder -->
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-amber-900/40 to-black/80">
                                        <svg class="w-16 h-16 text-amber-500/80 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                                        <audio src="{{ asset('storage/' . $media->file_path) }}" class="w-11/12 mt-2 h-10" controls preload="metadata" controlsList="nodownload" oncontextmenu="return false;"></audio>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="text-lg font-bold text-white mb-2">{{ $media->title }}</h3>
                                
                                @if($media->composer || $media->music_type || $media->performance_date)
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @if($media->music_type)
                                            <span class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-0.5 text-xs font-medium text-blue-400 border border-blue-400/20">{{ $media->music_type }}</span>
                                        @endif
                                        @if($media->composer)
                                            <span class="inline-flex items-center rounded-md bg-gray-400/10 px-2 py-0.5 text-xs font-medium text-gray-300"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> {{ $media->composer }}</span>
                                        @endif
                                        @if($media->performance_date)
                                            <span class="inline-flex items-center rounded-md bg-gray-400/10 px-2 py-0.5 text-xs font-medium text-gray-300"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ $media->performance_date->format('d/m/Y') }}</span>
                                        @endif
                                    </div>
                                @endif

                                @if($media->description)
                                    <p class="text-sm text-gray-400 line-clamp-3 mt-auto">{{ $media->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <p class="text-gray-500 text-lg">Próximamente compartiremos nuestro archivo sonoro y visual.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-800 bg-gray-950 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-6 md:mb-0">
                <div class="flex items-center gap-4">
                    <x-logo-rotator class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full shrink-0 overflow-hidden" />
                    <div class="flex flex-col">
                        <div class="flex flex-col leading-none">
                            <span class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600">{{ $globalBandName }}</span>
                        </div>
                        <span class="text-xs sm:text-sm lg:text-base font-medium italic tracking-widest mt-1 animate-slogan">{{ \App\Models\SiteSetting::getSetting('site_slogan', 'Tu banda') }}</span>
                    </div>
                </div>
                <p class="text-gray-500 mt-2 text-sm">Cultura y tradición musical en el Noroeste Murciano.</p>
                <div class="mt-4 flex items-center gap-2 text-sm text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <a href="mailto:bandamusicademoratalla@gmail.com" class="hover:text-amber-500 transition-colors">bandamusicademoratalla@gmail.com</a>
                </div>
            </div>
            <div class="flex space-x-6 mt-6 md:mt-0">
                <!-- Social links placeholders -->
                <a href="https://www.facebook.com/share/19TzkksMKa/" target="_blank" class="text-gray-400 hover:text-amber-500 transition-colors">
                    <span class="sr-only">Facebook</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                </a>
                <a href="https://www.instagram.com/bandamusicademoratalla?igsi=cnU2c2Rpc2d6N2pp" target="_blank" class="text-gray-400 hover:text-amber-500 transition-colors">
                    <span class="sr-only">Instagram</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>
                </a>
            </div>
        </div>
        <div class="mt-8 border-t border-gray-900 pt-8 flex flex-col md:flex-row justify-between items-center max-w-7xl mx-auto px-6 lg:px-8 text-sm text-gray-600">
            <p>&copy; {{ date('Y') }} Asociación Banda de Música de Moratalla. Todos los derechos reservados. <span class="block sm:inline mt-2 sm:mt-0 sm:ml-2">Diseñado por <a href="https://www.moratalla-murcia.com" target="_blank" class="text-amber-500 hover:text-amber-400 transition-colors">www.moratalla-murcia.com</a></span></p>
            <div class="mt-4 md:mt-0 space-x-4 flex items-center">
                <span class="text-gray-700 text-xs mr-2" title="Visitas">{{ number_format($visit_count ?? 0) }}</span>
                <a href="{{ route('estatutos') }}" class="hover:text-amber-500">Estatutos</a>
                <a href="{{ route('legal') }}" class="hover:text-amber-500">Aviso Legal</a>
                <a href="{{ route('legal') }}" class="hover:text-amber-500">Privacidad</a>
            </div>
        </div>
    </footer>
</body>
</html>
