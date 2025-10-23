<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">
            {{-- PHP Variables for JavaScript --}}
            @php
            $allIds = $perizinans->pluck('id')->toArray();
            $stats = $stats ?? [
            'total_aktif' => 0,
            'izin_hari_ini' => 0,
            'akan_kembali' => 0,
            'total_bulan_ini' => 0
            ];
            @endphp

            <!-- PERBAIKAN: Gunakan inline Alpine data -->
            <div class="space-y-4 sm:space-y-6" x-data="{
                     selectedIds: [],
                     allIds: {{ json_encode($allIds) }},
                     showDeleteConfirm: false,
                     currentDeleteId: null,
                     isBulkDelete: false,
                     deleteForm: null,
                     
                     get areAllSelected() {
                         return this.allIds.length > 0 && 
                                this.selectedIds.length === this.allIds.length &&
                                this.allIds.every(id => this.selectedIds.includes(id));
                     },
                     
                     get hasSelected() {
                         return this.selectedIds.length > 0;
                     },
                     
                     initData() {
                         if (!Array.isArray(this.selectedIds)) {
                             this.selectedIds = [];
                         }
                         if (!Array.isArray(this.allIds)) {
                             this.allIds = [];
                         }
                     },
                     
                     toggleSelectAll() {
                         if (this.areAllSelected) {
                             this.selectedIds = [];
                         } else {
                             this.selectedIds = [...this.allIds];
                         }
                     },
                     
                     confirmBulkDelete() {
                         this.isBulkDelete = true;
                         this.showDeleteConfirm = true;
                     },
                     
                     confirmSingleDelete(id) {
                         this.isBulkDelete = false;
                         this.currentDeleteId = id;
                         this.showDeleteConfirm = true;
                     },
                     
                     submitDelete() {
                         if (this.isBulkDelete) {
                             // Bulk delete
                             const form = this.$refs.bulkDeleteForm;
                             if (form) {
                                 form.submit();
                             }
                         } else {
                             // Single delete
                             if (this.currentDeleteId) {
                                 const form = document.getElementById('delete-form-' + this.currentDeleteId);
                                 if (form) {
                                     form.submit();
                                 }
                             }
                         }
                         this.resetDeleteState();
                     },
                     
                     cancelDelete() {
                         this.resetDeleteState();
                     },
                     
                     resetDeleteState() {
                         this.showDeleteConfirm = false;
                         this.currentDeleteId = null;
                         this.isBulkDelete = false;
                         this.deleteForm = null;
                     }
                 }" x-init="initData()" x-cloak>

                {{-- Header Section --}}
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold tracking-tight text-gray-900 truncate">Daftar Santri Izin Aktif</h1>
                            <p class="mt-1 text-xs sm:text-sm text-slate-600 max-w-2xl">Berikut adalah daftar santri yang sedang tidak berada di pondok atau tidak mengikuti KBM.</p>
                        </div>
                        @can('create', App\Models\Perizinan::class)
                        <div class="flex-shrink-0 flex flex-col sm:flex-row gap-2 sm:gap-3">
                            {{-- Tombol Riwayat Perizinan --}}
                            <a href="{{ route('perizinan.riwayat') }}" class="inline-flex items-center justify-center gap-1.5 sm:gap-2 rounded-lg sm:rounded-md bg-red-700 px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-colors duration-200">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="truncate">Riwayat Perizinan</span>
                            </a>

                            {{-- Tombol Buat Izin Baru --}}
                            <a href="{{ route('admin.santri-management.index') }}" class="inline-flex items-center justify-center gap-1.5 sm:gap-2 rounded-lg sm:rounded-md bg-red-700 px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                </svg>
                                <span class="truncate">Buat Izin Baru</span>
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>

                {{-- Statistics Cards - Mobile Grid --}}
                <div class="grid grid-cols-2 gap-3 sm:gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @php
                    $statConfigs = [
                    ['count' => $stats['total_aktif'], 'label' => 'Total Izin Aktif', 'icon' => 'blue', 'svg' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['count' => $stats['izin_hari_ini'], 'label' => 'Izin Hari Ini', 'icon' => 'green', 'svg' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['count' => $stats['akan_kembali'], 'label' => 'Akan Kembali', 'icon' => 'orange', 'svg' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                    ['count' => $stats['total_bulan_ini'], 'label' => 'Bulan Ini', 'icon' => 'purple', 'svg' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z']
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
                        <div class="flex gap-2 self-stretch sm:self-auto">
                            <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-lg sm:rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari
                            </button>
                            @if(isset($search) && $search)
                            <a href="{{ route('perizinan.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-lg sm:rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>

                    @if(isset($search) && $search)
                    <div class="mt-3 sm:mt-4 p-2.5 sm:p-3 bg-blue-50 rounded-lg">
                        <p class="text-xs sm:text-sm text-blue-700">
                            Menampilkan hasil pencarian untuk: <strong>"{{ $search }}"</strong>
                            <span class="hidden sm:inline">•</span><br class="sm:hidden">
                            Ditemukan <strong>{{ $perizinans->total() }}</strong> data
                        </p>
                    </div>
                    @endif
                </div>

                {{-- Custom Delete Confirmation Pop-up --}}
                <div x-show="showDeleteConfirm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-cloak>
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6 max-w-sm w-full mx-auto">
                        <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Hapus</h3>
                        <p class="mt-2 text-sm text-slate-600" x-text="isBulkDelete ? 
                           'Apakah Anda yakin ingin menghapus ' + selectedIds.length + ' data perizinan yang dipilih?' : 
                           'Apakah Anda yakin ingin menghapus data perizinan ini?'">
                        </p>
                        <div class="mt-4 sm:mt-6 flex justify-end gap-2 sm:gap-3">
                            <button @click="cancelDelete()" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 sm:px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-md hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-colors duration-200">
                                Batal
                            </button>
                            <button @click="submitDelete()" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 sm:px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Bulk Action Bar --}}
                @can('delete', App\Models\Perizinan::newModelInstance())
                <div x-show="hasSelected" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200 p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3" x-cloak>
                    <p class="text-sm font-medium text-slate-700 text-center sm:text-left">
                        <span x-text="selectedIds.length"></span> data dipilih
                    </p>
                    <form action="{{ route('perizinan.bulkDestroy') }}" method="POST" x-ref="bulkDeleteForm" class="w-full sm:w-auto">
                        @csrf
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="button" @click="confirmBulkDelete()" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md border border-transparent bg-red-100 px-3 py-1.5 text-sm font-medium text-red-700 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                            Hapus yang Dipilih
                        </button>
                    </form>
                </div>
                @endcan

                {{-- Mobile View --}}
                <div class="md:hidden space-y-3">
                    @can('delete', App\Models\Perizinan::newModelInstance())
                    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-3 flex items-center gap-3">
                        <input type="checkbox" @click="toggleSelectAll()" :checked="areAllSelected" class="h-4 w-4 rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                        <label @click="toggleSelectAll()" class="text-sm font-medium text-slate-700 cursor-pointer select-none flex-1">
                            Pilih Semua
                        </label>
                    </div>
                    @endcan

                    @forelse ($perizinans as $izin)
                    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-3 sm:p-4">
                        <div class="flex justify-between items-start gap-3">
                            <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                                <img class="h-8 w-8 sm:h-10 sm:w-10 rounded-full object-cover flex-shrink-0" src="{{ $izin->santri->foto ? Storage::url($izin->santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($izin->santri->nama).'&background=FBBF24&color=fff&font-size=0.4' }}" alt="Avatar {{ $izin->santri->nama }}" loading="lazy">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-slate-900 truncate">{{ $izin->santri->nama }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $izin->santri->nis }} / {{ $izin->santri->kelas->nama_kelas ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @can('delete', $izin)
                            <input type="checkbox" x-model="selectedIds" value="{{ $izin->id }}" class="h-4 w-4 rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 flex-shrink-0 mt-1">
                            @endcan
                        </div>

                        <div class="mt-3 pt-3 border-t border-slate-200 space-y-2">
                            <div>
                                <p class="text-xs text-slate-500">Jenis Izin</p>
                                <p class="text-sm font-medium text-slate-800 truncate">{{ $izin->jenis_izin }}</p>
                                <p class="text-xs text-slate-600 line-clamp-2">{{ $izin->keterangan }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <p class="text-xs text-slate-500">Mulai</p>
                                    <p class="text-xs text-slate-600">{{ $izin->tanggal_mulai->format('d M Y') }}</p>
                                </div>
                                @if($izin->tanggal_akhir)
                                <div>
                                    <p class="text-xs text-slate-500">Kembali</p>
                                    <p class="text-xs text-slate-600">{{ $izin->tanggal_akhir->format('d M Y') }}</p>
                                </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Dicatat Oleh</p>
                                <p class="text-xs text-slate-600 truncate">{{ $izin->pembuat->name ?? 'N/A' }} ({{ ucwords($izin->kategori) }})</p>
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-t border-slate-200 flex items-center justify-between gap-2">
                            @can('view', $izin)
                            <a href="{{ route('perizinan.pdf', $izin) }}" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-blue-600 hover:text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md transition-colors duration-200" target="_blank" rel="noopener">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                PDF
                            </a>
                            @endcan
                            @can('delete', $izin)

                            <!-- PERBAIKAN: Tombol hapus dengan form tersembunyi -->
                            <button type="button" @click="confirmSingleDelete('{{ $izin->id }}')" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-red-600 hover:text-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded-md transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>

                            <!-- Form tersembunyi untuk delete -->
                            <form id="delete-form-{{ $izin->id }}" action="{{ route('perizinan.destroy', $izin) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endcan
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-8 sm:p-12 text-center text-slate-500">
                        @if(isset($search) && $search)
                        <p class="text-sm">Tidak ditemukan data perizinan untuk pencarian "{{ $search }}"</p>
                        @else
                        <p class="text-sm">Tidak ada santri yang sedang izin saat ini.</p>
                        @endif
                    </div>
                    @endforelse
                </div>

                {{-- Desktop View --}}
                <div class="hidden md:block bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    @can('delete', App\Models\Perizinan::newModelInstance())
                                    <th class="pl-4 pr-3 py-3.5 w-12">
                                        <input type="checkbox" @click="toggleSelectAll()" :checked="areAllSelected" class="h-4 w-4 rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                    </th>
                                    @endcan
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Santri</th>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Izin</th>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dicatat Oleh</th>
                                    <th scope="col" class="relative px-4 py-3.5 text-right">
                                        <span class="sr-only">Aksi</span>
                                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($perizinans as $izin)
                                <tr class="hover:bg-slate-50 transition-colors duration-150">
                                    @can('delete', $izin)
                                    <td class="pl-4 pr-3 py-4">
                                        <input type="checkbox" x-model="selectedIds" value="{{ $izin->id }}" class="h-4 w-4 rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                    </td>
                                    @endcan
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
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-500 truncate max-w-xs">
                                        {{ $izin->pembuat->name ?? 'N/A' }} ({{ ucwords($izin->kategori) }})
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            @can('view', $izin)
                                            <a href="{{ route('perizinan.pdf', $izin) }}" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-blue-600 hover:text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md transition-colors duration-200" target="_blank" rel="noopener">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                PDF
                                            </a>
                                            @endcan
                                            @can('delete', $izin)

                                            <!-- PERBAIKAN: Tombol hapus dengan form tersembunyi -->
                                            <button type="button" @click="confirmSingleDelete('{{ $izin->id }}')" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-red-600 hover:text-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded-md transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>

                                            <!-- Form tersembunyi untuk delete -->
                                            <form id="delete-form-{{ $izin->id }}" action="{{ route('perizinan.destroy', $izin) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ (Auth::user()->can('delete', App\Models\Perizinan::newModelInstance())) ? 6 : 5 }}" class="px-6 py-8 text-center text-sm text-slate-500">
                                        @if(isset($search) && $search)
                                        Tidak ditemukan data perizinan untuk pencarian "{{ $search }}"
                                        @else
                                        Tidak ada santri yang sedang izin saat ini.
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
                        {{ $perizinans->appends(['search' => $search ?? ''])->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- HAPUS script Alpine yang lama -->
</x-app-layout>
