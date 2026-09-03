<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <style>
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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-100 antialiased bg-gray-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            <!-- Decorative background gradient -->
            <div class="absolute inset-0 z-0 opacity-30">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-amber-600 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
                <div class="absolute top-0 right-1/4 w-96 h-96 bg-yellow-600 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
            </div>

            <div class="relative z-10 text-center mb-6">
                <a href="/" class="flex flex-col items-center justify-center">
                    <x-logo-rotator class="h-32 w-32 mb-4" />
                    <h1 class="font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-600 tracking-tight leading-tight">
                        <span class="text-2xl">Banda de Música</span><br>
                        <span class="text-5xl uppercase tracking-widest">Moratalla</span>
                    </h1>
                    <span class="text-sm sm:text-lg font-medium italic tracking-widest mt-3 animate-slogan">{{ \App\Models\SiteSetting::getSetting('site_slogan', 'Tu banda') }}</span>
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-2 px-8 py-10 bg-gray-900 border border-gray-800 shadow-2xl overflow-hidden sm:rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
