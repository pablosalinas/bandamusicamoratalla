<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Banda de Música de Moratalla</title>
    
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
    </style>
</head>
<body class="antialiased bg-gray-950 text-gray-200" x-data="{ scrolled: false, mobileMenuOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <!-- Navigation -->
    <nav :class="{'bg-gray-950/90 backdrop-blur-md shadow-lg border-b border-gray-800': scrolled, 'bg-transparent': !scrolled}" class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-gray-950 shadow-[0_0_15px_rgba(245,158,11,0.4)] shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6 lg:w-7 lg:h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex flex-col lg:flex-row lg:items-baseline leading-none">
                            <span class="text-xl sm:text-2xl lg:text-4xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-gray-100 to-gray-300">Banda de Música</span>
                            <span class="text-xl sm:text-2xl lg:text-4xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600 lg:ml-2">de Moratalla</span>
                        </div>
                        <span class="text-xs sm:text-sm lg:text-lg font-medium text-amber-500/80 italic tracking-widest mt-1">Tu banda</span>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#inicio" class="text-gray-300 hover:text-white transition-colors font-medium">Inicio</a>
                    <a href="#historia" class="text-gray-300 hover:text-white transition-colors font-medium">Historia</a>
                    <a href="#noticias" class="text-gray-300 hover:text-white transition-colors font-medium">Noticias</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-amber-500 hover:text-amber-400 font-semibold transition-colors">Mi Panel</a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 rounded-full border border-amber-500/50 text-amber-500 hover:bg-amber-500 hover:text-gray-900 transition-all font-semibold shadow-[0_0_15px_rgba(245,158,11,0.2)] hover:shadow-[0_0_20px_rgba(245,158,11,0.4)] whitespace-nowrap">Acceso Músicos</a>
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
                @auth
                    <a href="{{ url('/dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-amber-500 hover:text-amber-400 hover:bg-gray-800">Mi Panel</a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-amber-500 hover:text-amber-400 hover:bg-gray-800">Acceso Músicos</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
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
                <a href="#contacto" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-full text-white glass-panel hover:bg-gray-800 transition-all transform hover:-translate-y-1">
                    Únete a la banda
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

    <!-- Junta Directiva / Features Section -->
    <section id="historia" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-bold mb-4">Nuestra Institución</h2>
                <div class="h-1 w-20 bg-amber-500 mx-auto rounded-full"></div>
                <p class="mt-6 text-gray-400 max-w-2xl mx-auto text-lg">
                    Dirigidos por una junta directiva comprometida con la excelencia y apoyados por decenas de músicos locales.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="glass-panel p-8 rounded-2xl hover:shadow-[0_0_30px_rgba(245,158,11,0.1)] transition-all duration-500 transform hover:-translate-y-2 group">
                    <div class="w-14 h-14 bg-amber-500/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-amber-500/20 transition-colors">
                        <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">La Junta Directiva</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Un equipo de apasionados que vela por el buen funcionamiento, la financiación y el futuro de nuestra banda.
                    </p>
                </div>
                <!-- Card 2 -->
                <div class="glass-panel p-8 rounded-2xl hover:shadow-[0_0_30px_rgba(245,158,11,0.1)] transition-all duration-500 transform hover:-translate-y-2 group relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-amber-500/5 rounded-full blur-2xl"></div>
                    <div class="w-14 h-14 bg-amber-500/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-amber-500/20 transition-colors">
                        <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">El Archivo Musical</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Contamos con un extenso archivo de partituras gestionado digitalmente para que nuestros músicos tengan siempre sus papeles al día.
                    </p>
                </div>
                <!-- Card 3 -->
                <div class="glass-panel p-8 rounded-2xl hover:shadow-[0_0_30px_rgba(245,158,11,0.1)] transition-all duration-500 transform hover:-translate-y-2 group">
                    <div class="w-14 h-14 bg-amber-500/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-amber-500/20 transition-colors">
                        <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Últimas Noticias</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Mantente informado de nuestros próximos conciertos, pasacalles, procesiones y nuevas incorporaciones a la banda.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-800 bg-gray-950 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-6 md:mb-0">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-gray-950 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6 lg:w-7 lg:h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex flex-col leading-none">
                            <span class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-gray-100 to-gray-300">Banda de Música</span>
                            <span class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600">de Moratalla</span>
                        </div>
                        <span class="text-xs sm:text-sm lg:text-base font-medium text-amber-500/80 italic tracking-widest mt-1">Tu banda</span>
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
            <div class="mt-4 md:mt-0 space-x-4">
                <a href="{{ route('legal') }}" class="hover:text-amber-500">Aviso Legal</a>
                <a href="{{ route('legal') }}" class="hover:text-amber-500">Privacidad</a>
            </div>
        </div>
    </footer>
</body>
</html>
