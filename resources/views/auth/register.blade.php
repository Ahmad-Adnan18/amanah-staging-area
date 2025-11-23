<x-guest-layout>
    <div class="flex min-h-screen bg-slate-50 font-sans">

        <!-- Kolom Kiri: Panel Branding & Visual -->
        <div class="hidden lg:flex w-1/2 items-center justify-center bg-gray-900 p-12 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-5" style="background-image: url('https://www.transparenttextures.com/patterns/subtle-prism.png');"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-red-800 to-gray-900 opacity-90"></div>

            <!-- Konten Branding -->
            <div class="relative z-10 text-center space-y-8">
                <a href="/">
                    <img src="{{ asset('images/logo-kunka.png') }}" alt="Logo Kun Karima" class="mx-auto mb-8 h-24 w-auto drop-shadow-lg">
                </a>
                <h1 class="text-4xl font-bold tracking-tight text-white">
                    Sistem Informasi Pondok
                </h1>
                <p class="mt-4 text-lg text-red-100 max-w-lg mx-auto">
                    Daftarkan akun Anda untuk mulai mengelola data perizinan dan informasi santri.
                </p>
            </div>
        </div>

        <!-- Kolom Kanan: Form Register -->
        <div class="flex w-full lg:w-1/2 items-center justify-center p-4 sm:p-8">
            <div class="w-full max-w-md space-y-8">
                <!-- Logo untuk tampilan mobile -->
                <div class="lg:hidden text-center">
                    <a href="/">
                        <img src="{{ asset('images/logo-kunka-merah.png') }}" alt="Logo Kun Karima" class="mx-auto h-20 w-auto">
                    </a>
                </div>

                <!-- Kartu Form Register -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8 sm:p-10">
                    <div class="text-left mb-8">
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900">Buat Akun Baru</h2>
                        <p class="mt-2 text-slate-600">Silakan isi data berikut untuk mendaftar.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                </div>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama Anda" class="block w-full rounded-lg border-gray-300 py-3 pl-11 pr-4 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-red-500 focus:ring-1 focus:ring-red-500 sm:text-sm">
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                                        <path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" /></svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="contoh@email.com" class="block w-full rounded-lg border-gray-300 py-3 pl-11 pr-4 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-red-500 focus:ring-1 focus:ring-red-500 sm:text-sm">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" /></svg>
                                </div>
                                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="block w-full rounded-lg border-gray-300 py-3 pl-11 pr-4 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-red-500 focus:ring-1 focus:ring-red-500 sm:text-sm">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" /></svg>
                                </div>
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="block w-full rounded-lg border-gray-300 py-3 pl-11 pr-4 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-red-500 focus:ring-1 focus:ring-red-500 sm:text-sm">
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- [BARU] Kode Registrasi Guru -->
                        <div class="mt-6">
                            <!-- Tambahkan mt-6 atau space-y-6 pada form jika belum ada -->
                            <label for="registration_code" class="block text-sm font-medium text-gray-700 mb-1">Kode Registrasi Guru</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" /></svg>
                                </div>
                                <input id="registration_code" type="text" name="registration_code" required placeholder="Masukkan kode rahasia Anda" class="block w-full rounded-lg border-gray-300 py-3 pl-11 pr-4 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-red-500 focus:ring-1 focus:ring-red-500 sm:text-sm">
                            </div>
                            <x-input-error :messages="$errors->get('registration_code')" class="mt-2" />
                        </div>

                        <!-- Tombol Register & Link Login -->
                        <div class="flex items-center justify-between pt-2">
                            <a class="text-sm text-red-600 underline rounded-md hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" href="{{ route('login') }}">
                                {{ __('Sudah punya akun?') }}
                            </a>
                            <button type="submit" class="inline-flex justify-center rounded-lg bg-red-700 px-4 py-2.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-600 transition-colors">
                                Daftar Akun
                            </button>
                        </div>
                    </form>
                </div>

                <div class="text-center text-sm text-slate-600">
                    &copy; {{ date('Y') }} Pondok Pesantren Kun Karima. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
