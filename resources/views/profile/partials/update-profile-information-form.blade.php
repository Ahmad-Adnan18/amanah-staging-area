<section>
    <header class="pb-4 border-b border-slate-200">
        <div class="flex items-center gap-3">
            <div class="bg-red-100 p-2 rounded-lg">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    {{ __('Informasi Profil') }}
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    {{ __("Perbarui informasi profil dan alamat email akun Anda.") }}
                </p>
            </div>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-slate-700 font-medium" />
            <x-text-input id="name" name="name" type="text" class="mt-2 block w-full rounded-xl border-slate-300 focus:ring-red-500 focus:border-red-500 shadow-sm transition-all duration-200" :value="old('name', $user->name)" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap Anda" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="text-slate-700 font-medium" />
            <x-text-input id="email" name="email" type="email" class="mt-2 block w-full rounded-xl border-slate-300 focus:ring-red-500 focus:border-red-500 shadow-sm transition-all duration-200" :value="old('email', $user->email)" required autocomplete="email" placeholder="Masukkan alamat email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                <p class="text-sm text-yellow-800">
                    {{ __('Alamat email Anda belum terverifikasi.') }}

                    <button form="send-verification" class="underline text-sm text-yellow-700 hover:text-yellow-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-150">
                        {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-green-600">
                    {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <!-- Profile Photo -->
        <div x-data="{ confirmDeletePhoto: false }">
            <x-input-label for="profile_photo" :value="__('Foto Profil')" class="text-slate-700 font-medium" />
            <div class="mt-2">
                <input id="profile_photo" name="profile_photo" type="file" class="block w-full text-sm text-slate-600 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:bg-red-50 file:text-red-700 hover:file:bg-red-100 file:transition-colors file:duration-200 file:cursor-pointer" accept="image/*" />
                <p class="mt-1 text-xs text-slate-500">Format: JPG, PNG, GIF (Maks. 2MB)</p>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />

            @if ($user->profile_photo)
            <div class="mt-4 flex items-center gap-4">
                <img src="{{ Storage::url($user->profile_photo) }}" alt="Foto Profil" class="w-16 h-16 rounded-full object-cover border-2 border-slate-200">
                <div>
                    <p class="text-sm font-medium text-slate-700">{{ __('Foto saat ini') }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <button type="button" @click="confirmDeletePhoto = true" class="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-700 font-medium transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Foto
                        </button>
                    </div>
                </div>
            </div>

            <!-- Confirmation Modal for Photo Deletion -->
            <div x-show="confirmDeletePhoto" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4" @click="confirmDeletePhoto = false" x-cloak>
                <div class="bg-white rounded-xl p-6 max-w-md w-full shadow-xl border border-slate-200" @click.stop>
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-full bg-red-100">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-slate-800">Hapus Foto Profil</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Apakah Anda yakin ingin menghapus foto profil saat ini? Tindakan ini tidak dapat dibatalkan.
                        </p>
                        <div class="mt-6 flex items-center justify-center gap-3">
                            <button type="button" @click="confirmDeletePhoto = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                                Batal
                            </button>
                            <form method="post" action="{{ route('profile.update') }}" class="inline">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="delete_photo" value="1">
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="mt-4 flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center border-2 border-slate-200">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-700">{{ __('Belum ada foto profil') }}</p>
                    <p class="text-xs text-slate-500">{{ __('Unggah foto untuk melengkapi profil Anda') }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="flex items-center gap-2 text-sm text-green-600 bg-green-50 px-4 py-2 rounded-lg border border-green-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ __('Perubahan berhasil disimpan.') }}
            </div>
            @endif
        </div>
    </form>
</section>
