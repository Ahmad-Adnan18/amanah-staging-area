<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Tambah Catatan Pelanggaran</h1>
                    <p class="mt-1 text-slate-600">Isi formulir untuk mencatat pelanggaran baru.</p>
                </div>

                <!-- Form -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div x-data="{
                        selectedKelas: '{{ old('kelas_id') }}',
                        santriList: [],
                        isLoading: false,
                        fetchSantris() {
                            if (!this.selectedKelas) {
                                this.santriList = [];
                                return;
                            }
                            this.isLoading = true;
                            this.santriList = [];
                            fetch(`/pengajaran/kelas/${this.selectedKelas}/santri-json`)
                                .then(response => response.json())
                                .then(data => {
                                    this.santriList = data;
                                    this.isLoading = false;
                                });
                        }
                    }" x-init="fetchSantris()">
                        <form action="{{ route('pelanggaran.store') }}" method="POST">
                            @csrf
                            <div class="p-6 space-y-6">
                                <div>
                                    <label for="kelas_id" class="block text-sm font-medium text-gray-700">Pilih Kelas</label>
                                    <select id="kelas_id" x-model="selectedKelas" @change="fetchSantris()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="santri_id" class="block text-sm font-medium text-gray-700">Pilih Santri</label>
                                    <select name="santri_id" id="santri_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" :disabled="!selectedKelas || isLoading" required>
                                        <template x-if="isLoading">
                                            <option>Memuat santri...</option>
                                        </template>
                                        <template x-if="!isLoading && selectedKelas && santriList.length > 0">
                                            <option value="">-- Pilih Santri --</option>
                                        </template>
                                        <template x-if="!isLoading && selectedKelas && santriList.length == 0">
                                            <option value="">-- Tidak ada santri di kelas ini --</option>
                                        </template>
                                        <template x-for="santri in santriList" :key="santri.id">
                                            <option :value="santri.id" x-text="santri.nama"></option>
                                        </template>
                                        <template x-if="!selectedKelas">
                                            <option value="">-- Pilih kelas terlebih dahulu --</option>
                                        </template>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('santri_id')" />
                                </div>

                                <div>
                                    <label for="jenis_pelanggaran" class="block text-sm font-medium text-gray-700">Jenis Pelanggaran</label>
                                    <input id="jenis_pelanggaran" name="jenis_pelanggaran" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" value="{{ old('jenis_pelanggaran') }}" required />
                                </div>
                                <div>
                                    <label for="tanggal_kejadian" class="block text-sm font-medium text-gray-700">Tanggal Kejadian</label>
                                    <input id="tanggal_kejadian" name="tanggal_kejadian" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" value="{{ old('tanggal_kejadian', now()->format('Y-m-d')) }}" required />
                                </div>
                                <div>
                                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan (Opsional)</label>
                                    <textarea id="keterangan" name="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('keterangan') }}</textarea>
                                </div>
                                <div>
                                    <label for="dicatat_oleh" class="block text-sm font-medium text-gray-700">Dicatat Oleh</label>
                                    <input id="dicatat_oleh" name="dicatat_oleh" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" value="{{ old('dicatat_oleh', Auth::user()->name) }}" required />
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-4">
                                <button type="button" onclick="history.back()" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 cursor-pointer">
                                    Batal
                                </button>
                                <button type="submit" onclick="history.back()" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
