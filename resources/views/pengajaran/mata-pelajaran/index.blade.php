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
                @if (session('success') || session('error'))
                    @php
                        $type = session('success') ? 'success' : 'error';
                        $message = session('success') ?? session('error');
                        
                        $typeClasses = [
                            'success' => 'bg-green-100 border-green-400 text-green-700',
                            'error' => 'bg-red-100 border-red-400 text-red-700',
                        ];
                        
                        $iconPaths = [
                            'success' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                            'error' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
                        ];
                
                        $hoverClasses = [
                            'success' => 'hover:bg-green-200',
                            'error' => 'hover:bg-red-200',
                        ];
                    @endphp
                
                    <div id="notification-panel" class="relative rounded-lg border-l-4 p-4 {{ $typeClasses[$type] }}" role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPaths[$type] }}" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="font-bold">
                                    {{ $type === 'success' ? 'Berhasil!' : 'Terjadi Kesalahan!' }}
                                </p>
                                <p class="text-sm">{{ $message }}</p>
                            </div>
                            <button 
                                type="button" 
                                class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 {{ $hoverClasses[$type] }} inline-flex h-8 w-8" 
                                onclick="document.getElementById('notification-panel').style.display='none'"
                                aria-label="Close">
                                <span class="sr-only">Dismiss</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
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
                                    <div>
                                        <p class="text-xs font-medium text-slate-500 mb-1">Guru Pengampu:</p>
                                        <div class="flex flex-wrap gap-1">
                                            @forelse ($mapel->teachers as $teacher)
                                                <span class="inline-block bg-slate-100 text-slate-700 text-xs font-medium px-2 py-0.5 rounded-full">
                                                    {{ $teacher->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-red-500 italic">Belum ada</span>
                                            @endforelse
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
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Guru Pengampu</th>
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

                                        <td class="px-6 py-4 text-slate-600 max-w-xs">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse ($mapel->teachers as $teacher)
                                                    <span class="inline-block bg-slate-100 text-slate-700 text-xs font-medium px-2 py-0.5 rounded-full">
                                                        {{ $teacher->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-red-500 italic">Belum ada</span>
                                                @endforelse
                                            </div>
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