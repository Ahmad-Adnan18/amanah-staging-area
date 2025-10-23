<x-guest-layout>
    <div class="flex min-h-screen bg-slate-50 font-sans">
        <!-- Kolom Kiri: Panel Branding & Visual -->
        <div class="hidden lg:flex w-1/2 items-center justify-center bg-gray-900 p-12 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-5" style="background-image: url('https://www.transparenttextures.com/patterns/subtle-prism.png');"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-red-800 to-gray-900 opacity-90"></div>
            <div class="relative z-10 text-center space-y-8">
                <a href="/">
                    <img src="{{ asset('images/logo-kunka.png') }}" alt="Logo Kun Karima" class="mx-auto mb-8 h-24 w-auto drop-shadow-lg">
                </a>
                <h1 class="text-4xl font-bold tracking-tight text-white">
                    Lupa Password?
                </h1>
                <p class="mt-4 text-lg text-red-100 max-w-lg mx-auto">
                    Silakan hubungi pihak pondok pesantren untuk mereset password Anda.
                </p>
            </div>
        </div>

        <!-- Kolom Kanan: Pemberitahuan Reset Password -->
        <div class="flex w-full lg:w-1/2 items-center justify-center p-4 sm:p-8">
            <div class="w-full max-w-md space-y-8">
                <!-- Logo untuk tampilan mobile -->
                <div class="lg:hidden text-center">
                    <a href="/">
                        <img src="{{ asset('images/logo-kunka-merah.png') }}" alt="Logo Kun Karima" class="mx-auto h-20 w-auto">
                    </a>
                </div>

                <!-- Kartu Pemberitahuan -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8 sm:p-10">
                    <div class="text-left mb-8">
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900">Reset Password</h2>
                        <p class="mt-2 text-slate-600">
                            Untuk mereset password Anda, silakan hubungi pihak pondok pesantren.
                        </p>
                    </div>

                    <div class="relative rounded-lg border-l-4 bg-blue-50 p-4 border-blue-400">
                        <p class="text-sm text-blue-800">
                            Harap hubungi administrasi pondok pesantren melalui:
                        </p>
                        <ul class="mt-2 list-disc list-inside text-sm text-blue-700 space-y-1">
                            <li><strong>Email</strong>: admin@pondokpesantren.com</li>
                            <li><strong>Telepon</strong>: +62 123-456-7890</li>
                            <li><strong>Jam Kerja</strong>: Senin - Jumat, 08:00 - 16:00 WIB</li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('login') }}" class="font-medium text-sm text-red-600 hover:text-red-800">
                            Kembali ke login
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
</x-guest-layout>
