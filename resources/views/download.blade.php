<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 font-sans">

        <div class="max-w-md mx-auto w-full space-y-8">
            <div class="text-center">
                <a href="/" class="inline-block transition-transform hover:scale-105 duration-300">
                    <img src="{{ asset('images/logo-kunka-merah1.png') }}" alt="Logo" class="h-28 mx-auto drop-shadow-sm">
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

                    <div class="text-center space-y-3">
                        <a href="{{ route('download.apk') }}" class="group relative flex items-center justify-center w-full px-6 py-4 text-lg font-bold text-white transition-all duration-200 bg-red-600 rounded-2xl hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-500/30 shadow-lg shadow-red-600/20">
                            <svg class="w-6 h-6 mr-3 transition-transform group-hover:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download Sekarang (.APK)
                        </a>
                        <p class="text-xs text-slate-400">
                            Aman • Bebas Virus • Resmi
                        </p>
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
