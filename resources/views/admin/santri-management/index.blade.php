<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">
            <div class="space-y-4 sm:space-y-6" x-data="santriManagementData()" x-init="initData()" x-cloak>

                {{-- [BAGIAN 1] HEADER HALAMAN --}}
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold tracking-tight text-gray-900 truncate">Pusat Manajemen Santri</h1>
                            <p class="mt-1 text-xs sm:text-sm text-slate-600 max-w-2xl">Cari, filter, dan kelola semua data santri di pondok.</p>
                        </div>
                        <div class="flex items-center space-x-2 sm:space-x-3 flex-shrink-0">
                            {{-- Tombol Tambah Manual --}}
                            <a href="{{ route('pengajaran.kelas.index') }}" class="group inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium bg-white border border-slate-200 rounded-lg sm:rounded-md text-slate-700 shadow-sm hover:shadow-md hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-200 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-2 text-slate-500 group-hover:text-slate-700 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span class="truncate">Tambah Manual</span>
                            </a>

                            {{-- Tombol Import Excel --}}
                            <a href="{{ route('admin.santri-management.import.show') }}" class="group inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold bg-red-600 hover:bg-red-700 text-white rounded-lg sm:rounded-md shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span class="truncate">Import Excel</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- [BAGIAN 2] KARTU STATISTIK --}}
                <div class="grid grid-cols-2 gap-3 sm:gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    @php
                    $statConfigs = [
                    [
                    'count' => $stats['total'] ?? 0,
                    'label' => 'Total Santri',
                    'icon' => 'slate',
                    'svg' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.5-2.962a3.75 3.75 0 015.968 0 3.75 3.75 0 01-5.968 0zM21 12.75A9 9 0 113 12.75v6.75a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 19.5v-6.75z',
                    'isWarning' => false
                    ],
                    [
                    'count' => $stats['putra'] ?? 0,
                    'label' => 'Jumlah Putra',
                    'icon' => 'slate',
                    'svg' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
                    'isWarning' => false
                    ],
                    [
                    'count' => $stats['putri'] ?? 0,
                    'label' => 'Jumlah Putri',
                    'icon' => 'slate',
                    'svg' => 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25',
                    'isWarning' => false
                    ],
                    [
                    'count' => $stats['tanpa_nis'] ?? 0,
                    'label' => 'Tanpa NIS',
                    'icon' => 'amber',
                    'svg' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
                    'isWarning' => true
                    ],
                    [
                    'count' => $stats['tanpa_rayon'] ?? 0,
                    'label' => 'Tanpa Rayon',
                    'icon' => 'amber',
                    'svg' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
                    'isWarning' => true
                    ]
                    ];
                    @endphp

                    @foreach($statConfigs as $stat)
                    @if(isset($stat['isWarning']) && $stat['isWarning'])
                    {{-- Warning Card --}}
                    <div class="bg-amber-50 p-3 sm:p-4 lg:p-6 rounded-xl shadow-md sm:shadow-lg border border-amber-200 flex items-start gap-3 sm:gap-4 group hover:shadow-xl transition-all duration-300">
                        <div class="bg-amber-100 p-2.5 sm:p-3 rounded-lg group-hover:bg-amber-200 transition-colors flex-shrink-0">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $stat['svg'] }}" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm font-medium text-amber-700 mb-1 truncate">{{ $stat['label'] }}</p>
                            <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-amber-900">{{ $stat['count'] }}</p>
                        </div>
                    </div>
                    @else
                    {{-- Normal Card --}}
                    <div class="bg-white p-3 sm:p-4 lg:p-6 rounded-xl shadow-md border border-slate-200 flex items-start gap-3 sm:gap-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-red-500 group">
                        <div class="bg-slate-100 p-2.5 sm:p-3 rounded-lg group-hover:bg-red-50 transition-colors flex-shrink-0">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-slate-600 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $stat['svg'] }}" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm font-medium text-slate-600 mb-1 truncate">{{ $stat['label'] }}</p>
                            <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900">{{ $stat['count'] }}</p>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                {{-- [BAGIAN 3] FILTER DAN PENCARIAN --}}
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200 overflow-hidden" x-data="{ filtersOpen: window.innerWidth >= 768 }">
                    {{-- Mobile Toggle Button --}}
                    <button @click="filtersOpen = !filtersOpen" class="w-full p-3 sm:p-4 flex justify-between items-center md:hidden bg-slate-50 hover:bg-slate-100 transition-colors">
                        <span class="text-base sm:text-lg font-semibold text-slate-800">Filter & Cari</span>
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 transform transition-transform text-slate-500" :class="{'rotate-180': filtersOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    {{-- Filter Form --}}
                    <form action="{{ route('admin.santri-management.index') }}" method="GET" class="p-4 sm:p-6 border-t border-slate-200 md:border-t-0" x-show="filtersOpen" x-collapse>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4 items-end">
                            {{-- Input Pencarian --}}
                            <div class="sm:col-span-2 lg:col-span-2 xl:col-span-2">
                                <label for="search" class="block text-xs sm:text-sm font-medium text-slate-700 mb-1 sm:mb-2">Cari Santri</label>
                                <div class="relative group">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-slate-400 group-focus-within:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="search" name="search" value="{{ request('search') }}" class="block w-full rounded-lg sm:rounded-xl border border-slate-200 pl-9 sm:pl-10 pr-3 py-2 sm:py-3 text-sm shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:ring-offset-0 transition-all duration-200 placeholder-slate-400" placeholder="Nama atau NIS santri...">
                                </div>
                            </div>

                            {{-- Filter Kelas --}}
                            <div>
                                <label for="kelas_id" class="block text-xs sm:text-sm font-medium text-slate-700 mb-1 sm:mb-2">Kelas</label>
                                <select id="kelas_id" name="kelas_id" class="block w-full rounded-lg sm:rounded-xl border border-slate-200 px-3 py-2 sm:py-3 text-sm shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:ring-offset-0 transition-all duration-200">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Rayon --}}
                            <div>
                                <label for="rayon" class="block text-xs sm:text-sm font-medium text-slate-700 mb-1 sm:mb-2">Rayon</label>
                                <select id="rayon" name="rayon" class="block w-full rounded-lg sm:rounded-xl border border-slate-200 px-3 py-2 sm:py-3 text-sm shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:ring-offset-0 transition-all duration-200">
                                    <option value="">Semua Rayon</option>
                                    @foreach($rayonList as $rayon)
                                    <option value="{{ $rayon }}" {{ request('rayon') == $rayon ? 'selected' : '' }}>{{ $rayon }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tombol Action --}}
                            <div class="flex flex-col sm:flex-row gap-2">
                                <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-lg sm:rounded-xl bg-red-600 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-semibold text-white shadow-sm hover:bg-red-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <span>Filter</span>
                                </button>
                                <a href="{{ route('admin.santri-management.index') }}" class="flex-1 inline-flex items-center justify-center rounded-lg sm:rounded-xl bg-white border border-slate-200 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 hover:border-slate-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span>Reset</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- [BAGIAN 4] DAFTAR DATA (Mobile & Desktop) --}}
                {{-- Tampilan Kartu untuk Mobile --}}
                <div class="space-y-3 md:hidden">
                    @forelse ($santris as $santri)
                    <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
                        {{-- Profile Section --}}
                        <a href="{{ route('santri.profil.show', $santri) }}" class="block p-3 sm:p-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start gap-3 sm:gap-4">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 sm:h-14 sm:w-14 rounded-full object-cover ring-2 ring-slate-100 hover:ring-red-200 transition-all duration-200" src="{{ $santri->foto ? Storage::url($santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($santri->nama).'&background=DC2626&color=fff&size=128' }}" alt="{{ $santri->nama }}" loading="lazy">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-slate-900 text-sm sm:text-base leading-tight truncate">{{ $santri->nama }}</h3>
                                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">{{ $santri->nis ?? 'NIS belum diisi' }}</p>
                                </div>
                            </div>
                            <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-slate-200">
                                <div class="grid grid-cols-2 gap-2 sm:gap-4 text-xs sm:text-sm">
                                    <div class="space-y-1">
                                        <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">Kelas</span>
                                        <span class="font-medium text-slate-900">{{ $santri->kelas->nama_kelas ?? 'N/A' }}</span>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">Rayon</span>
                                        <span class="font-medium text-slate-900">{{ $santri->rayon ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                        {{-- Action Buttons --}}
                        <div class="bg-slate-50 border-t border-slate-200 px-3 sm:px-4 py-2.5 sm:py-3">
                            <div class="flex items-center justify-end space-x-1.5 sm:space-x-2">
                                @can('create', App\Models\Perizinan::class)
                                <a href="{{ route('perizinan.create', $santri) }}" class="group inline-flex items-center px-2.5 sm:px-3 py-1.5 sm:py-2 bg-green-50 hover:bg-green-100 text-green-700 font-medium rounded-md sm:rounded-lg text-xs sm:text-sm shadow-sm hover:shadow-md border border-green-200 hover:border-green-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 group-hover:scale-110 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Buat Izin</span>
                                </a>
                                @endcan

                                <a href="{{ route('pengajaran.santris.edit', $santri) }}" class="group inline-flex items-center px-2.5 sm:px-3 py-1.5 sm:py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 font-medium rounded-md sm:rounded-lg text-xs sm:text-sm shadow-sm hover:shadow-md border border-slate-200 hover:border-slate-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 group-hover:scale-110 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    <span class="truncate">Edit Data</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-8 sm:p-12 text-center">
                        <div class="flex flex-col items-center space-y-3 sm:space-y-4">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-slate-100 rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="space-y-1.5 sm:space-y-2">
                                <h3 class="text-base sm:text-lg font-medium text-slate-900">Tidak ada data</h3>
                                <p class="text-xs sm:text-sm text-slate-500">Tidak ada data santri yang cocok dengan filter yang dipilih.</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>

                {{-- Tampilan Tabel untuk Desktop --}}
                <div class="hidden md:block bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Santri</th>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelas</th>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Rayon</th>
                                    <th scope="col" class="relative px-4 py-3.5 text-right">
                                        <span class="sr-only">Aksi</span>
                                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($santris as $santri)
                                <tr class="hover:bg-slate-50 transition-colors duration-150">
                                    <td class="px-4 py-4">
                                        <a href="{{ route('santri.profil.show', $santri) }}" class="group flex items-center">
                                            <img class="h-8 w-8 sm:h-10 sm:w-10 rounded-full object-cover ring-2 ring-slate-100 group-hover:ring-red-200 transition-all duration-200" src="{{ $santri->foto ? Storage::url($santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($santri->nama).'&background=DC2626&color=fff&size=80' }}" alt="{{ $santri->nama }}" loading="lazy">
                                            <div class="ml-3 sm:ml-4">
                                                <div class="font-medium text-slate-900 group-hover:text-red-600 transition-colors truncate max-w-xs">{{ $santri->nama }}</div>
                                                <div class="text-sm text-slate-500 truncate max-w-xs">{{ $santri->nis ?? 'NIS belum diisi' }}</div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ $santri->kelas->nama_kelas ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ $santri->rayon ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end space-x-1.5 sm:space-x-2">
                                            @can('create', App\Models\Perizinan::class)
                                            <a href="{{ route('perizinan.create', $santri) }}" class="group inline-flex items-center px-2.5 sm:px-3 py-1.5 sm:py-2 bg-green-50 hover:bg-green-100 text-green-700 font-medium rounded-md sm:rounded-lg text-xs sm:text-sm shadow-sm hover:shadow-md border border-green-200 hover:border-green-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 group-hover:scale-110 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="truncate">Buat Izin</span>
                                            </a>
                                            @endcan

                                            <a href="{{ route('pengajaran.santris.edit', $santri) }}" class="group inline-flex items-center px-2.5 sm:px-3 py-1.5 sm:py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 font-medium rounded-md sm:rounded-lg text-xs sm:text-sm shadow-sm hover:shadow-md border border-slate-200 hover:border-slate-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 group-hover:scale-110 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                                <span class="truncate">Edit</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center">
                                        <div class="flex flex-col items-center space-y-3 sm:space-y-4">
                                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-slate-100 rounded-xl flex items-center justify-center">
                                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="space-y-1.5 sm:space-y-2">
                                                <h3 class="text-base sm:text-lg font-medium text-slate-900">Tidak ada data</h3>
                                                <p class="text-xs sm:text-sm text-slate-500">Tidak ada data santri yang cocok dengan filter yang dipilih.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if ($santris->hasPages())
                <div class="p-3 sm:p-4 bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                        <div class="text-xs sm:text-sm text-slate-700">
                            Menampilkan {{ $santris->firstItem() ?? 0 }} - {{ $santris->lastItem() ?? 0 }} dari {{ $santris->total() }} santri
                        </div>
                        <div class="w-full sm:w-auto">
                            {{ $santris->appends(request()->only(['search', 'kelas_id', 'rayon']))->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Register Alpine component globally for Santri Management
        document.addEventListener('alpine:init', () => {
            Alpine.data('santriManagementData', () => {
                return {
                    selectedIds: []
                    , showDeleteConfirm: false
                    , deleteForm: null
                    , allIds: [], // Will be empty since no bulk actions in this view

                    // Getters
                    get areAllSelected() {
                        return false; // No bulk selection in this view
                    },

                    get hasSelected() {
                        return this.selectedIds.length > 0;
                    },

                    // Methods
                    initData() {
                        this.selectedIds = [];
                        console.log('Santri Management Alpine initialized');
                    },

                    toggleSelectAll() {
                        // No-op for this view
                    },

                    confirmDelete(form) {
                        this.deleteForm = form;
                        this.showDeleteConfirm = true;
                    },

                    confirmSingleDelete(id, form) {
                        this.deleteForm = form;
                        this.showDeleteConfirm = true;
                    },

                    submitDelete() {
                        if (this.deleteForm) {
                            this.deleteForm.submit();
                        }
                        this.showDeleteConfirm = false;
                        this.deleteForm = null;
                    },

                    cancelDelete() {
                        this.showDeleteConfirm = false;
                        this.deleteForm = null;
                    }
                };
            });
        });

    </script>
</x-app-layout>
