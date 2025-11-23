<x-app-layout>
    <div class="bg-slate-50 min-h-screen" x-data="{
        selectedKelas: '{{ request('kelas_id') }}',
        santriList: [],
        suratList: [],
        isLoadingSantri: false,
        isLoadingSurat: false,
        santriSelect: null,
        suratSelect: null,
        selectedSuratMaxAyah: null, // Untuk validasi ayat

        // Toast Notification (sama persis seperti absensi)
        toastMessage: '',
        toastType: 'success',
        showToast: false,
        showNotification(message, type = 'success') {
            this.toastMessage = message;
            this.toastType = type;
            this.showToast = true;
            setTimeout(() => this.showToast = false, 3000);
        },

        // Fetch data
        fetchSantri() {
            if (!this.selectedKelas) {
                this.santriList = [];
                if (this.santriSelect) this.santriSelect.clearOptions();
                return;
            }
            this.isLoadingSantri = true;
            
            // [PERBAIKAN DI SINI] Ubah URL dari '/pengajaran/tahfidz/' ke '/ubudiyah/'
            fetch(`/ubudiyah/get-santri-by-kelas/${this.selectedKelas}`) 
                .then(r => r.json())
                .then(data => {
                    this.santriList = data;
                    this.isLoadingSantri = false;
                    if (this.santriSelect) {
                        this.santriSelect.clearOptions();
                        this.santriSelect.addOptions(data.map(s => ({ value: s.id, text: s.nama })));
                    }
                })
                .catch(() => {
                    this.isLoadingSantri = false;
                    this.showNotification('Gagal memuat daftar santri.', 'error');
                });
        },
        fetchSurat() {
            this.isLoadingSurat = true;
            
            // [PERBAIKAN DI SINI] Ubah URL dari '/pengajaran/tahfidz/' ke '/ubudiyah/'
            fetch(`/ubudiyah/get-surat-list`)
                .then(r => r.json())
                .then(data => {
                    this.suratList = data;
                    this.isLoadingSurat = false;
                    if (this.suratSelect) {
                        this.suratSelect.clearOptions();
                        this.suratSelect.addOptions(data.map(s => ({
                            value: s.id,
                            text: `${s.id}. ${s.nama_surat}`
                        })));
                    }
                })
                .catch(() => {
                    this.isLoadingSurat = false;
                    this.showNotification('Gagal memuat daftar surat.', 'error');
                });
        },

        // TomSelect init
        initTomSelect() {
            this.santriSelect = new TomSelect('#santri_id', {
                placeholder: '-- Pilih Santri --',
                searchField: ['text'],
                create: false
            });
            this.suratSelect = new TomSelect('#surat_id', {
                placeholder: '-- Pilih Surat --',
                searchField: ['text'],
                create: false,
                onChange: (value) => { // Validasi ayat dinamis
                    if (value) {
                        const surat = this.suratList.find(s => s.id == value);
                        this.selectedSuratMaxAyah = surat ? surat.jumlah_ayat : null;
                    } else {
                        this.selectedSuratMaxAyah = null;
                    }
                }
            });

            this.fetchSantri();
            this.fetchSurat();
        },

        // Delete setoran
        deleteSetoran(id) {
            if (confirm('Yakin ingin menghapus setoran ini?')) {
                // [PERBAIKAN DI SINI] Ubah URL dari '/pengajaran/tahfidz/' ke '/ubudiyah/'
                fetch(`/ubudiyah/${id}`, { 
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(r => {
                    if (!r.ok) throw new Error('Gagal menghapus setoran');
                    return r.json();
                })
                .then(data => {
                    this.showNotification(data.message || 'Setoran berhasil dihapus');
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(err => this.showNotification(err.message, 'error'));
            }
        }
    }" x-init="initTomSelect()">

        <div x-show="showToast" class="fixed top-4 right-4 z-50 max-w-xs w-full" x-transition>
            <div :class="toastType === 'success' ? 'bg-green-500' : 'bg-red-500'" class="text-white px-4 py-2 rounded-lg shadow-lg">
                <span x-text="toastMessage"></span>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Input Setoran Tahfidz</h1>
                            <p class="mt-1 text-sm sm:text-base text-slate-600">Pilih kelas dan tanggal untuk mengelola setoran santri.</p>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                <x-alert type="success" :message="session('success')" />
                @endif
                @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg" role="alert">
                    <p class="font-bold">Terjadi Kesalahan:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200">
                    <form action="{{ route('ubudiyah.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-3 items-end">
                        <div>
                            <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                            <select name="kelas_id" id="kelas_id" x-model="selectedKelas" @change="fetchSantri()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm sm:text-base" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm sm:text-base" required>
                        </div>

                        <div class="sm:col-auto">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 w-full sm:w-auto">
                                Tampilkan
                            </button>
                        </div>
                    </form>
                </div>

                @if(request()->filled('kelas_id'))
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-lg font-semibold text-gray-900">Input Setoran Baru</h3>
                    </div>

                    <form action="{{ route('ubudiyah.store') }}" method="POST" class="p-4 sm:p-6 space-y-6">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ request('kelas_id') }}">
                        <input type="hidden" name="tanggal_setor" value="{{ $tanggal }}">

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="sm:col-span-2 lg:col-span-1">
                                <label for="santri_id" class="block text-sm font-medium text-gray-700">Santri</label>
                                <select name="santri_id" id="santri_id" :disabled="isLoadingSantri || !selectedKelas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                    <template x-if="!selectedKelas">
                                        <option value="">-- Pilih Kelas Dulu --</option>
                                    </template>
                                    <template x-if="isLoadingSantri">
                                        <option value="">Memuat santri...</option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label for="surat_id" class="block text-sm font-medium text-gray-700">Surat</label>
                                <select name="surat_id" id="surat_id" :disabled="isLoadingSurat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                    <template x-if="isLoadingSurat">
                                        <option value="">Memuat surat...</option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Setoran</label>
                                <select name="jenis_setoran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                    <option value="baru">Baru</option>
                                    <option value="murojaah">Murojaah</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ayat Mulai</label>
                                <input type="number" name="ayat_mulai" min="1" :max="selectedSuratMaxAyah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ayat Selesai</label>
                                <input type="number" name="ayat_selesai" min="1" :max="selectedSuratMaxAyah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                <template x-if="selectedSuratMaxAyah">
                                    <p class="text-xs text-slate-500 mt-1">
                                        (Jumlah ayat: <span x-text="selectedSuratMaxAyah"></span>)
                                    </p>
                                </template>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nilai</label>
                                <select name="nilai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                    <option value="mumtaz">Mumtaz</option>
                                    <option value="jayyid_jiddan">Jayyid Jiddan</option>
                                    <option value="jayyid">Jayyid</option>
                                    <option value="maqbul">Maqbul</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2 lg:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <input type="text" name="keterangan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                            </div>

                            <div class="sm:col-span-2 lg:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Penerima Setoran</label>
                                <input type="text" name="penerima_manual" placeholder="Nama penerima Setoran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                                <p class="text-xs text-slate-500 mt-1">
                                    Info: Wajib diisi.
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Simpan Setoran
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Log Setoran ({{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }})
                        </h3>
                    </div>

                    <div class="hidden sm:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Santri</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Setoran</th>
                                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase">Nilai</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Penerima</th>
                                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($setorans as $setoran)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">{{ $setoran->santri->nama }}</div>
                                        <div class="text-sm text-slate-500">NIS: {{ $setoran->santri->nis }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium">{{ $setoran->surat->nama_surat }}: {{ $setoran->ayat_mulai }}-{{ $setoran->ayat_selesai }}</div>
                                        <span class="text-sm text-slate-500">
                                            {{ $setoran->jenis_setoran == 'baru' ? 'Setoran Baru' : 'Murojaah' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                                    {{ $setoran->nilai == 'mumtaz' ? 'bg-green-100 text-green-800' :
                                                       ($setoran->nilai == 'jayyid_jiddan' ? 'bg-blue-100 text-blue-800' :
                                                       ($setoran->nilai == 'jayyid' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $setoran->nilai)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $setoran->penerima_manual ?? $setoran->teacher->nama ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button" @click="deleteSetoran({{ $setoran->id }})" class="text-red-600 hover:text-red-800 text-sm">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-slate-500">
                                        Belum ada setoran untuk kelas ini pada tanggal yang dipilih.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="sm:hidden space-y-4 px-4 py-4">
                        @forelse ($setorans as $setoran)
                        <div class="bg-white border border-slate-200 rounded-lg p-4">
                            <div class="font-medium text-slate-900">{{ $setoran->santri->nama }}</div>
                            <div class="text-sm text-slate-500 mb-2">NIS: {{ $setoran->santri->nis }}</div>

                            <div class="text-sm space-y-1">
                                <div><span class="font-medium">Setoran:</span> {{ $setoran->surat->nama_surat }} ({{ $setoran->ayat_mulai }}-{{ $setoran->ayat_selesai }})</div>
                                <div><span class="font-medium">Jenis:</span> {{ $setoran->jenis_setoran == 'baru' ? 'Baru' : 'Murojaah' }}</div>
                                <div><span class="font-medium">Nilai:</span> {{ ucfirst(str_replace('_', ' ', $setoran->nilai)) }}</div>
                                <div><span class="font-medium">Penerima:</span> {{ $setoran->penerima_manual ?? $setoran->teacher->nama ?? '-' }}</div>
                            </div>

                            <div class="mt-3 flex justify-end">
                                <button type="button" @click="deleteSetoran({{ $setoran->id }})" class="rounded-md bg-red-600 text-white px-4 py-2 text-sm hover:bg-red-700">
                                    Hapus
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-slate-500 py-8">
                            Belum ada setoran untuk kelas ini pada tanggal yang dipilih.
                        </div>
                        @endforelse
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
