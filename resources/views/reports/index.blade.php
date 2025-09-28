<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Pusat Laporan & Export</h1>
                    <p class="mt-1 text-slate-600">Pilih jenis laporan yang ingin Anda lihat atau download.</p>
                </div>

                <!-- [PENYESUAIAN] Daftar Laporan Responsif -->
                <div class="space-y-4">
                    @php
                        // Mendefinisikan kelas dasar untuk konsistensi
                        $listWrapperClass = "bg-white p-4 rounded-2xl shadow-lg border border-slate-200 transition-all duration-300 hover:shadow-xl hover:border-red-300 group flex items-center md:flex-col md:p-6 md:text-center";
                        $iconWrapperBase = "p-3 rounded-full transition-colors duration-300 flex-shrink-0";
                        $iconBase = "h-8 w-8 text-slate-600 transition-colors duration-300";
                        $buttonBase = "mt-4 w-full inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors md:mt-6";
                    @endphp

                    {{-- Laporan Perizinan --}}
                    @can('viewAny', App\Models\Perizinan::class)
                    <div class="{{ $listWrapperClass }}">
                        <div class="{{ $iconWrapperBase }} bg-slate-100 group-hover:bg-red-100">
                            <svg class="{{ $iconBase }} group-hover:text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                        </div>
                        <div class="ml-4 md:ml-0 md:mt-4 flex-grow">
                            <h3 class="font-bold text-lg text-gray-900">Rekap Perizinan</h3>
                            <p class="text-sm text-slate-500 mt-1 md:flex-grow">Lihat semua data perizinan dan export ke Excel.</p>
                        </div>
                        <div class="ml-4 md:ml-0 flex-shrink-0 md:w-full">
                             <a href="{{ route('laporan.perizinan') }}" class="{{ $buttonBase }} bg-red-700 hover:bg-red-600">Buka Laporan</a>
                        </div>
                    </div>
                    @endcan

                    {{-- Laporan Pelanggaran --}}
                    @can('viewAny', App\Models\Pelanggaran::class)
                    <div class="{{ $listWrapperClass }}">
                        <div class="{{ $iconWrapperBase }} bg-slate-100 group-hover:bg-red-100">
                            <svg class="{{ $iconBase }} group-hover:text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                        </div>
                        <div class="ml-4 md:ml-0 md:mt-4 flex-grow">
                            <h3 class="font-bold text-lg text-gray-900">Rekap Pelanggaran</h3>
                            <p class="text-sm text-slate-500 mt-1 md:flex-grow">Lihat semua catatan pelanggaran dan export ke Excel.</p>
                        </div>
                         <div class="ml-4 md:ml-0 flex-shrink-0 md:w-full">
                            <a href="{{ route('laporan.pelanggaran') }}" class="{{ $buttonBase }} bg-red-700 hover:bg-red-600">Buka Laporan</a>
                        </div>
                    </div>
                    @endcan

                    {{-- Export Data Santri --}}
                    <div class="{{ $listWrapperClass }}">
                        <div class="{{ $iconWrapperBase }} bg-slate-100 group-hover:bg-green-100">
                            <svg class="{{ $iconBase }} group-hover:text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        </div>
                        <div class="ml-4 md:ml-0 md:mt-4 flex-grow">
                            <h3 class="font-bold text-lg text-gray-900">Data Induk Santri</h3>
                            <p class="text-sm text-slate-500 mt-1 md:flex-grow">Download daftar lengkap semua santri yang terdaftar.</p>
                        </div>
                        <div class="ml-4 md:ml-0 flex-shrink-0 md:w-full">
                            <a href="{{ route('laporan.santri.export') }}" class="{{ $buttonBase }} bg-green-600 hover:bg-green-500">Download Excel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
