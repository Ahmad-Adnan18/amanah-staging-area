<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Amanah') }}</title>
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- [TAMBAHAN] Memuat CSS untuk TomSelect -->
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

        <!-- Scripts Vite -->
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-slate-50">
            <div x-data="{ sidebarOpen: false }">
                @include('layouts.navigation')

                <main class="md:ml-64">
                    <header class="md:hidden sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-slate-200">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <div class="flex h-16 items-center justify-between">
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                                    <img src="{{ asset('images/logo-kunka-merah.png') }}" alt="Logo" class="block h-8 w-auto">
                                    <span class="text-lg font-bold tracking-wider text-gray-800">{{ config('app.name', 'Amanah') }}</span>
                                </a>
                            </div>
                        </div>
                    </header>

                    <div>
                        <div class="pb-20 md:pb-0">
                             {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
        
        <!-- [TAMBAHAN] Memuat JavaScript untuk TomSelect -->
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

        <!-- Script untuk plugin Alpine.js -->
        <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    </body>
</html>

