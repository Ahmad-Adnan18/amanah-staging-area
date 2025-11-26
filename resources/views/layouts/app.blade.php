<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#e31a1aff">
    <meta name="view-transition" content="same-origin" />

    <title>{{ config('app.name', 'Amanah') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="manifest" href="/manifest.json">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pulltorefreshjs/0.1.22/index.umd.min.js"></script>

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .bg-gradient-time-based {
            background: linear-gradient(135deg,
                    #fef7f7 0%,
                    #fdf2f2 25%,
                    #fce8e8 50%,
                    #fde8e8 75%,
                    #fef7f7 100%);
            background-attachment: fixed;
        }

        .bg-gradient-night {
            background: linear-gradient(135deg,
                    #f5f5f5 0%,
                    #f0f0f0 25%,
                    #ebebeb 50%,
                    #f0f0f0 75%,
                    #f5f5f5 100%);
            background-attachment: fixed;
        }

        .header-safe-area {
            padding-top: env(safe-area-inset-top);
            padding-top: constant(safe-area-inset-top);
            height: auto;
        }

        .overflow-hidden {
            overflow: hidden;
        }

        /* Tambahkan Class Khusus Ini */
        .glass-overlay {
            /* Warna dasar hitam transparan (30% supaya blur lebih kelihatan) */
            background-color: rgba(0, 0, 0, 0.1);

            /* Efek Blur Standar */
            backdrop-filter: blur(20px);

            /* Efek Blur KHUSUS iPhone/Safari (Wajib) */
            -webkit-backdrop-filter: blur(20px);
        }

        window.closeProfilePreview=function() {
            const modal=document.getElementById('profile-preview-modal');

            if (modal) {
                modal.classList.add('hidden'); // Pakai class hidden
                // Hapus property overflow dari body
                document.body.style.overflow='';
            }
        }

        /* Sembunyikan Scrollbar untuk HTML dan Body (Wajib untuk Capacitor) */
        html,
        body {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE/Edge */
        }

        /* Sembunyikan Webkit Scrollbar (Chrome/Safari/Android WebView) */
        ::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
            background: transparent;
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }

        /* 1. MATIKAN SELEKSI TEKS DI SELURUH APLIKASI */
        body {
            -webkit-user-select: none;
            /* Safari / Chrome */
            -moz-user-select: none;
            /* Firefox */
            -ms-user-select: none;
            /* IE / Edge */
            user-select: none;
            /* Standar Modern */

            /* 2. MATIKAN WARNA BIRU SAAT KLIK (TAP HIGHLIGHT) */
            /* Ini biar pas tombol diklik gak ada kedip biru */
            -webkit-tap-highlight-color: transparent;
            /* Mencegah efek mental/bouncing saat scroll mentok */
            overscroll-behavior-y: none;
        }

        /* 3. PENTING: KEMBALIKAN SELEKSI UNTUK FORM INPUT */
        /* Kalau ini lupa, user gak bakal bisa ngetik/edit teks di form! */
        input,
        textarea,
        [contenteditable] {
            -webkit-user-select: text;
            user-select: text;
        }

    </style>
</head>
<body class="font-sans antialiased bg-gradient-modern">
    <div class="min-h-screen">
        <div x-data="{ sidebarOpen: false }">
            @include('layouts.navigation')

            <main class="md:ml-64">
                <header class="md:hidden sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-slate-200 header-safe-area">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="flex h-16 items-center justify-between">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                                <img src="{{ asset('images/logo-kunka-merah.png') }}" alt="Logo" class="block h-8 w-auto">
                                <span class="text-lg font-bold tracking-wider text-gray-800">{{ config('app.name', 'Amanah') }}</span>
                            </a>

                            <div x-data="profilePreviewHandler()" @touchstart.passive="startPress" @touchend.passive="endPress" @touchcancel.passive="cancelPress" @contextmenu.prevent="" class="flex items-center cursor-pointer select-none">
                                <img src="{{ Auth::user()->profile_photo ? Storage::url(Auth::user()->profile_photo) : asset('images/default-profile.png') }}" alt="Foto Profil" class="w-10 h-10 rounded-full object-cover border-2 border-red-700 transition-all duration-200" :class="isLongPressActive ? 'scale-95 border-blue-500 ring-4 ring-blue-200' : ''">
                            </div>
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


    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // 1. Fungsi Global untuk menutup modal (agar bisa dipanggil dari onclick HTML)
        window.closeProfilePreview = function() {
            const modal = document.getElementById('profile-preview-modal');
            if (modal) {
                modal.classList.add('hidden'); // Pakai class hidden
                // Hapus property overflow dari body
                document.body.style.overflow = '';
            }
        };
        // 2. Logic Handler untuk Foto Profil
        function profilePreviewHandler() {
            return {
                pressTimer: null
                , isLongPressActive: false
                , isMoved: false,

                startPress() {
                    this.isMoved = false;
                    // Timer 500ms untuk trigger long press
                    this.pressTimer = setTimeout(() => {
                        if (!this.isMoved) {
                            this.isLongPressActive = true;
                            this.showPreview();
                            // Efek getar di HP (Haptic Feedback)
                            if (navigator.vibrate) navigator.vibrate(50);
                        }
                    }, 500);
                },

                endPress() {
                    clearTimeout(this.pressTimer);
                    // Jika bukan long press dan tidak digeser, anggap sebagai KLIK BIASA
                    if (!this.isLongPressActive && !this.isMoved) {
                        // Opsional: Redirect ke halaman edit profil
                        // window.location.href = "{{ route('profile.edit') }}";
                    }
                    this.isLongPressActive = false;
                },

                cancelPress() {
                    clearTimeout(this.pressTimer);
                    this.isLongPressActive = false;
                },

                init() {
                    const el = this.$el;
                    // Deteksi pergeseran jari (scroll)
                    el.addEventListener('touchmove', () => {
                        this.isMoved = true;
                        this.cancelPress();
                    }, {
                        passive: true
                    });
                },

                showPreview() {
                    const modal = document.getElementById('profile-preview-modal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        // Kunci scroll body biar ga bisa digeser belakangnya
                        document.body.style.overflow = 'hidden';
                    }
                }
            };
        }

        // 3. Script Ganti Background Berdasarkan Waktu
        document.addEventListener('DOMContentLoaded', function() {
            const hour = new Date().getHours();
            const body = document.body;

            if (hour >= 18 || hour < 6) {
                body.classList.remove('bg-gradient-time-based');
                body.classList.add('bg-gradient-night');
            }
        });

        // 4. Script PullToRefresh untuk Mobile (Capacitor/WebView)
        document.addEventListener('DOMContentLoaded', function() {
            if (window.Capacitor) {
                PullToRefresh.init({
                    mainElement: 'body'
                    , triggerElement: 'body'
                    , onRefresh: function() {
                        window.location.reload();
                    }
                    , distThreshold: 70
                    , iconArrow: '&#8675;'
                    , iconRefreshing: '&hellip;'
                    , instructionsPullToRefresh: 'Tarik untuk segarkan'
                    , instructionsReleaseToRefresh: 'Lepas untuk memuat ulang'
                    , instructionsRefreshing: 'Memuat ulang...'
                });
            }
        });

    </script>

    @yield('scripts')
    @stack('scripts')

    <div id="profile-preview-modal" class="hidden" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999999;">

        <div onclick="window.closeProfilePreview()" style="
            position: absolute; 
            top: -10vh; 
            left: -10vw; 
            width: 120vw; 
            height: 120vh; 
            background-color: rgba(0, 0, 0, 0.25); 
            backdrop-filter: blur(15px); 
            -webkit-backdrop-filter: blur(15px);
            cursor: pointer;
         ">
        </div>

        <div style="
            position: absolute; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%); 
            z-index: 1000000;
            pointer-events: none; /* Agar klik tembus ke background saat meleset dikit */
         ">

            <img src="{{ Auth::user()->profile_photo ? Storage::url(Auth::user()->profile_photo) : asset('images/default-profile.png') }}" alt="Preview Profil" class="rounded-full object-cover shadow-2xl animate-in fade-in zoom-in duration-200" style="
                 width: 65vw; 
                 height: 65vw; 
                 max-width: 300px; 
                 max-height: 300px; 
                 border: 6px solid rgba(131, 0, 0, 0.71);
                 aspect-ratio: 1 / 1;
                 pointer-events: auto; /* Gambar bisa disentuh */
              ">
        </div>
    </div>

</body>
</html>
