<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">
                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Form Perizinan Santri</h1>
                    <p class="mt-1 text-slate-600">Membuat izin untuk: <span class="font-semibold text-red-700">{{ $santri->nama }} (NIS: {{ $santri->nis }})</span></p>
                </div>

                <!-- Form -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('perizinan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="santri_id" value="{{ $santri->id }}">

                        @php
                        $userRole = Auth::user()->role;
                        $kategori = '';
                        $jenisIzinOptions = [];

                        if ($userRole === 'admin') {
                        $jenisIzinOptions = [
                        'Pulang' => 'Pulang',
                        'Izin Piket Rayon' => 'Izin Piket Rayon',
                        'Sakit Ringan (Izin Tidak Masuk Kelas)' => 'Sakit Ringan',
                        'Sakit Berat (Izin Pulang)' => 'Sakit Berat (Izin Pulang)',
                        ];
                        } elseif ($userRole === 'pengasuhan') {
                        $kategori = 'Pengasuhan';
                        $jenisIzinOptions = [
                        'Pulang' => 'Pulang',
                        'Izin Piket Rayon' => 'Izin Piket Rayon',
                        ];
                        } elseif ($userRole === 'kesehatan') {
                        $kategori = 'Kesehatan';
                        $jenisIzinOptions = [
                        'Sakit Ringan (Izin Tidak Masuk Kelas)' => 'Sakit Ringan',
                        'Sakit Berat (Izin Pulang)' => 'Sakit Berat (Izin Pulang)',
                        ];
                        }
                        @endphp

                        <div class="p-6 space-y-6" x-data="{ jenisIzin: '{{ old('jenis_izin', array_key_first($jenisIzinOptions)) }}' }">

                            @if ($userRole === 'admin')
                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori Izin</label>
                                <select name="kategori" id="kategori" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="Pengasuhan" @selected(old('kategori')=='Pengasuhan' )>Pengasuhan</option>
                                    <option value="Kesehatan" @selected(old('kategori')=='Kesehatan' )>Kesehatan</option>
                                </select>
                                <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
                            </div>
                            @else
                            <input type="hidden" name="kategori" value="{{ $kategori }}">
                            @endif

                            <div>
                                <label for="jenis_izin" class="block text-sm font-medium text-gray-700">Jenis Izin</label>
                                <select name="jenis_izin" id="jenis_izin" x-model="jenisIzin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    @foreach ($jenisIzinOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('jenis_izin')" class="mt-2" />
                            </div>

                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai Izin</label>
                                <input id="tanggal_mulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', now()->format('Y-m-d')) }}" required />
                                <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                            </div>

                            <div x-show="jenisIzin.includes('Pulang')" x-transition>
                                <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700">Wajib Diisi Tanggal Kembali</label>
                                <input id="tanggal_akhir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}" />
                                <x-input-error :messages="$errors->get('tanggal_akhir')" class="mt-2" />
                            </div>

                            <div>
                                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan / Diagnosa</label>
                                <textarea id="keterangan" name="keterangan" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('keterangan') }}</textarea>
                                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-4">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</a>
                            <button type="submit" onclick="history.back()" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Simpan Izin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
