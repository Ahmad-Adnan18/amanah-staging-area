<x-app-layout>
    {{-- PHP Variables for JavaScript --}}
    @php
    $allIds = $pelanggarans->pluck('id')->toArray();
    $statistics = $statistics ?? [
    'total_pelanggaran' => 0,
    'pelanggaran_bulan_ini' => 0,
    'top_pelanggar' => collect([]),
    'jenis_pelanggaran' => collect([])
    ];
    @endphp

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">
            <!-- PERBAIKAN: Gunakan pendekatan inline Alpine data -->
            <div class="space-y-4 sm:space-y-6" x-data="{
                     selectedIds: [],
                     allIds: {{ json_encode($allIds) }},
                     showDeleteConfirm: false,
                     currentDeleteId: null,
                     isBulkDelete: false,
                     
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
                             const form = this.$refs.bulkDeleteForm;
                             if (form) {
                                 form.submit();
                             }
                         } else {
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
                     }
                 }" x-init="initData()" x-cloak>

                {{-- Header Section --}}
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold tracking-tight text-gray-900 truncate">Catatan Pelanggaran Santri</h1>
                            <p class="mt-1 text-xs sm:text-sm text-slate-600 max-w-2xl">Daftar semua catatan pelanggaran yang telah diinput.</p>
                        </div>
                        @can('create', App\Models\Pelanggaran::class)
                        <div class="flex-shrink-0">
                            <a href="{{ route('pelanggaran.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 sm:gap-2 rounded-lg sm:rounded-md bg-red-700 px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                </svg>
                                <span class="truncate">Tambah Catatan</span>
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>

                {{-- Search Bar --}}
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200 p-4 sm:p-6">
                    <form action="{{ route('pelanggaran.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-stretch sm:items-end">
                        <div class="flex-1 min-w-0">
                            <label for="search" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Cari Pelanggaran
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama santri, jenis pelanggaran, atau pencatat..." class="block w-full pl-9 sm:pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-lg sm:rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                        <div class="flex gap-2 self-stretch sm:self-auto">
                            <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-lg sm:rounded-md shadow-sm text-white bg-red-700 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari
                            </button>
                            @if(request('search'))
                            <a href="{{ route('pelanggaran.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-lg sm:rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>

                    @if(request('search'))
                    <div class="mt-3 sm:mt-4 p-2.5 sm:p-3 bg-red-50 rounded-lg">
                        <p class="text-xs sm:text-sm text-red-700">
                            Menampilkan hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                            <span class="hidden sm:inline">•</span><br class="sm:hidden">
                            Ditemukan <strong>{{ $pelanggarans->total() }}</strong> data
                        </p>
                    </div>
                    @endif
                </div>

                {{-- Statistics Cards - Mobile Grid --}}
                <div class="grid grid-cols-2 gap-3 sm:gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @php
                    $statConfigs = [
                    ['count' => $statistics['total_pelanggaran'], 'label' => 'Total Pelanggaran', 'icon' => 'red', 'svg' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z'],
                    ['count' => $statistics['pelanggaran_bulan_ini'], 'label' => 'Bulan Ini', 'icon' => 'orange', 'svg' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['count' => $statistics['top_pelanggar']->count(), 'label' => 'Top Pelanggar', 'icon' => 'yellow', 'svg' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['count' => $statistics['jenis_pelanggaran']->count(), 'label' => 'Jenis Teratas', 'icon' => 'blue', 'svg' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2']
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
                                @if($stat['label'] === 'Top Pelanggar')
                                <p class="text-xs text-slate-500">santri</p>
                                @elseif($stat['label'] === 'Jenis Teratas')
                                <p class="text-xs text-slate-500">kategori</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Detailed Statistics --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-6">
                    {{-- Top 5 Pelanggar --}}
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-3 sm:mb-4">Top 5 Santri Pelanggar</h3>
                        <div class="space-y-2 sm:space-y-3">
                            @forelse($statistics['top_pelanggar'] as $santri)
                            <div class="flex items-center justify-between p-2.5 sm:p-3 bg-slate-50 rounded-lg">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs sm:text-sm font-medium text-red-700">{{ $loop->iteration }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs sm:text-sm font-medium text-slate-900 truncate">{{ $santri->nama }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ $santri->kelas->nama_kelas ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 whitespace-nowrap">
                                    {{ $santri->pelanggarans_count }} pelanggaran
                                </span>
                            </div>
                            @empty
                            <p class="text-sm text-slate-500 text-center py-4">Tidak ada data pelanggaran</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Jenis Pelanggaran Terbanyak --}}
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200 p-4 sm:p-6">
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-3 sm:mb-4">Jenis Pelanggaran Terbanyak</h3>
                        <div class="space-y-2 sm:space-y-3">
                            @forelse($statistics['jenis_pelanggaran'] as $jenis)
                            <div class="flex items-center justify-between p-2.5 sm:p-3 bg-slate-50 rounded-lg">
                                <span class="text-xs sm:text-sm font-medium text-slate-900 truncate flex-1 mr-2">{{ $jenis->jenis_pelanggaran }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 whitespace-nowrap">
                                    {{ $jenis->total }} kali
                                </span>
                            </div>
                            @empty
                            <p class="text-sm text-slate-500 text-center py-4">Tidak ada data jenis pelanggaran</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Custom Delete Confirmation Pop-up --}}
                <div x-show="showDeleteConfirm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-cloak>
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6 max-w-sm w-full mx-auto">
                        <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Hapus</h3>
                        <p class="mt-2 text-sm text-slate-600" x-text="isBulkDelete ? 'Apakah Anda yakin ingin menghapus ' + selectedIds.length + ' data yang dipilih?' : 'Apakah Anda yakin ingin menghapus data ini?'"></p>
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
                @can('delete', App\Models\Pelanggaran::newModelInstance())
                <div x-show="hasSelected" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200 p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3" x-cloak>
                    <p class="text-sm font-medium text-slate-700 text-center sm:text-left">
                        <span x-text="selectedIds.length"></span> data dipilih
                    </p>
                    <form action="{{ route('pelanggaran.bulkDestroy') }}" method="POST" x-ref="bulkDeleteForm" class="w-full sm:w-auto">
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

                {{-- Success Message --}}
                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif

                {{-- Mobile View --}}
                <div class="md:hidden space-y-3">
                    @can('delete', App\Models\Pelanggaran::newModelInstance())
                    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-3 flex items-center gap-3">
                        <input type="checkbox" @click="toggleSelectAll()" :checked="areAllSelected" class="h-4 w-4 rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                        <label @click="toggleSelectAll()" class="text-sm font-medium text-slate-700 cursor-pointer select-none flex-1">
                            Pilih Semua
                        </label>
                    </div>
                    @endcan

                    @forelse ($pelanggarans as $pelanggaran)
                    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-3 sm:p-4">
                        <div class="flex justify-between items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 truncate">{{ $pelanggaran->jenis_pelanggaran }}</p>
                                <a href="{{ route('santri.profil.show', $pelanggaran->santri) }}" class="text-xs sm:text-sm font-medium text-red-700 hover:text-red-900 mt-1 block truncate">
                                    {{ $pelanggaran->santri->nama }}
                                </a>
                                <div class="mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-slate-200 text-xs sm:text-sm text-slate-500 space-y-1">
                                    <p>Tanggal: {{ $pelanggaran->tanggal_kejadian->format('d M Y') }}</p>
                                    <p>Dicatat oleh: {{ $pelanggaran->dicatat_oleh }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end space-y-1.5 sm:space-y-2 flex-shrink-0">
                                @can('update', $pelanggaran)
                                <a href="{{ route('pelanggaran.edit', $pelanggaran) }}" class="text-xs sm:text-sm font-medium text-slate-600 hover:text-red-700 p-1">
                                    Edit
                                </a>
                                @endcan
                                @can('delete', $pelanggaran)
                                <input type="checkbox" x-model="selectedIds" value="{{ $pelanggaran->id }}" class="h-4 w-4 rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1">

                                <button type="button" @click="confirmSingleDelete('{{ $pelanggaran->id }}')" class="text-xs sm:text-sm font-medium text-red-600 hover:text-red-900 p-1">
                                    Hapus
                                </button>

                                {{-- Form tersembunyi untuk delete --}}
                                <form id="delete-form-{{ $pelanggaran->id }}" action="{{ route('pelanggaran.destroy', $pelanggaran) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-8 sm:p-12 text-center text-slate-500">
                        <p class="text-sm">Belum ada data pelanggaran.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Desktop View --}}
                <div class="hidden md:block bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    @can('delete', App\Models\Pelanggaran::newModelInstance())
                                    <th class="pl-4 pr-3 py-3.5 w-12">
                                        <input type="checkbox" @click="toggleSelectAll()" :checked="areAllSelected" class="h-4 w-4 rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                    </th>
                                    @endcan
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Santri</th>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Pelanggaran</th>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dicatat Oleh</th>
                                    <th scope="col" class="relative px-4 py-3.5 text-right">
                                        <span class="sr-only">Aksi</span>
                                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($pelanggarans as $pelanggaran)
                                <tr class="hover:bg-slate-50 transition-colors duration-150">
                                    @can('delete', $pelanggaran)
                                    <td class="pl-4 pr-3 py-4">
                                        <input type="checkbox" x-model="selectedIds" value="{{ $pelanggaran->id }}" class="h-4 w-4 rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                    </td>
                                    @endcan
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-red-700">{{ substr($pelanggaran->santri->nama, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-3 min-w-0">
                                                <a href="{{ route('santri.profil.show', $pelanggaran->santri) }}" class="text-sm font-medium text-slate-900 hover:text-red-700 truncate max-w-xs">
                                                    {{ $pelanggaran->santri->nama }}
                                                </a>
                                                <div class="text-xs text-slate-500 truncate max-w-xs">{{ $pelanggaran->santri->kelas->nama_kelas ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-slate-900 truncate max-w-xs">{{ $pelanggaran->jenis_pelanggaran }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $pelanggaran->tanggal_kejadian->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-500 truncate max-w-xs">
                                        {{ $pelanggaran->dicatat_oleh }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            @can('update', $pelanggaran)
                                            <a href="{{ route('pelanggaran.edit', $pelanggaran) }}" class="text-sm font-medium text-slate-600 hover:text-red-700">
                                                Edit
                                            </a>
                                            @endcan
                                            @can('delete', $pelanggaran)
                                            <button type="button" @click="confirmSingleDelete('{{ $pelanggaran->id }}')" class="text-sm font-medium text-red-600 hover:text-red-900 ml-2">
                                                Hapus
                                            </button>

                                            {{-- Form tersembunyi untuk delete --}}
                                            <form id="delete-form-{{ $pelanggaran->id }}" action="{{ route('pelanggaran.destroy', $pelanggaran) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ (Auth::user()->can('delete', App\Models\Pelanggaran::newModelInstance())) ? 6 : 5 }}" class="px-6 py-8 text-center text-sm text-slate-500">
                                        Belum ada data pelanggaran.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if ($pelanggarans->hasPages())
                <div class="p-3 sm:p-4 bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg border border-slate-200 flex justify-center">
                    <div class="w-full max-w-md">
                        {{ $pelanggarans->appends(request()->only('search'))->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- HAPUS script Alpine yang lama -->
</x-app-layout>
