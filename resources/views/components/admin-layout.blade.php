<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Banda Moratalla') }} - Administración</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts (Vite con Alpine y Tailwind) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-gray-100 bg-gray-950">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        <!-- Off-canvas menu for mobile -->
        <div x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true" x-cloak>
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/80"></div>
            <div class="fixed inset-0 flex">
                <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative mr-16 flex w-full max-w-xs flex-1">
                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                            <span class="sr-only">Cerrar sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <!-- Sidebar component for mobile -->
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-2 ring-1 ring-white/10">
                        <div class="flex h-16 shrink-0 items-center">
                            <span class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-600">Banda Moratalla</span>
                        </div>
                        <nav class="flex flex-1 flex-col">
                            @include('admin.partials.nav')
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900/50 backdrop-blur-xl border-r border-gray-800 px-6">
                <div class="flex h-20 shrink-0 items-center mt-2">
                    <h1 class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-600 tracking-tight leading-none">
                        Banda de Música<br><span class="text-sm font-medium text-gray-400">Moratalla</span>
                    </h1>
                </div>
                <nav class="flex flex-1 flex-col pb-4">
                    @include('admin.partials.nav')
                </nav>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-1 flex-col lg:pl-72 min-w-0">
            <!-- Topbar -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-800 bg-gray-900/80 backdrop-blur-sm px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" class="-m-2.5 p-2.5 text-gray-400 lg:hidden" @click="sidebarOpen = true">
                    <span class="sr-only">Abrir sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6 justify-end items-center">
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button type="button" class="-m-1.5 flex items-center p-1.5" id="user-menu-button" @click="open = !open">
                                <span class="sr-only">Abrir menú de usuario</span>
                                <div class="h-9 w-9 rounded-full bg-gray-800 flex items-center justify-center border border-gray-700">
                                    <span class="text-sm font-medium leading-none text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-4 text-sm font-semibold leading-6 text-white" aria-hidden="true">{{ Auth::user()->name }}</span>
                                    <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>
                            <!-- Dropdown menu -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-gray-800 py-2 shadow-lg ring-1 ring-white/10 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" x-cloak>
                                <a href="{{ route('profile.edit') }}" class="block px-3 py-1 text-sm leading-6 text-gray-300 hover:text-white hover:bg-gray-700" role="menuitem">Perfil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-3 py-1 text-sm leading-6 text-gray-300 hover:text-white hover:bg-gray-700" role="menuitem">Salir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-950 p-6 lg:p-8">
                <div class="mx-auto max-w-7xl">
                    <!-- Page Header -->
                    @if (isset($header))
                        <header class="mb-8">
                            {{ $header }}
                        </header>
                    @endif

                    <!-- Alerts -->
                    @if (session('success'))
                        <div class="mb-6 rounded-md bg-green-900/50 border border-green-500/30 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-300">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Content -->
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>
