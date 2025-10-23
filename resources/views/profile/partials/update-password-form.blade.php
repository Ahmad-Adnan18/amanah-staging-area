<section class="space-y-6">
    <header class="pb-4 border-b border-slate-200">
        <div class="flex items-center gap-3">
            <div class="bg-red-100 p-2 rounded-lg">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    {{ __('Perbarui Kata Sandi') }}
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
                </p>
            </div>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6" x-data>
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" :value="__('Kata Sandi Saat Ini')" class="text-slate-700 font-medium" />
            <div class="relative mt-2" x-data="{ showCurrentPassword: false }">
                <input
                    id="current_password"
                    name="current_password"
                    type="password"
                    x-bind:type="showCurrentPassword ? 'text' : 'password'"
                    class="block w-full pr-10 pl-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('current_password') border-red-500 ring-2 ring-red-500 @enderror shadow-sm transition-all duration-200 bg-white"
                    autocomplete="current-password"
                    required
                    placeholder="Masukkan kata sandi saat ini"
                />
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    @click="showCurrentPassword = !showCurrentPassword"
                    aria-label="Toggle password visibility"
                >
                    <svg x-show="!showCurrentPassword" class="h-5 w-5 text-slate-400 hover:text-slate-600 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showCurrentPassword" class="h-5 w-5 text-slate-400 hover:text-slate-600 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Kata Sandi Baru')" class="text-slate-700 font-medium" />
            <div class="relative mt-2" x-data="{ showPassword: false }">
                <input
                    id="password"
                    name="password"
                    type="password"
                    x-bind:type="showPassword ? 'text' : 'password'"
                    class="block w-full pr-10 pl-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('password') border-red-500 ring-2 ring-red-500 @enderror shadow-sm transition-all duration-200 bg-white"
                    autocomplete="new-password"
                    required
                    placeholder="Masukkan kata sandi baru"
                />
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    @click="showPassword = !showPassword"
                    aria-label="Toggle password visibility"
                >
                    <svg x-show="!showPassword" class="h-5 w-5 text-slate-400 hover:text-slate-600 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPassword" class="h-5 w-5 text-slate-400 hover:text-slate-600 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" class="text-slate-700 font-medium" />
            <div class="relative mt-2" x-data="{ showConfirmPassword: false }">
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    x-bind:type="showConfirmPassword ? 'text' : 'password'"
                    class="block w-full pr-10 pl-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('password_confirmation') border-red-500 ring-2 ring-red-500 @enderror shadow-sm transition-all duration-200 bg-white"
                    autocomplete="new-password"
                    required
                    placeholder="Konfirmasi kata sandi baru"
                />
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    @click="showConfirmPassword = !showConfirmPassword"
                    aria-label="Toggle password visibility"
                >
                    <svg x-show="!showConfirmPassword" class="h-5 w-5 text-slate-400 hover:text-slate-600 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showConfirmPassword" class="h-5 w-5 text-slate-400 hover:text-slate-600 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ __('Perbarui Kata Sandi') }}
            </button>

            @if (session('status') === 'password-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center gap-2 text-sm text-green-600 bg-green-50 px-4 py-2 rounded-lg border border-green-200"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Kata sandi berhasil diperbarui.') }}
                </div>
            @endif
        </div>
    </form>
</section>