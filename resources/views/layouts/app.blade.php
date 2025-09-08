<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Amanah') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
    
    
</head>
<body x-data="{ sidebarOpen: false }" class="font-sans antialiased">
    <div class="min-h-screen bg-slate-50">
        {{-- Memuat file navigasi hibrida yang baru --}}
        @include('layouts.navigation')

        {{-- Konten utama akan bergeser ke kanan di desktop untuk memberi ruang pada sidebar --}}
        <div class="transition-all duration-300 ease-in-out md:ml-64">
            <!-- Page Content -->
            <main>
                {{ $slot }}
                
                <!-- Spacer untuk Konten agar tidak tertutup Bottom Nav Bar -->
                <div class="h-16 md:hidden"></div>
            </main>
        </div>
    </div>
    @stack('scripts')
    
    
    @vite(['resources/js/app.js'])

    {{-- Penambahan 'defer' membuat script dieksekusi setelah HTML selesai di-parse --}}
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
</body>
</html>

