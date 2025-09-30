<x-app-layout>
    <div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <div class="p-6 sm:p-8 border-b border-slate-200">
                    <h1 class="text-2xl font-bold tracking-tight text-red-700">Edit Data Santri</h1>
                    <p class="mt-1 text-slate-600">Perbarui informasi untuk santri: <strong class="font-semibold text-slate-800">{{ $santri->nama }}</strong></p>
                </div>
                <div class="p-6 sm:p-8">
                    <form action="{{ route('pengajaran.santris.update', $santri) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Input tersembunyi untuk kelas_id -->
                        <input type="hidden" name="kelas_id" value="{{ $santri->kelas_id }}">

                        <div class="space-y-6">
                            <!-- NIS -->
                            <div>
                                <x-input-label for="nis" :value="__('NIS')" />
                                <x-text-input id="nis" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="nis" :value="old('nis', $santri->nis)" />
                                <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                            </div>

                            <!-- Nama -->
                            <div>
                                <x-input-label for="nama" :value="__('Nama Lengkap')" />
                                <x-text-input id="nama" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="nama" :value="old('nama', $santri->nama)" required autofocus />
                                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-600 focus:border-red-600" required>
                                    <option value="Putra" @selected(old('jenis_kelamin', $santri->jenis_kelamin) == 'Putra')>Putra</option>
                                    <option value="Putri" @selected(old('jenis_kelamin', $santri->jenis_kelamin) == 'Putri')>Putri</option>
                                </select>
                                <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                            </div>

                            <!-- Rayon -->
                            <div>
                                <x-input-label for="rayon" :value="__('Rayon')" />
                                <x-text-input id="rayon" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="rayon" :value="old('rayon', $santri->rayon)" />
                                <x-input-error :messages="$errors->get('rayon')" class="mt-2" />
                            </div>

                            <!-- Foto -->
                            <div>
                                <x-input-label for="foto" :value="__('Ganti Foto (Opsional)')" />
                                <div class="flex items-center gap-4 mt-1">
                                    @if ($santri->foto)
                                        <img src="{{ Storage::url($santri->foto) }}" alt="Foto {{ $santri->nama }}" class="h-16 w-16 object-cover rounded-full ring-2 ring-white ring-offset-2 ring-offset-slate-100">
                                    @else
                                        <span class="inline-block h-16 w-16 overflow-hidden rounded-full bg-slate-100">
                                            <svg class="h-full w-full text-slate-300 object-contain" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                    @endif
                                    <input id="foto" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition-colors duration-200" type="file" name="foto" accept="image/*">
                                </div>
                                <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end gap-4 pt-4">
                                <a href="{{ route('pengajaran.santris.index', ['kelas' => $santri->kelas_id]) }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</a>
                                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                    {{ __('Update Data') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>