<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-2 px-8 py-10 bg-gray-900 border border-gray-800 shadow-2xl overflow-hidden sm:rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
