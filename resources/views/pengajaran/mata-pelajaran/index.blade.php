<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-4 sm:py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-6 sm:space-y-8">

                {{-- Header Halaman --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Manajemen Mata Pelajaran</h1>
                            <p class="mt-1 text-slate-600 text-sm sm:text-base">Kelola mata pelajaran dan alokasi jam.</p>
                        </div>
                        <a href="{{ route('pengajaran.mata-pelajaran.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 flex-shrink-0">
                            Tambah Mata Pelajaran
                        </a>
                    </div>
                </div>

                {{-- Panel Notifikasi --}}
                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif
                @if (session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif

                {{-- Dasbor Alokasi JP --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-bold text-slate-800 mb-4">Alokasi Jam Pelajaran (JP)</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="text-xs sm:text-sm text-blue-600 font-semibold uppercase">Jam Efektif</div>
                            <div class="text-2xl sm:text-3xl font-bold text-blue-800 mt-1">{{ $jamEfektif }} JP</div>
                        </div>

                        @foreach ($jpPerTingkat as $tingkat => $totalJp)
                            @php
                                $isPas = ($totalJp == $jamEfektif);
                                $isLebih = ($totalJp > $jamEfektif);
                            @endphp
                            <div class="p-4 rounded-lg border 
                                @if($isPas) bg-green-50 border-green-200 
                                @elseif($isLebih) bg-red-50 border-red-200 
                                @else bg-yellow-50 border-yellow-200 @endif">
                                <div class="text-xs sm:text-sm font-semibold uppercase 
                                    @if($isPas) text-green-600 @elseif($isLebih) text-red-600 @else text-yellow-600 @endif">
                                    Tingkat {{ $tingkat }}
                                </div>
                                <div class="text-2xl sm:text-3xl font-bold mt-1 
                                    @if($isPas) text-green-800 @elseif($isLebih) text-red-800 @else text-yellow-800 @endif">
                                    {{ $totalJp }} JP
                                </div>
                                <p class="text-xs mt-1 
                                    @if($isPas) text-green-500 @elseif($isLebih) text-red-500 @else text-yellow-500 @endif">
                                    @if($isPas) Sempurna!
                                    @elseif($isLebih) +{{ $totalJp - $jamEfektif }} JP
                                    @else -{{ $jamEfektif - $totalJp }} JP
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                {{-- Tabel Daftar Mata Pelajaran --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                    <div class="p-4 sm:p-6 border-b border-slate-200">
                        <span class="text-sm font-medium text-gray-700">Filter berdasarkan tingkatan:</span>
                        <div class="mt-2 flex flex-wrap gap-2">
                             <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ request('tingkatan') == '' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                 Semua
                             </a>
                             @foreach($jpPerTingkat->keys()->sort() as $tingkat)
                                 <a href="{{ route('pengajaran.mata-pelajaran.index', ['tingkatan' => $tingkat]) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ request('tingkatan') == $tingkat ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                     Tingkat {{ $tingkat }}
                                 </a>
                             @endforeach
                        </div>
                    </div>

                    {{-- =============================================================== --}}
                    {{-- AWAL PERUBAHAN: Tampilan Kartu untuk Mobile --}}
                    {{-- 'block sm:hidden' berarti hanya tampil di layar kecil --}}
                    {{-- =============================================================== --}}
                    <div class="block sm:hidden">
                        <div class="divide-y divide-slate-200">
                            @forelse ($mataPelajarans as $mapel)
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <p class="font-bold text-slate-900">{{ $mapel->nama_pelajaran }}</p>
                                            <p class="text-sm text-slate-600">{{ $mapel->kategori }}</p>
                                            <p class="text-xs text-slate-500">Tingkat {{ $mapel->tingkatan }} / {{ $mapel->duration_jp }} JP</p>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <a href="{{ route('pengajaran.mata-pelajaran.edit', $mapel) }}" class="text-red-600 hover:text-red-900 text-sm font-medium">Edit</a>
                                        </div>
                                    </div>

                                </div>
                            @empty
                                <div class="px-6 py-12 text-center text-slate-500">
                                    Tidak ada data mata pelajaran untuk filter ini.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- =============================================================== --}}
                    {{-- AWAL PERUBAHAN: Tampilan Tabel untuk Desktop --}}
                    {{-- 'hidden sm:block' berarti hanya tampil di layar besar --}}
                    {{-- =============================================================== --}}
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama Pelajaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Info</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Ditetapkan</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($mataPelajarans as $mapel)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900">{{ $mapel->nama_pelajaran }}</td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                            <div class="text-sm">{{ $mapel->kategori }}</div>
                                            <div class="text-xs text-slate-500">Tingkat {{ $mapel->tingkatan }} / {{ $mapel->duration_jp }} JP</div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                            <span class="text-xs text-slate-500">Ditetapkan di kelas</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('pengajaran.mata-pelajaran.edit', $mapel) }}" class="text-red-600 hover:text-red-900">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">Tidak ada data mata pelajaran untuk filter ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    {{ $mataPelajarans->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>