<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                    Pusat Laporan & Export
                </h1>
                <p class="mt-2 text-slate-600 text-sm sm:text-base">Pilih jenis laporan yang ingin Anda lihat atau download</p>
            </div>

            {{-- Daftar Laporan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Laporan Perizinan --}}
                @can('viewAny', App\Models\Perizinan::class)
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 transition-all duration-300 hover:shadow-xl group">
                    {{-- Icon --}}
                    <div class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110 bg-red-100 text-red-600 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"></path>
                        </svg>
                    </div>

                    {{-- Content --}}
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Rekap Perizinan</h3>
                    <p class="text-slate-600 text-sm mb-4">Lihat semua data perizinan dan export ke Excel</p>

                    {{-- Button --}}
                    <a href="{{ route('laporan.perizinan') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-300 transform hover:scale-105">
                        Buka Laporan
                    </a>
                </div>
                @endcan

                {{-- Laporan Pelanggaran --}}
                @can('viewAny', App\Models\Pelanggaran::class)
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 transition-all duration-300 hover:shadow-xl group">
                    {{-- Icon --}}
                    <div class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110 bg-amber-100 text-amber-600 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                        </svg>
                    </div>

                    {{-- Content --}}
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Rekap Pelanggaran</h3>
                    <p class="text-slate-600 text-sm mb-4">Lihat semua catatan pelanggaran dan export ke Excel</p>

                    {{-- Button --}}
                    <a href="{{ route('laporan.pelanggaran') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-all duration-300 transform hover:scale-105">
                        Buka Laporan
                    </a>
                </div>
                @endcan

                {{-- Export Data Santri --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 transition-all duration-300 hover:shadow-xl group">
                    {{-- Icon --}}
                    <div class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110 bg-green-100 text-green-600 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path>
                        </svg>
                    </div>

                    {{-- Content --}}
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Data Induk Santri</h3>
                    <p class="text-slate-600 text-sm mb-4">Download daftar lengkap semua santri yang terdaftar</p>

                    {{-- Button --}}
                    <a href="{{ route('laporan.santri.export') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all duration-300 transform hover:scale-105">
                        Download Excel
                    </a>
                </div>
            </div>

            {{-- Additional Information --}}
            <div class="mt-8 bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-2">Informasi Laporan</h3>
                        <p class="text-slate-600 text-sm">
                            Semua laporan dapat di-export dalam format Excel untuk keperluan dokumentasi dan analisis lebih lanjut.
                            Pastikan data yang akan di-export sesuai dengan periode yang diinginkan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }

        .transform {
            transition: all 0.3s ease-out;
        }

    </style>
</x-app-layout>
