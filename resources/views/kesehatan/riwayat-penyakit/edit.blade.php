<x-app-layout>
    <div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <div class="p-6 sm:p-8 border-b border-slate-200">
                    <h1 class="text-2xl font-bold tracking-tight text-red-700">Edit Riwayat Penyakit</h1>
                    <p class="mt-1 text-slate-600">Edit riwayat penyakit untuk santri:
                        <strong class="font-semibold text-slate-800">{{ $riwayatPenyakit->santri->nama }}</strong> ({{ $riwayatPenyakit->santri->nis }})
                    </p>
                </div>
                <div class="p-6 sm:p-8">
                    <form action="{{ route('kesehatan.riwayat_penyakit.update', $riwayatPenyakit) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Nama Penyakit -->
                            <div>
                                <x-input-label for="nama_penyakit" :value="__('Nama Penyakit')" />
                                <x-text-input id="nama_penyakit" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="nama_penyakit" :value="old('nama_penyakit', $riwayatPenyakit->nama_penyakit)" required autofocus />
                                <x-input-error :messages="$errors->get('nama_penyakit')" class="mt-2" />
                            </div>

                            <!-- Tanggal Diagnosis -->
                            <div>
                                <x-input-label for="tanggal_diagnosis" :value="__('Tanggal Diagnosis')" />
                                <x-text-input id="tanggal_diagnosis" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="date" name="tanggal_diagnosis" :value="old('tanggal_diagnosis', $riwayatPenyakit->tanggal_diagnosis->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('tanggal_diagnosis')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status Penyakit')" />
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-600 focus:border-red-600" required>
                                    <option value="">Pilih Status</option>
                                    <option value="aktif" @selected(old('status', $riwayatPenyakit->status) == 'aktif')>Aktif</option>
                                    <option value="sembuh" @selected(old('status', $riwayatPenyakit->status) == 'sembuh')>Sembuh</option>
                                    <option value="kronis" @selected(old('status', $riwayatPenyakit->status) == 'kronis')>Kronis</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <x-input-label for="keterangan" :value="__('Keterangan (Opsional)')" />
                                <textarea id="keterangan" name="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-600 focus:border-red-600" placeholder="Deskripsi gejala, kondisi, atau informasi tambahan...">{{ old('keterangan', $riwayatPenyakit->keterangan) }}</textarea>
                                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                            </div>

                            <!-- Penanganan -->
                            <div>
                                <x-input-label for="penanganan" :value="__('Penanganan (Opsional)')" />
                                <textarea id="penanganan" name="penanganan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-600 focus:border-red-600" placeholder="Tindakan medis, obat-obatan, atau penanganan yang diberikan...">{{ old('penanganan', $riwayatPenyakit->penanganan) }}</textarea>
                                <x-input-error :messages="$errors->get('penanganan')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end gap-4 pt-4">
                                <a href="{{ route('santri.profil.show', $riwayatPenyakit->santri) }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    Batal
                                </a>
                                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                    Update Riwayat Penyakit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
