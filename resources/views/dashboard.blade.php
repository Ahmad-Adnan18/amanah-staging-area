<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
            {{-- Salam & Header --}}
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                    Selamat Datang Kembali, {{ Auth::user()->name }}!
                </h1>
                <p class="mt-2 text-slate-600 text-sm sm:text-base">Berikut adalah ringkasan aktivitas hari ini</p>
            </div>

            {{-- Slider --}}
            <div class="mb-6">
                <x-slider />
            </div>

            {{-- Layout satu kolom untuk semua perangkat --}}
            <div class="space-y-4 sm:space-y-6">
                @if ($isTeacher)
                {{-- Kartu Jadwal - MARGIN NEGATIF UNTUK MENYATUKAN --}}
                @if (!$isHoliday)
                <div class="rounded-2xl shadow-lg text-white p-3 sm:p-4 relative -mt-2" style="
                            background: 
                                linear-gradient(135deg, #dc2626, #f87171),
                                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1), transparent 70%);
                            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                            backdrop-filter: blur(4px);
                        ">
                    {{-- TOMBOL JADWAL LENGKAP --}}
                    <a href="{{ route('jadwal.saya') }}" class="absolute top-3 right-3 bg-white/20 hover:bg-white/30 text-white p-1.5 rounded-lg transition-all duration-300 transform hover:scale-110 group" title="Lihat Jadwal Lengkap">
                        <svg class="w-6 h-6 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </a>

                    <div class="flex items-center justify-between pr-10">
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold">Jadwal Mengajar Hari Ini</h2>
                            <p class="text-xs opacity-90 mt-1">{{ $todayDateString }}</p>
                        </div>
                        {{-- <div class="absolute top-3 right-12 bg-white/20 p-1.5 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>  --}}
                    </div>

                    <div class="mt-1 text-xs opacity-80">
                        Klik ikon di kanan atas untuk melihat jadwal lengkap
                    </div>
                </div>

                <div class="space-y-3 -mt-2">
                    @php
                    $jamMap = [
                    1 => ['start' => '07:00', 'end' => '07:45', 'label' => '07:00 - 07:45'],
                    2 => ['start' => '07:45', 'end' => '08:30', 'label' => '07:45 - 08:30'],
                    3 => ['start' => '09:00', 'end' => '09:45', 'label' => '09:00 - 09:45'],
                    4 => ['start' => '09:45', 'end' => '10:30', 'label' => '09:45 - 10:30'],
                    5 => ['start' => '11:00', 'end' => '11:45', 'label' => '11:00 - 11:45'],
                    6 => ['start' => '11:45', 'end' => '12:30', 'label' => '11:45 - 12:30'],
                    7 => ['start' => '14:15', 'end' => '15:00', 'label' => '14:15 - 15:00'],
                    ];
                    @endphp
                    @foreach($scheduleSlots as $slot => $schedule)
                    @php
                    $now = \Carbon\Carbon::now('Asia/Jakarta');
                    $start = \Carbon\Carbon::today('Asia/Jakarta')->setTimeFromTimeString($jamMap[$slot]['start']);
                    $end = \Carbon\Carbon::today('Asia/Jakarta')->setTimeFromTimeString($jamMap[$slot]['end'])->addMinutes(1);
                    $isTimeActive = $now->between($start, $end);
                    $isScheduled = (bool) $schedule;
                    $isCurrent = $isTimeActive;
                    @endphp
                    <div class="rounded-2xl flex items-center transition-all duration-300 {{ $isTimeActive ? 'bg-white scale-105 shadow-xl border-red-400' : 'bg-white shadow-lg border-transparent' }} border p-3 sm:p-4 relative">
                        <div class="w-1/3 sm:w-1/4 pr-3 sm:pr-4 text-center">
                            <p class="font-bold {{ $isCurrent ? 'text-red-600' : 'text-slate-800' }} text-base sm:text-lg">{{ $jamMap[$slot]['label'] }}</p>
                            <p class="{{ $isCurrent ? 'text-red-500' : 'text-slate-500' }} text-xs sm:text-sm">Jam Ke-{{ $slot }}</p>
                        </div>
                        <div class="w-2/3 sm:w-3/4 pl-3 sm:pl-4 border-l border-slate-200">
                            @if($schedule)
                            <p class="font-bold {{ $isCurrent ? 'text-red-700' : 'text-gray-900' }} text-base sm:text-lg">{{ $schedule->subject->nama_pelajaran }}</p>
                            <div class="text-xs sm:text-sm text-slate-600 flex items-center gap-x-3 mt-1">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                                    </svg>
                                    {{ $schedule->kelas->nama_kelas }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    {{ $schedule->room->name }}
                                </span>
                            </div>
                            @else
                            <div class="flex items-center h-full">
                                <p class="text-sm sm:text-base text-slate-400 font-medium italic">Jam Kosong</p>
                            </div>
                            @endif
                        </div>
                        @if($isCurrent)
                        <div class="absolute top-2 right-2 flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800 animate-pulse">
                            <svg class="-ml-0.5 mr-1 h-2 w-2 text-red-500" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Sekarang
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="rounded-2xl shadow-lg text-white p-4 sm:p-6" style="
                            background: 
                                linear-gradient(135deg, #10b981, #34d399),
                                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1), transparent 70%);
                            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                            backdrop-filter: blur(4px);
                        ">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold">Hari Libur</h2>
                            <p class="text-sm opacity-90 mt-1">{{ $todayDateString }} - Hari Jumat Libur</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
                @endif

                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-800 mb-4 @if($isTeacher) mt-8 @endif">Menu Akses Cepat</h2>
                    @php
                    $menuItems = [
                    ['route' => 'admin.santri-management.index', 'roles' => null, 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Data Santri', 'color' => 'blue'],
                    ['route' => 'pengajaran.kelas.index', 'roles' => null, 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222', 'label' => 'Data Kelas', 'color' => 'indigo'],
                    ['route' => 'perizinan.index', 'roles' => null, 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Perizinan', 'color' => 'green'],
                    ['route' => 'pelanggaran.index', 'roles' => null, 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'label' => 'Pelanggaran', 'color' => 'orange'],
                    ['route' => 'admin.teachers.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Data Guru', 'color' => 'red'],
                    ['route' => 'santri.portofolio.list', 'roles' => ['admin','pengajaran','pengasuhan','kesehatan','ustadz_umum'], 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Portofolio Santri', 'color' => 'purple'],
                    ['route' => 'pengajaran.rekapan-harian.index', 'roles' => ['admin','pengajaran'], 'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z', 'label' => 'Rekapan Harian', 'color' => 'blue'],
                    ['route' => 'pengajaran.rekapan-harian.laporan', 'roles' => null, 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Laporan Absensi', 'color' => 'green'],
                    ['route' => 'ubudiyah.index', 'roles' => ['admin','ubudiyah'], 'icon' => 'M12 2l9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9l9-7z M9 22V12h6v10 M12 2v7', 'label' => 'Setoran Hafalan', 'color' => 'indigo'],
                    ];

                    $glassItems = collect($menuItems)
                    ->filter(function ($item) {
                    return is_null($item['roles']) || in_array(Auth::user()->role, $item['roles']);
                    })
                    ->map(function ($item) {
                    return [
                    'label' => $item['label'],
                    'color' => $item['color'],
                    'iconPath' => $item['icon'],
                    'url' => route($item['route']),
                    ];
                    })
                    ->values();
                    @endphp

                    <div id="dashboard-glass-icons" data-glass-items='@json($glassItems)' data-extra-class="grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-16" class="w-full"></div>
                </div>

                {{-- Statistik Pondok - Ditampilkan untuk semua perangkat --}}
                <div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-800 mb-4">Statistik Pondok</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="bg-white p-4 rounded-2xl shadow-lg border border-slate-200 flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Total Santri</p>
                                    <p class="text-2xl sm:text-3xl font-bold text-slate-800 mt-1">{{ $totalSantri ?? 0 }}</p>
                                    <p class="text-xs text-slate-400">{{ $totalSantriPutra ?? 0 }} Putra, {{ $totalSantriPutri ?? 0 }} Putri</p>
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded-2xl shadow-lg border border-slate-200 flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Izin Aktif Saat Ini</p>
                                    <p class="text-2xl sm:text-3xl font-bold text-slate-800 mt-1">{{ $totalIzinAktif ?? 0 }}</p>
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded-2xl shadow-lg border border-slate-200 flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0zM15 17h.01M9 17h.01"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Santri Terlambat Kembali</p>
                                    <p class="text-2xl sm:text-3xl font-bold text-slate-800 mt-1">{{ $jumlahTerlambat ?? 0 }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Progress Bars untuk Komposisi Santri - Ditampilkan hanya di desktop --}}
                        <div class="lg:block mt-4">
                            <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-lg border border-slate-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-sm font-medium text-slate-500">Komposisi Santri</h3>
                                        <p class="text-xs text-slate-400 mt-1">Distribusi santri putra dan putri</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-slate-800">{{ $totalSantri ?? 0 }}</p>
                                        <p class="text-xs text-slate-500">Total Santri</p>
                                    </div>
                                </div>

                                {{-- Simple Progress Bars sebagai alternatif --}}
                                <div class="space-y-3">
                                    @php
                                    $putraPercentage = $totalSantri > 0 ? ($totalSantriPutra / $totalSantri) * 100 : 0;
                                    $putriPercentage = $totalSantri > 0 ? ($totalSantriPutri / $totalSantri) * 100 : 0;
                                    @endphp

                                    <div>
                                        <div class="flex justify-between text-xs text-slate-600 mb-1">
                                            <span>Santri Putra</span>
                                            <span>{{ $totalSantriPutra ?? 0 }} Orang ({{ number_format($putraPercentage, 1) }}%)</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $putraPercentage }}%"></div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex justify-between text-xs text-slate-600 mb-1">
                                            <span>Santri Putri</span>
                                            <span>{{ $totalSantriPutri ?? 0 }} Orang ({{ number_format($putriPercentage, 1) }}%)</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-2">
                                            <div class="bg-red-500 h-2 rounded-full" style="width: {{ $putriPercentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PWA Install Button --}}
        <button id="install-pwa-button" class="hidden fixed bottom-4 right-4 z-50 px-4 py-2 text-white font-medium rounded-lg shadow-lg transition-all duration-300 bg-gradient-to-r from-red-500 to-red-300 hover:from-red-600 hover:to-red-400 sm:bottom-6 md:bottom-8">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
            </svg>
            Install Aplikasi
        </button>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scheduleCards = document.querySelectorAll('.schedule-slot');
            scheduleCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // PWA
        let deferredPrompt;
        const installBtn = document.getElementById('install-pwa-button');
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            installBtn ? .classList.remove('hidden');
        });

        installBtn ? .addEventListener('click', async () => {
            if (!deferredPrompt) return;
            const {
                outcome
            } = await deferredPrompt.prompt();
            deferredPrompt = null;
            installBtn.classList.add('hidden');
            showToast(outcome === 'accepted' ? 'Aplikasi berhasil diinstall!' : 'Install dibatalkan', outcome === 'accepted' ? 'success' : 'info');
        });

        function showToast(message, type = 'info') {
            const bg = {
                success: 'linear-gradient(135deg, #10b981, #34d399)'
                , warning: 'linear-gradient(135deg, #f59e0b, #fbbf24)'
                , info: 'linear-gradient(135deg, #3b82f6, #60a5fa)'
            } [type] || 'linear-gradient(135deg, #6b7280, #9ca3af)';

            Toastify({
                text: message
                , duration: 3000
                , gravity: "top"
                , position: "right"
                , backgroundColor: bg
                , stopOnFocus: true
                , className: "toastify-notification"
                , style: {
                    borderRadius: "8px"
                    , boxShadow: "0 4px 6px rgba(0, 0, 0, 0.1)"
                    , fontSize: "14px"
                    , color: "#fff"
                    , padding: "12px 20px"
                }
            }).showToast();
        }

    </script>

    <style>
        /* Schedule Card Enhancements */
        .schedule-slot {
            position: relative;
            overflow: hidden;
        }

        .schedule-slot::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .schedule-slot:hover::before {
            opacity: 1;
        }

        @media (max-width: 640px) {
            #install-pwa-button {
                bottom: 5rem;
                right: 1rem;
                font-size: 14px;
                padding: 0.75rem 1.5rem;
            }
        }

        /* Responsive adjustments for schedule */
        @media (max-width: 768px) {
            .schedule-slot {
                flex-direction: column;
                gap: 1rem;
            }

            .schedule-slot>div:first-child {
                width: 100% !important;
                order: 2;
            }

            .schedule-slot>div:last-child {
                width: 100% !important;
                order: 1;
                border-left: none !important;
                border-top: 1px solid rgba(0, 0, 0, 0.1);
                padding-top: 1rem;
            }
        }

    </style>
    @endpush
</x-app-layout>
