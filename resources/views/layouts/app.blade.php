<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#e31a1aff">

    <title>{{ config('app.name', 'Amanah') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="manifest" href="/manifest.json">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">


    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <!-- Scripts Vite -->
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])

    <style>
        /* Gradasi berdasarkan waktu */
        .bg-gradient-time-based {
            background: linear-gradient(135deg,
                    #fef7f7 0%,
                    #fdf2f2 25%,
                    #fce8e8 50%,
                    #fde8e8 75%,
                    #fef7f7 100%);
            background-attachment: fixed;
        }

        /* Untuk malam hari - lebih gelap */
        .bg-gradient-night {
            background: linear-gradient(135deg,
                    #f5f5f5 0%,
                    #f0f0f0 25%,
                    #ebebeb 50%,
                    #f0f0f0 75%,
                    #f5f5f5 100%);
            background-attachment: fixed;
        }

    </style>
</head>
<body class="font-sans antialiased bg-gradient-modern">
    <div class="min-h-screen">
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

                            <!-- Foto Profil -->
                            <a href="{{ route('profile.edit') }}" class="flex items-center">
                                <img src="{{ Auth::user()->profile_photo ? Storage::url(Auth::user()->profile_photo) : asset('images/default-profile.png') }}" alt="Foto Profil" class="w-10 h-10 rounded-full object-cover border-2 border-red-700 hover:border-red-600 transition-colors">
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

    <!-- External JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Mengubah gradasi berdasarkan waktu
        document.addEventListener('DOMContentLoaded', function() {
            const hour = new Date().getHours();
            const body = document.body;

            if (hour >= 18 || hour < 6) {
                // Malam hari
                body.classList.remove('bg-gradient-time-based');
                body.classList.add('bg-gradient-night');
            }
        });

    </script>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
