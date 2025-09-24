<x-app-layout>
    {{-- Menggunakan Alpine.js untuk dropdown dinamis --}}
    <div class="bg-slate-50 min-h-screen" x-data="{
        selectedKelas: '{{ request('kelas_id') }}',
        selectedMapel: '{{ request('mata_pelajaran_id') }}',
        mapelList: [],
        isLoading: false,
        fetchMapel() {
            if (!this.selectedKelas) {
                this.mapelList = [];
                return;
            }
            this.isLoading = true;
            // [PERBAIKAN] Menggunakan URL yang benar dari NilaiController
            fetch(`/akademik/nilai/get-mapel-by-kelas/${this.selectedKelas}`)
                .then(response => response.json())
                .then(data => {
                    this.mapelList = data;
                    this.isLoading = false;
                })
                .catch(() => {
                    this.isLoading = false;
                    alert('Gagal memuat daftar mata pelajaran.');
                });
        }
    }" x-init="fetchMapel()">

        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class.space-y-8">
                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Input Nilai Santri</h1>
                    <p class="mt-1 text-slate-600">Pilih kelas, mata pelajaran, dan sesi penilaian untuk memulai.</p>
                </div>

                @if (session('success'))
                    <div class="mt-8 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                        <p class="font-bold">Berhasil!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Form Filter -->
                <div class="mt-8 bg-white p-6 rounded-2xl shadow-lg border border-slate-200">
                    <form action="{{ route('akademik.nilai.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 items-end">
                        {{-- Kelas --}}
                        <div class="lg:col-span-1">
                            <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                            <select name="kelas_id" id="kelas_id" x-model="selectedKelas" @change="fetchMapel()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Mata Pelajaran (Dinamis) --}}
                        <div class="lg:col-span-1">
                            <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" id="mata_pelajaran_id" x-model="selectedMapel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" :disabled="isLoading || !selectedKelas" required>
                                <template x-if="isLoading"><option>Memuat...</option></template>
                                <template x-if="!isLoading && !selectedKelas"><option value="">-- Pilih Kelas Dulu --</option></template>
                                <template x-if="!isLoading && selectedKelas && mapelList.length === 0"><option value="">-- Mapel Kosong --</option></template>
                                <template x-if="!isLoading && selectedKelas && mapelList.length > 0">
                                    <option value="">-- Pilih Mapel --</option>
                                </template>
                                <template x-for="mapel in mapelList" :key="mapel.id">
                                    <option :value="mapel.id" x-text="mapel.nama_pelajaran"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Semester --}}
                        <div class="lg:col-span-1">
                            <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                            <select name="semester" id="semester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                                <option value="Ganjil" @selected(request('semester') == 'Ganjil')>Ganjil</option>
                                <option value="Genap" @selected(request('semester') == 'Genap')>Genap</option>
                            </select>
                        </div>

                        {{-- Tahun Ajaran --}}
                        <div class="lg:col-span-1">
                            <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ request('tahun_ajaran', date('Y').'/'.(date('Y')+1)) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        </div>
                        
                        {{-- Jenis Penilaian & Tombol --}}
                        <div class="lg:col-span-1 flex flex-col justify-end">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="jenis_penilaian" class="block text-sm font-medium text-gray-700">Jenis Nilai</label>
                                    <select name="jenis_penilaian" id="jenis_penilaian" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                                        <option value="nilai_tugas" @selected(request('jenis_penilaian') == 'nilai_tugas')>Tugas</option>
                                        <option value="nilai_uts" @selected(request('jenis_penilaian') == 'nilai_uts')>UTS</option>
                                        <option value="nilai_uas" @selected(request('jenis_penilaian') == 'nilai_uas')>UAS</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                    Tampilkan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tabel Input Nilai -->
                @if ($santris->isNotEmpty())
                <div class="mt-8 bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('akademik.nilai.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ request('kelas_id') }}">
                        <input type="hidden" name="mata_pelajaran_id" value="{{ request('mata_pelajaran_id') }}">
                        <input type="hidden" name="semester" value="{{ request('semester') }}">
                        <input type="hidden" name="tahun_ajaran" value="{{ request('tahun_ajaran') }}">
                        <input type="hidden" name="jenis_penilaian" value="{{ request('jenis_penilaian') }}">

                        <!-- Header Tabel & Tombol Export -->
                        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                             <div>
                                <h3 class="text-lg font-semibold text-gray-900">Input Nilai {{ ucwords(str_replace('_', ' ', request('jenis_penilaian'))) }}</h3>
                                <p class="text-sm text-slate-600">Mapel: <span class="font-medium">{{ $selectedMataPelajaran->nama_pelajaran ?? '-' }}</span></p>
                            </div>
                            {{-- [PERBAIKAN] Mengganti nama rute menjadi 'akademik.nilai.export' --}}
                            <a href="{{ route('akademik.nilai.export', request()->query()) }}" class="inline-flex items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                                Export Leger
                            </a>
                        </div>

                        <!-- Tabel -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Nama Santri</th>
                                        <th class="w-40 px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase">Nilai (0-100)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach ($santris as $index => $santri)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4">
                                            <input type="hidden" name="nilais[{{ $index }}][santri_id]" value="{{ $santri->id }}">
                                            <div class="font-medium text-slate-900">{{ $santri->nama }}</div>
                                            <div class="text-sm text-slate-500">NIS: {{ $santri->nis }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php $jenisNilai = request('jenis_penilaian'); @endphp
                                            <input type="number" name="nilais[{{ $index }}][nilai]" class="block w-full text-center rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" min="0" max="100" value="{{ optional($santri->nilai->first())->$jenisNilai ?? '' }}" />
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Footer Aksi Form -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Simpan Semua Nilai
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

