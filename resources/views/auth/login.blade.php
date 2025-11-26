<x-guest-layout>
    <div class="flex min-h-screen bg-slate-50 font-sans">

        <div class="hidden lg:flex w-1/2 items-center justify-center bg-gray-900 p-12 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-5" style="background-image: url('https://www.transparenttextures.com/patterns/subtle-prism.png');"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-red-800 to-gray-900 opacity-90"></div>

            <div class="relative z-10 text-center space-y-8">
                <a href="/">
                    <img src="{{ asset('images/logo-kunka.png') }}" alt="Logo" class="mx-auto mb-8 h-24 w-auto drop-shadow-lg">
                </a>
                <h1 class="text-4xl font-bold tracking-tight text-white">
                    Aplikasi Manajemen Akademik & Harian
                </h1>
                <p class="mt-4 text-lg text-red-100 max-w-lg mx-auto">
                    Masuk untuk mengelola data perizinan dan informasi santri Pondok Pesantren.
                </p>
            </div>
        </div>

        <div class="flex w-full lg:w-1/2 items-center justify-center p-4 sm:p-8">
            <div class="w-full max-w-md space-y-8">
                <div class="lg:hidden text-center">
                    <a href="/">
                        <img src="{{ asset('images/logo-kunka-merah.png') }}" alt="Logo" class="mx-auto h-20 w-auto">
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8 sm:p-10">
                    <div class="text-left mb-8">
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900">Login Akun</h2>
                        <p class="mt-2 text-slate-600">Selamat datang kembali! Silakan masuk.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    @if($errors->any())
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            // Jika login gagal (password salah/diganti), hapus data biometric lama
                            if (window.clearCredentials) {
                                window.clearCredentials();
                                console.log('Login gagal, data biometric dihapus.');
                            }
                        });

                    </script>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6" id="login-form">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                                        <path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" /></svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="contoh@email.com" class="block w-full rounded-lg border-gray-300 py-3 pl-11 pr-4 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-red-500 focus:ring-1 focus:ring-red-500 sm:text-sm">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" /></svg>
                                </div>
                                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="block w-full rounded-lg border-gray-300 py-3 pl-11 pr-4 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-red-500 focus:ring-1 focus:ring-red-500 sm:text-sm">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat saya</label>
                            </div>
                            @if (Route::has('password.request'))
                            <div class="text-sm"><a class="font-medium text-red-600 hover:text-red-800" href="{{ route('password.request') }}">Lupa password?</a></div>
                            @endif
                        </div>

                        <div class="!mt-8 space-y-4">
                            <button type="submit" class="w-full justify-center py-3 text-base inline-flex items-center rounded-lg bg-red-700 px-4 font-semibold text-white shadow-sm hover:bg-red-600 transition-colors">
                                {{ __('Log in') }}
                            </button>

                            <div id="biometric-container" class="hidden">
                                <button type="button" onclick="window.performBiometricLogin()" class="w-full justify-center py-3 text-base inline-flex items-center rounded-lg border-2 border-red-700 px-4 font-semibold text-red-700 bg-white hover:bg-red-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-fingerprint-pattern mr-2">
                                        <path d="M12 10a2 2 0 0 0-2 2c0 1.02-.1 2.51-.26 4" />
                                        <path d="M14 13.12c0 2.38 0 6.38-1 8.88" />
                                        <path d="M17.29 21.02c.12-.6.43-2.3.5-3.02" />
                                        <path d="M2 12a10 10 0 0 1 18-6" />
                                        <path d="M2 16h.01" />
                                        <path d="M21.8 16c.2-2 .131-5.354 0-6" />
                                        <path d="M5 19.5C5.5 18 6 15 6 12a6 6 0 0 1 .34-2" />
                                        <path d="M8.65 22c.21-.66.45-1.32.57-2" />
                                        <path d="M9 6.8a6 6 0 0 1 9 5.2v2" />
                                    </svg> Login dengan Sidik Jari
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative flex justify-center"><span class="bg-white px-2 text-sm text-slate-500">Atau</span></div>
                    </div>

                    <div>
                        <a href="{{ route('register') }}" class="flex w-full justify-center rounded-lg bg-white px-3 py-3 text-sm font-semibold leading-6 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                            Buat Akun Guru
                        </a>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('wali.register') }}" class="flex w-full justify-center rounded-lg bg-amber-500 px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-amber-600 transition-colors">
                            Daftar sebagai Wali Santri
                        </a>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('download.show') }}" class="flex w-full justify-center rounded-lg bg-slate-900 px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-slate-800 transition-colors">
                            Download Aplikasi Android
                        </a>
                    </div>
                </div>

                <div class="text-center text-sm text-slate-600">
                    &copy; {{ date('Y') }} Pondok Pesantren. All rights reserved.
                    <p class="mt-1 text-slate-600">Created by Ahmad Adnan</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- 1. LOGIC MENUNGGU FUNGSI SIAP (POLLING) ---
            // Kita cek setiap setengah detik apakah app.jsx sudah selesai loading
            let attempts = 0;
            const waitForBiometric = setInterval(async () => {
                attempts++;

                // Cek apakah fungsi dari app.jsx sudah masuk ke window?
                if (window.checkBiometricSupport) {
                    clearInterval(waitForBiometric); // Stop menunggu

                    try {
                        const isAvailable = await window.checkBiometricSupport();
                        if (isAvailable) {
                            // TAMPILKAN TOMBOL
                            const btnContainer = document.getElementById('biometric-container');
                            if (btnContainer) btnContainer.classList.remove('hidden');
                        }
                    } catch (e) {
                        console.error("Gagal cek biometric:", e);
                    }
                }
                // Jika sudah 10 detik gak muncul juga, stop.
                else if (attempts >= 20) {
                    clearInterval(waitForBiometric);
                }
            }, 500); // Cek setiap 500ms

            // --- 2. LOGIC SIMPAN PASSWORD SAAT SUBMIT MANUAL ---
            const form = document.getElementById('login-form');
            if (form) {
                form.addEventListener('submit', (e) => {
                    // Ambil nilai saat itu juga
                    const emailVal = document.getElementById('email').value;
                    const passwordVal = document.getElementById('password').value;

                    // VALIDASI PENTING: Jangan simpan kalau kosong!
                    if (window.saveCredentials && emailVal.trim() !== "" && passwordVal.trim() !== "") {
                        // Simpan ke HP (Fire and Forget)
                        window.saveCredentials(emailVal, passwordVal);
                        console.log('Credential sedang disimpan ke HP...');
                    }
                });
            }
        });

    </script>
</x-guest-layout>
