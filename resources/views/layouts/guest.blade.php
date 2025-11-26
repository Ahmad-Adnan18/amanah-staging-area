<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin" />

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* TAMBAHAN BARU: Agar header turun otomatis */
        .header-safe-area {
            padding-top: env(safe-area-inset-top);
            padding-top: constant(safe-area-inset-top);
            /* Support HP lama */
            height: auto;
            /* Biar tingginya menyesuaikan padding */
        }

    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
    <header class="md:hidden sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-slate-200 header-safe-area"></header>
    {{-- Layout ini hanya menyediakan slot tanpa styling tambahan --}}
    {{ $slot }}
</body>
</html>
