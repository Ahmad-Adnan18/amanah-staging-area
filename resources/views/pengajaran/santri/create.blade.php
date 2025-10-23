 <x-app-layout>
     <div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
         <div class="max-w-4xl mx-auto">
             <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                 <div class="p-6 sm:p-8 border-b border-slate-200">
                     {{-- Style: Warna judul disesuaikan --}}
                     <h1 class="text-2xl font-bold tracking-tight text-red-700">Tambah Santri Baru ke Kelas: {{ $kelas->nama_kelas }}</h1>
                     <p class="mt-1 text-slate-600">Isi formulir di bawah ini untuk mendaftarkan santri baru.</p>
                 </div>
                 <div class="p-6 sm:p-8">
                     <form action="{{ route('pengajaran.santris.store') }}" method="POST" enctype="multipart/form-data">
                         @csrf
                         <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                             <!-- NIS -->
                             <div>
                                 <x-input-label for="nis" :value="__('NIS (Opsional)')" />
                                 <x-text-input id="nis" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="nis" :value="old('nis')" />
                                 <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                             </div>

                             <!-- Nama Lengkap -->
                             <div>
                                 <x-input-label for="nama" :value="__('Nama Lengkap')" />
                                 <x-text-input id="nama" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="nama" :value="old('nama')" required autofocus />
                                 <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                             </div>

                             <!-- Jenis Kelamin -->
                             <div>
                                 <x-input-label for="jenis_kelamin" value="Jenis Kelamin" />
                                 <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                     <option value="" @selected(old('jenis_kelamin') == '')>Pilih Jenis Kelamin</option>
                                     <option value="Putra" @selected(old('jenis_kelamin') == 'Putra')>Putra</option>
                                     <option value="Putri" @selected(old('jenis_kelamin') == 'Putri')>Putri</option>
                                 </select>
                                 <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                             </div>

                             <!-- Tempat Lahir -->
                             <div>
                                 <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
                                 <x-text-input id="tempat_lahir" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="tempat_lahir" :value="old('tempat_lahir')" required />
                                 <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
                             </div>

                             <!-- Tanggal Lahir -->
                             <div>
                                 <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                                 <x-text-input id="tanggal_lahir" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="date" name="tanggal_lahir" :value="old('tanggal_lahir')" required />
                                 <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                             </div>

                             <!-- Agama -->
                             <div>
                                 <x-input-label for="agama" value="Agama" />
                                 <select name="agama" id="agama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                     <option value="" @selected(old('agama') == '')>Pilih Agama</option>
                                     <option value="Islam" @selected(old('agama') == 'Islam')>Islam</option>
                                     <option value="Kristen" @selected(old('agama') == 'Kristen')>Kristen</option>
                                     <option value="Katolik" @selected(old('agama') == 'Katolik')>Katolik</option>
                                     <option value="Hindu" @selected(old('agama') == 'Hindu')>Hindu</option>
                                     <option value="Buddha" @selected(old('agama') == 'Buddha')>Buddha</option>
                                     <option value="Konghucu" @selected(old('agama') == 'Konghucu')>Konghucu</option>
                                 </select>
                                 <x-input-error :messages="$errors->get('agama')" class="mt-2" />
                             </div>

                             <!-- Alamat -->
                             <div class="md:col-span-2">
                                 <x-input-label for="alamat" :value="__('Alamat')" />
                                 <textarea id="alamat" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600 rounded-md border-gray-300" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                                 <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                             </div>

                             <!-- No. Telepon -->
                             <div>
                                 <x-input-label for="no_telepon" :value="__('No. Telepon')" />
                                 <x-text-input id="no_telepon" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="no_telepon" :value="old('no_telepon')" />
                                 <x-input-error :messages="$errors->get('no_telepon')" class="mt-2" />
                             </div>

                             <!-- Email -->
                             <div>
                                 <x-input-label for="email" :value="__('Email')" />
                                 <x-text-input id="email" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="email" name="email" :value="old('email')" />
                                 <x-input-error :messages="$errors->get('email')" class="mt-2" />
                             </div>

                             <!-- Rayon -->
                             <div>
                                 <x-input-label for="rayon" :value="__('Rayon (Opsional)')" />
                                 <x-text-input id="rayon" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="rayon" :value="old('rayon')" />
                                 <x-input-error :messages="$errors->get('rayon')" class="mt-2" />
                             </div>

                             <!-- Asal Sekolah -->
                             <div class="md:col-span-2">
                                 <x-input-label for="asal_sekolah" :value="__('Asal Sekolah')" />
                                 <x-text-input id="asal_sekolah" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="asal_sekolah" :value="old('asal_sekolah')" />
                                 <x-input-error :messages="$errors->get('asal_sekolah')" class="mt-2" />
                             </div>

                             <!-- Nama Ayah -->
                             <div>
                                 <x-input-label for="nama_ayah" :value="__('Nama Ayah')" />
                                 <x-text-input id="nama_ayah" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="nama_ayah" :value="old('nama_ayah')" />
                                 <x-input-error :messages="$errors->get('nama_ayah')" class="mt-2" />
                             </div>

                             <!-- Nama Ibu -->
                             <div>
                                 <x-input-label for="nama_ibu" :value="__('Nama Ibu')" />
                                 <x-text-input id="nama_ibu" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="nama_ibu" :value="old('nama_ibu')" />
                                 <x-input-error :messages="$errors->get('nama_ibu')" class="mt-2" />
                             </div>

                             <!-- Foto -->
                             <div class="md:col-span-2">
                                 <x-input-label for="foto" :value="__('Foto (Opsional)')" />
                                 <input id="foto" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition-colors duration-200" type="file" name="foto">
                                 <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                             </div>

                         </div>

                         <div class="flex items-center justify-end gap-4 pt-6">
                             <a href="{{ route('pengajaran.santris.index', $kelas) }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</a>
                             <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                 {{ __('Simpan') }}
                             </button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 </x-app-layout>
