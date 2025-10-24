<x-app-layout>
    {{-- [MODIFIKASI] Menambahkan dependensi untuk Tom Select & Alpine.js --}}
    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-center gap-x-4">
                        <div class="bg-red-100 p-3 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-red-700">
                                <path d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                                <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h15a3 3 0 0 1 3 3v15a3 3 0 0 1-3 3h-15a3 3 0 0 1-3-3V4.5ZM6 4.5a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3A.75.75 0 0 1 6 4.5Zm3.75 0a.75.75 0 0 0-1.5 0v3a.75.75 0 0 0 1.5 0v-3ZM12 4.5a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3A.75.75 0 0 1 12 4.5Zm3.75 0a.75.75 0 0 0-1.5 0v3a.75.75 0 0 0 1.5 0v-3Zm3 0a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Alat Penempatan Kelas</h1>
                            <p class="mt-1 text-slate-600">Pindahkan santri dari kelas lama ke kelas baru untuk tahun ajaran berikutnya.</p>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif

                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200">
                    <form action="{{ route('akademik.placement.index') }}" method="GET">
                        <label for="source_kelas_id" class="block text-lg font-semibold text-gray-900">Langkah 1: Pilih Kelas Asal Santri</label>
                        <p class="text-sm text-slate-500 mt-1">Pilih kelas yang santrinya akan dipindahkan atau dinaikkan kelasnya.</p>
                        <div class="mt-3 flex flex-col sm:flex-row items-end gap-4">
                            <div class="flex-grow w-full">
                                {{-- [MODIFIKASI] Menggunakan Tom Select untuk pencarian kelas --}}
                                <select name="source_kelas_id" id="source_kelas_id" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" @selected(request('source_kelas_id') == $kelas->id)>{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Tampilkan Santri
                            </button>
                        </div>
                    </form>
                </div>

                @if ($santris->isNotEmpty())
                <div x-data="classPlacement()">
                    <form @submit.prevent="confirmPlacement">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                            
                            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                                <div class="p-4 bg-slate-50 border-b border-slate-200">
                                    <h2 class="font-semibold text-gray-800">Langkah 2: Pilih Santri yang Akan Dipindahkan</h2>
                                </div>
                                <div class="p-4">
                                    {{-- [MODIFIKASI] Kotak Pencarian Santri --}}
                                    <div class="mb-4">
                                        <input type="text" x-model="search" placeholder="Cari nama atau NIS santri..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                                    </div>

                                    <label class="flex items-center border-b border-slate-200 pb-3 mb-3">
                                        <input type="checkbox" x-model="checkAll" @change="toggleSelectAll()" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                        <span class="ml-2 font-medium text-sm text-slate-800">Pilih Semua Santri yang Tampil</span>
                                    </label>
                                    <div class="max-h-96 overflow-y-auto space-y-1 pr-2">
                                        <template x-for="santri in filteredSantris" :key="santri.id">
                                            <label class="flex items-center p-2 rounded-md hover:bg-slate-50 cursor-pointer" :class="{'bg-red-50': isSelected(santri.id)}">
                                                <input type="checkbox" name="santri_ids[]" :value="santri.id" x-model="selectedSantri" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                                <span class="ml-3 text-sm text-slate-900">
                                                    <span x-text="santri.nama"></span> 
                                                    <span class="text-slate-500" x-text="'(' + santri.nis + ')'"></span>
                                                </span>
                                            </label>
                                        </template>
                                        <div x-show="filteredSantris.length === 0" class="text-center py-8 text-slate-500 text-sm">
                                            Tidak ada santri yang cocok dengan pencarian.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden sticky top-8">
                                <div class="p-4 bg-slate-50 border-b border-slate-200">
                                    <h2 class="font-semibold text-gray-800">Langkah 3: Pilih Kelas Tujuan</h2>
                                </div>
                                <div class="p-6 space-y-6">
                                    <div>
                                        <label for="target_kelas_id" class="block text-sm font-medium text-slate-800">Pindahkan <strong x-text="selectedSantri.length" class="text-red-700"></strong> santri terpilih ke:</label>
                                        <select name="target_kelas_id" id="target_kelas_id" x-model="targetClassId" @change="updateTargetClassInfo()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                                            <option value="">-- Pilih Kelas Tujuan --</option>
                                            @foreach($kelasList as $kelas)
                                                @if(request('source_kelas_id') != $kelas->id)
                                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        {{-- [MODIFIKASI] Info kapasitas kelas --}}
                                        <p x-show="targetClassId" class="text-xs text-slate-500 mt-2" x-cloak>
                                            Kelas ini sudah memiliki <strong x-text="targetClassInfo.count"></strong> santri.
                                        </p>
                                    </div>
                                    
                                    {{-- [MODIFIKASI] Panel Konfirmasi Visual Santri Terpilih --}}
                                    <div x-show="selectedSantri.length > 0" x-cloak>
                                        <h3 class="text-sm font-medium text-slate-800 mb-2">Santri yang akan dipindahkan:</h3>
                                        <div class="max-h-40 overflow-y-auto bg-slate-50 rounded-lg p-3 border space-y-1">
                                            <template x-for="id in selectedSantri" :key="id">
                                                <div class="text-xs text-slate-700" x-text="getSantriNameById(id)"></div>
                                            </template>
                                        </div>
                                    </div>

                                    <button type="submit" :disabled="selectedSantri.length === 0 || !targetClassId" class="w-full inline-flex justify-center py-2 px-4 rounded-md text-white bg-red-700 hover:bg-red-800 disabled:bg-red-400 disabled:cursor-not-allowed font-semibold">
                                        Tempatkan Santri
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- [MODIFIKASI] Modal Konfirmasi --}}
                        <div x-show="showConfirmModal" x-cloak x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white rounded-2xl shadow-xl p-6 m-4 max-w-sm w-full" @click.away="showConfirmModal = false">
                                <h3 class="text-lg font-bold text-slate-800">Konfirmasi Penempatan</h3>
                                <p class="mt-2 text-sm text-slate-600">
                                    Anda akan memindahkan <strong x-text="selectedSantri.length"></strong> santri ke kelas <strong x-text="targetClassInfo.name"></strong>. Apakah Anda yakin ingin melanjutkan?
                                </p>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" @click="showConfirmModal = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-md hover:bg-slate-200">Batal</button>
                                    <button type="button" @click="submitForm" class="px-4 py-2 text-sm font-medium text-white bg-red-700 rounded-md hover:bg-red-600">Ya, Pindahkan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi Tom Select untuk dropdown kelas asal
            new TomSelect('#source_kelas_id', {
                create: false,
                sortField: { field: "text", direction: "asc" }
            });
        });

        // Data Alpine.js
        function classPlacement() {
            return {
                search: '',
                selectedSantri: [],
                checkAll: false,
                targetClassId: '',
                showConfirmModal: false,
                allSantris: @json($santris->map(fn($s) => ['id' => $s->id, 'nama' => $s->nama, 'nis' => $s->nis])),
                allClasses: @json($kelasList->keyBy('id')->map(fn($k) => ['id' => $k->id, 'name' => $k->nama_kelas, 'count' => $k->santris_count])),

                // Hitung santri yang tampil berdasarkan pencarian
                get filteredSantris() {
                    if (this.search === '') {
                        return this.allSantris;
                    }
                    return this.allSantris.filter(santri => {
                        const searchLower = this.search.toLowerCase();
                        return santri.nama.toLowerCase().includes(searchLower) || santri.nis.toLowerCase().includes(searchLower);
                    });
                },
                
                // Info kelas tujuan
                get targetClassInfo() {
                    if (!this.targetClassId || !this.allClasses[this.targetClassId]) {
                        return { name: '', count: 0 };
                    }
                    return this.allClasses[this.targetClassId];
                },

                // Fungsi untuk memilih semua santri yang tampil
                toggleSelectAll() {
                    let filteredIds = this.filteredSantris.map(s => s.id);
                    if (this.checkAll) {
                        // Tambahkan ID yang belum terpilih
                        this.selectedSantri = [...new Set([...this.selectedSantri, ...filteredIds])];
                    } else {
                        // Hapus ID yang ada di daftar filter dari pilihan
                        this.selectedSantri = this.selectedSantri.filter(id => !filteredIds.includes(id));
                    }
                },

                // Fungsi untuk memeriksa apakah santri terpilih
                isSelected(id) {
                    return this.selectedSantri.includes(id);
                },

                // Ambil nama santri berdasarkan ID untuk panel konfirmasi
                getSantriNameById(id) {
                const santri = this.allSantris.find(s => s.id == id); // <-- UBAH MENJADI == (DUA SAMA DENGAN)
                return santri ? santri.nama : 'Santri tidak ditemukan';
                },
                
                // Tampilkan modal konfirmasi
                confirmPlacement() {
                    if (this.selectedSantri.length > 0 && this.targetClassId) {
                        this.showConfirmModal = true;
                    }
                },
                
                // Submit form utama
                submitForm() {
                    this.$el.submit(); // 'this.$el' merujuk ke elemen <form>
                },
                
                init() {
                    // Sinkronisasi checkbox 'Pilih Semua' jika pilihan berubah
                    this.$watch('selectedSantri', () => {
                        let filteredIds = this.filteredSantris.map(s => s.id);
                        // Cek apakah semua santri yang terfilter sudah terpilih
                        this.checkAll = filteredIds.length > 0 && filteredIds.every(id => this.selectedSantri.includes(id));
                    });
                     this.$watch('search', () => {
                        // Panggil lagi watch untuk selectedSantri agar checkAll terupdate
                        let event = new CustomEvent('input', { bubbles: true });
                        this.$refs.selectedSantriWatcher?.dispatchEvent(event);
                     });
                }
            }
        }
    </script>
</x-app-layout>