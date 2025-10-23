<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">
            <div class="space-y-4 sm:space-y-6">

                {{-- Header Section --}}
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold tracking-tight text-gray-900 truncate">Riwayat Perizinan Santri</h1>
                            <p class="mt-1 text-xs sm:text-sm text-slate-600 max-w-2xl">Daftar lengkap perizinan santri yang sudah selesai.</p>
                        </div>
                        <div class="flex-shrink-0 flex gap-2">
                            <a href="{{ route('perizinan.index') }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-lg sm:rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Izin Aktif
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Statistics Cards --}}
                <div class="grid grid-cols-2 gap-3 sm:gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @php
                    $statConfigs = [
                    ['count' => $statsRiwayat['total_selesai'], 'label' => 'Total Selesai', 'icon' => 'green', 'svg' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['count' => $statsRiwayat['total_terlambat'], 'label' => 'Total Terlambat', 'icon' => 'red', 'svg' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['count' => $statsRiwayat['selesai_bulan_ini'], 'label' => 'Selesai Bulan Ini', 'icon' => 'blue', 'svg' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['count' => $statsRiwayat['terlambat_bulan_ini'], 'label' => 'Terlambat Bulan Ini', 'icon' => 'orange', 'svg' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z']
                    ];
                    @endphp
                    @foreach($statConfigs as $stat)
                    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-3 sm:p-4 lg:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-{{ $stat['icon'] }}-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-{{ $stat['icon'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['svg'] }}" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-2 sm:ml-3 lg:ml-4 min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium text-slate-600 truncate">{{ $stat['label'] }}</p>
                                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-900 truncate">{{ $stat['count'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Search and Filter Section --}}
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200 p-4 sm:p-6">
                    <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-stretch sm:items-end">
                        <div class="flex-1 min-w-0">
                            <label for="search" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Cari Santri (Nama atau NIS)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ $search ?? '' }}" class="block w-full pl-9 sm:pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-lg sm:rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="Ketik nama santri atau NIS...">
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <label for="status" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Filter Status
                            </label>
                            <select name="status" id="status" class="block w-full pl-3 pr-10 py-2 text-sm border border-gray-300 rounded-lg sm:rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                <option value="all" {{ $filterStatus == 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="selesai" {{ $filterStatus == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="terlambat" {{ $filterStatus == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            </select>
                        </div>
                        <div class="flex gap-2 self-stretch sm:self-auto">
                            <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-lg sm:rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari
                            </button>
                            @if(isset($search) || isset($filterStatus))
                            <a href="{{ route('perizinan.riwayat') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-lg sm:rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Desktop View --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Santri</th>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Izin</th>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Periode</th>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dicatat Oleh</th>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Diselesaikan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($perizinans as $izin)
                                <tr class="hover:bg-slate-50 transition-colors duration-150">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $izin->santri->foto ? Storage::url($izin->santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($izin->santri->nama).'&background=FBBF24&color=fff&font-size=0.4' }}" alt="Avatar {{ $izin->santri->nama }}" loading="lazy">
                                            </div>
                                            <div class="ml-3 min-w-0">
                                                <div class="text-sm font-medium text-slate-900 truncate max-w-xs">{{ $izin->santri->nama }}</div>
                                                <div class="text-xs text-slate-500 truncate max-w-xs">{{ $izin->santri->nis }} / {{ $izin->santri->kelas->nama_kelas ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-slate-900 truncate max-w-xs">{{ $izin->jenis_izin }}</div>
                                        <div class="text-xs text-slate-500 truncate max-w-xs">{{ $izin->keterangan }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-500">
                                        <div>Mulai: {{ $izin->tanggal_mulai->format('d M Y') }}</div>
                                        @if($izin->tanggal_akhir)
                                        <div>Kembali: {{ $izin->tanggal_akhir->format('d M Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $izin->status == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($izin->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-500 truncate max-w-xs">
                                        {{ $izin->pembuat->name ?? 'N/A' }} ({{ ucwords($izin->kategori) }})
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $izin->updated_at->format('d M Y H:i') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                        @if(isset($search) || isset($filterStatus))
                                        Tidak ditemukan data perizinan untuk kriteria pencarian ini.
                                        @else
                                        Belum ada riwayat perizinan.
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if ($perizinans->hasPages())
                <div class="p-3 sm:p-4 bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200 flex justify-center">
                    <div class="w-full max-w-md">
                        {{ $perizinans->appends(['search' => $search ?? '', 'status' => $filterStatus])->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
