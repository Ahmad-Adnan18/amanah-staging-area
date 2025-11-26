<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 font-sans">

        <div class="max-w-md mx-auto w-full space-y-8">
            <div class="text-center">
                <a href="/" class="inline-block transition-transform hover:scale-105 duration-300">
                    <img src="{{ asset('images/logo-kunka-merah.png') }}" alt="Logo" class="h-28 mx-auto drop-shadow-sm">
                </a>

                <div class="mt-6 mb-2 flex justify-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ring-1 ring-inset ring-red-600/20">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        Aplikasi Resmi Pondok
                    </span>
                </div>

                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    Download Aplikasi Android
                </h2>
                <p class="mt-2 text-slate-600">
                    Kemudahan memantau perkembangan santri dalam satu genggaman.
                </p>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-red-600"></div>

                <div class="p-8 space-y-8">

                    <div class="text-center space-y-4">
                        <a href="{{ route('download.apk') }}" class="group relative flex w-full items-center justify-between gap-4 rounded-2xl bg-gradient-to-r from-red-500 via-red-600 to-red-700 px-6 py-4 text-left text-white shadow-lg shadow-emerald-600/25 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-emerald-500/30">
                            <div class="relative inline-flex h-16 w-16 flex-shrink-0 items-center justify-center">
                                <span class="absolute inset-0 rounded-2xl bg-white/20 blur-lg opacity-70 group-hover:opacity-90"></span>
                                <span class="relative inline-flex h-full w-full items-center justify-center rounded-2xl bg-white/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0,0,255.99008,255.99008">
                                        <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                                            <g transform="scale(5.33333,5.33333)">
                                                <path d="M12,29c0,1.1 -0.9,2 -2,2c-1.1,0 -2,-0.9 -2,-2v-9c0,-1.1 0.9,-2 2,-2c1.1,0 2,0.9 2,2zM40,29c0,1.1 -0.9,2 -2,2c-1.1,0 -2,-0.9 -2,-2v-9c0,-1.1 0.9,-2 2,-2c1.1,0 2,0.9 2,2zM22,40c0,1.1 -0.9,2 -2,2c-1.1,0 -2,-0.9 -2,-2v-9c0,-1.1 0.9,-2 2,-2c1.1,0 2,0.9 2,2zM30,40c0,1.1 -0.9,2 -2,2c-1.1,0 -2,-0.9 -2,-2v-9c0,-1.1 0.9,-2 2,-2c1.1,0 2,0.9 2,2z"></path>
                                                <path d="M14,18v15c0,1.1 0.9,2 2,2h16c1.1,0 2,-0.9 2,-2v-15zM24,8c-6,0 -9.7,3.6 -10,8h20c-0.3,-4.4 -4,-8 -10,-8zM20,13.6c-0.6,0 -1,-0.4 -1,-1c0,-0.6 0.4,-1 1,-1c0.6,0 1,0.4 1,1c0,0.5 -0.4,1 -1,1zM28,13.6c-0.6,0 -1,-0.4 -1,-1c0,-0.6 0.4,-1 1,-1c0.6,0 1,0.4 1,1c0,0.5 -0.4,1 -1,1z"></path>
                                                <path d="M28.3,10.5c-0.2,0 -0.4,-0.1 -0.6,-0.2c-0.5,-0.3 -0.6,-0.9 -0.3,-1.4l1.7,-2.5c0.3,-0.5 0.9,-0.6 1.4,-0.3c0.5,0.3 0.6,0.9 0.3,1.4l-1.7,2.5c-0.1,0.3 -0.4,0.5 -0.8,0.5zM19.3,10.1c-0.3,0 -0.7,-0.2 -0.8,-0.5l-1.3,-2.1c-0.3,-0.5 -0.2,-1.1 0.3,-1.4c0.5,-0.3 1.1,-0.2 1.4,0.3l1.3,2.1c0.3,0.5 0.2,1.1 -0.3,1.4c-0.2,0.1 -0.4,0.2 -0.6,0.2z"></path>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                            <div class="flex-1">
                                <span class="inline-flex items-center rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-50">Android Terbaru</span>
                                <p class="mt-2 text-2xl font-bold leading-tight">Download Aplikasi Android</p>
                                <p class="text-sm text-emerald-100">Versi resmi & aman langsung dari Pondok</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 text-white">
                                <svg class="h-6 w-6 transition-transform group-hover:translate-x-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                        <p class="text-xs font-medium text-slate-400">
                            Dipindai Keamanan • Selalu Terbaru • Dukungan TI Pondok
                        </p>
                        <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali ke Halaman Login
                        </a>
                    </div>

                    <div class="border-t border-slate-100"></div>

                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Panduan Instalasi
                        </h3>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 font-bold text-sm border border-red-100">
                                    1
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-slate-900">Unduh File</p>
                                    <p class="text-sm text-slate-500">Klik tombol download di atas dan tunggu hingga selesai.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 font-bold text-sm border border-red-100">
                                    2
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-slate-900">Buka File APK</p>
                                    <p class="text-sm text-slate-500">Ketuk notifikasi unduhan selesai atau cari di folder File Manager.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 font-bold text-sm border border-red-100">
                                    3
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-slate-900">Izinkan Instalasi</p>
                                    <p class="text-sm text-slate-500">Jika muncul peringatan, pilih <strong>Setelan</strong> lalu aktifkan <strong>"Izinkan dari sumber ini"</strong>.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 rounded-xl bg-blue-50 border border-blue-100 flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <p class="text-xs text-blue-700 leading-relaxed">
                                Aplikasi ini dikembangkan secara internal oleh Tim IT Pondok. Dijamin aman untuk perangkat Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <p class="text-sm text-slate-400">
                    &copy; {{ date('Y') }} Pondok Pesantren Kun Karima.
                    <br>All rights reserved.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
