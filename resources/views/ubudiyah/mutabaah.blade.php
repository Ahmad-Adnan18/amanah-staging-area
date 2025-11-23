<x-app-layout>
    <div class="bg-slate-50 min-h-screen" x-data="{
        selectedKelas: '{{ request('kelas_id') }}',
        selectedSantri: '{{ request('santri_id') }}',
        santriList: [],
        isLoadingSantri: false,
        santriSelect: null,
        
        fetchSantri() {
            if (!this.selectedKelas) {
                this.santriList = [];
                if (this.santriSelect) this.santriSelect.clearOptions();
                return;
            }
            this.isLoadingSantri = true;
            fetch(`/ubudiyah/get-santri-by-kelas/${this.selectedKelas}`)
                .then(response => response.json())
                .then(data => {
                    this.santriList = data;
                    this.isLoadingSantri = false;
                    if (this.santriSelect) {
                        this.santriSelect.clearOptions();
                        this.santriSelect.addOptions(data.map(santri => ({ value: santri.id, text: santri.nama })));
                        // Jika selectedSantri sudah ada (dari request), set nilainya
                        if (this.selectedSantri) {
                            this.santriSelect.setValue(this.selectedSantri);
                        }
                    }
                });
        },
        initTomSelect() {
            this.santriSelect = new TomSelect('#santri_id', { 
                placeholder: '-- Pilih Santri --',
                onChange: (value) => { this.selectedSantri = value }
            });
            this.fetchSantri(); // Panggil saat init
        }
    }" x-init="initTomSelect()">

        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Laporan Mutaba'ah Tahfidz</h1>
                            <p class="mt-1 text-sm sm:text-base text-slate-600">Lihat progres dan riwayat setoran hafalan per santri.</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <a href="{{ route('ubudiyah.index') }}" class="inline-flex items-center justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Input Setoran
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-white p-6 rounded-2xl shadow-lg border border-slate-200">
                    <form action="{{ route('ubudiyah.mutabaah') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-3 items-end">
                        <input type="hidden" name="santri_id" x-model="selectedSantri">
                        <div>
                            <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                            <select name="kelas_id" id="kelas_id" x-model="selectedKelas" @change="fetchSantri()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm sm:text-base" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="santri_id" class="block text-sm font-medium text-gray-700">Santri</label>
                            <select id="santri_id" :disabled="isLoadingSantri || !selectedKelas" required>
                                <template x-if="!selectedKelas">
                                    <option value="">-- Pilih Kelas Dulu --</option>
                                </template>
                                <template x-if="isLoadingSantri">
                                    <option value="">Memuat...</option>
                                </template>
                            </select>
                        </div>
                        <div class="col-span-full sm:col-auto">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 w-full sm:w-auto">
                                Tampilkan Riwayat
                            </button>
                        </div>
                    </form>
                </div>

                @if ($santri)
                <div class="mt-8 bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h3 class="text-xl font-semibold text-gray-900">{{ $santri->nama }}</h3>
                    <p class="text-sm text-slate-600">Kelas: {{ $santri->kelas->nama_kelas }}</p>

                    @php
                    $totalSetoranBaru = $setorans->where('jenis_setoran', 'baru')->count();
                    $totalMurojaah = $setorans->where('jenis_setoran', 'murojaah')->count();
                    $setoranTerakhir = $setorans->first();
                    @endphp

                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                            <div class="text-sm text-green-700">Total Setoran Baru</div>
                            <div class="text-2xl font-bold text-green-900">{{ $totalSetoranBaru }} Kali</div>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                            <div class="text-sm text-blue-700">Total Murojaah</div>
                            <div class="text-2xl font-bold text-blue-900">{{ $totalMurojaah }} Kali</div>
                        </div>
                        <div class="bg-slate-50 border border-slate-200 p-4 rounded-lg">
                            <div class="text-sm text-slate-700">Setoran Terakhir</div>
                            <div class="text-lg font-bold text-slate-900">
                                {{ $setoranTerakhir ? $setoranTerakhir->tanggal_setor->isoFormat('D MMM Y') : 'Belum ada' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <h3 class="px-6 py-4 text-lg font-semibold text-gray-900">Riwayat Setoran (Diurutkan dari terbaru)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Setoran</th>
                                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase">Nilai</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Ustadz Penerima</th>
                                    ----------------
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($setorans as $setoran)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $setoran->tanggal_setor->isoFormat('dddd, D MMMM Y') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">{{ $setoran->surat->nama_surat }}: {{ $setoran->ayat_mulai }}-{{ $setoran->ayat_selesai }}</div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                            {{ $setoran->jenis_setoran == 'baru' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $setoran->jenis_setoran == 'baru' ? 'Setoran Baru' : 'Murojaah' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium capitalize">{{ $setoran->nilai }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $setoran->penerima_manual ?? $setoran->teacher->nama ?? '-' }}</td>
                                    ----------------
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-slate-500">Belum ada riwayat setoran untuk santri ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @elseif(request()->filled('santri_id'))
                <div class="mt-8 bg-white p-6 rounded-2xl shadow-lg border border-slate-200 text-center">
                    <p class="text-slate-500">Santri tidak ditemukan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
