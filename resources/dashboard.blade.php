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

            {{-- Layout 2 Kolom --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                {{-- Kolom Utama (Lebih Lebar) --}}
                <div class="lg:col-span-2 space-y-4 sm:space-y-6">
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
                        <div class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @php
                            $menuItems = [
                            ['route' => 'admin.santri-management.index', 'roles' => null, 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Data Santri', 'color' => 'blue'],
                            ['route' => 'pengajaran.kelas.index', 'roles' => null, 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222', 'label' => 'Data Kelas', 'color' => 'indigo'],
                            ['route' => 'perizinan.index', 'roles' => null, 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Perizinan', 'color' => 'green'],
                            ['route' => 'pelanggaran.index', 'roles' => null, 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'label' => 'Pelanggaran', 'color' => 'amber'],
                            ['route' => 'admin.teachers.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Data Guru', 'color' => 'rose'],
                            ['route' => 'pengajaran.absensi.index', 'roles' => ['admin','pengajaran','pengasuhan','kesehatan','ustadz_umum'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'label' => 'Absensi', 'color' => 'blue'],
                            ];

                            $colorClasses = [
                            'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'hover:bg' => 'hover:bg-blue-50', 'hover:border' => 'hover:border-blue-300', 'group-hover:bg' => 'group-hover:bg-blue-200'],
                            'indigo' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-600', 'hover:bg' => 'hover:bg-indigo-50', 'hover:border' => 'hover:border-indigo-300', 'group-hover:bg' => 'group-hover:bg-indigo-200'],
                            'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'hover:bg' => 'hover:bg-green-50', 'hover:border' => 'hover:border-green-300', 'group-hover:bg' => 'group-hover:bg-green-200'],
                            'amber' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'hover:bg' => 'hover:bg-amber-50', 'hover:border' => 'hover:border-amber-300', 'group-hover:bg' => 'group-hover:bg-amber-200'],
                            'rose' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-600', 'hover:bg' => 'hover:bg-rose-50', 'hover:border' => 'hover:border-rose-300', 'group-hover:bg' => 'group-hover:bg-rose-200'],
                            ];
                            @endphp

                            @foreach ($menuItems as $item)
                            @if (is_null($item['roles']) || in_array(Auth::user()->role, $item['roles']))
                            @php $colors = $colorClasses[$item['color']]; @endphp
                            <a href="{{ route($item['route']) }}" class="group flex flex-col items-center justify-center text-center p-4 bg-white rounded-2xl shadow-lg border border-slate-200 {{ $colors['hover:bg'] }} {{ $colors['hover:border'] }} transition-all duration-300 transform hover:-translate-y-1">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110 {{ $colors['bg'] }} {{ $colors['text'] }} {{ $colors['group-hover:bg'] }}">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"></path>
                                    </svg>
                                </div>
                                <p class="mt-3 text-xs sm:text-sm font-semibold text-slate-800">{{ $item['label'] }}</p>
                            </a>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Kolom Samping (Sidebar) --}}
                <div class="lg:col-span-1 space-y-6 sm:space-y-8">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-800 mb-4">Statistik Pondok</h2>
                        <div class="space-y-4">
                            {{-- Ganti bagian chart dengan ini --}}
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

                            <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-lg border border-slate-200 flex items-start gap-4">
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

                            <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-lg border border-slate-200 flex items-start gap-4">
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

                            <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-lg border border-slate-200 flex items-start gap-4">
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
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Install PWA --}}
        <button id="install-pwa-button" class="hidden fixed bottom-4 right-4 z-50 px-4 py-2 text-white font-medium rounded-lg shadow-lg transition-all duration-300 bg-gradient-to-r from-red-500 to-red-300 hover:from-red-600 hover:to-red-400 sm:bottom-6 md:bottom-8" style="display: none;">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
            </svg>
            Install Aplikasi
        </button>

    </div>

    @section('scripts')
    {{-- CDN Toastify.js --}}
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Inisialisasi Chart.js
        const ctx = document.getElementById('santriChart').getContext('2d');

        // Data untuk bar chart dengan fallback
        const totalSantri = {
            {
                $totalSantri ? ? 0
            }
        };
        const santriPutra = {
            {
                $totalSantriPutra ? ? 0
            }
        };
        const santriPutri = {
            {
                $totalSantriPutri ? ? 0
            }
        };

        // Debugging: Log data statistik
        // console.log('Total Santri:', totalSantri);
        // console.log('Santri Putra:', santriPutra);
        // console.log('Santri Putri:', santriPutri);

        // Hitung persentase
        const persentasePutra = totalSantri > 0 ? ((santriPutra / totalSantri) * 100).toFixed(1) : 0;
        const persentasePutri = totalSantri > 0 ? ((santriPutri / totalSantri) * 100).toFixed(1) : 0;

        new Chart(ctx, {
            type: 'bar'
            , data: {
                labels: ['Santri Putra', 'Santri Putri']
                , datasets: [{
                    label: 'Jumlah Santri'
                    , data: [santriPutra, santriPutri]
                    , backgroundColor: [
                        'rgba(33, 116, 251, 0.8)'
                        , 'rgba(234, 23, 41, 0.8)'
                    ]
                    , borderColor: [
                        'rgba(30, 64, 175, 1)'
                        , 'rgba(193, 18, 33, 0.8)'
                    ]
                    , borderWidth: 2
                    , borderRadius: 8
                    , borderSkipped: false
                    , barThickness: 60
                    , maxBarThickness: 80
                }]
            }
            , options: {
                responsive: true
                , maintainAspectRatio: false
                , plugins: {
                    legend: {
                        display: false
                    }
                    , tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)'
                        , titleColor: 'white'
                        , bodyColor: 'white'
                        , borderColor: 'rgba(255, 255, 255, 0.2)'
                        , borderWidth: 1
                        , cornerRadius: 8
                        , displayColors: true
                        , callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const percentage = totalSantri > 0 ? ((value / totalSantri) * 100).toFixed(1) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                            , afterLabel: function(context) {
                                return `Total: ${totalSantri} santri`;
                            }
                        }
                    }
                }
                , scales: {
                    y: {
                        beginAtZero: true
                        , max: Math.max(santriPutra, santriPutri, 1) * 1.2
                        , grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                            , drawBorder: false
                            , lineWidth: 1
                        }
                        , ticks: {
                            stepSize: Math.ceil(Math.max(santriPutra, santriPutri, 1) / 5)
                            , callback: function(value) {
                                return value.toLocaleString();
                            }
                            , color: 'rgba(0, 0, 0, 0.6)'
                            , font: {
                                size: 11
                            }
                        }
                    }
                    , x: {
                        grid: {
                            display: false
                        }
                        , ticks: {
                            color: 'rgba(0, 0, 0, 0.6)'
                            , font: {
                                size: 12
                                , weight: '600'
                            }
                            , maxRotation: 0
                            , minRotation: 0
                        }
                    }
                }
                , animation: {
                    duration: 1500
                    , easing: 'easeOutQuart'
                }
                , elements: {
                    bar: {
                        borderRadius: 8
                        , borderSkipped: false
                    }
                }
            }
            , plugins: [{
                id: 'customDatalabels'
                , afterDatasetsDraw: function(chart) {
                    const ctx = chart.ctx;
                    const meta = chart.getDatasetMeta(0);

                    meta.data.forEach((bar, index) => {
                        const data = chart.data.datasets[0].data[index];
                        const percentage = totalSantri > 0 ? ((data / totalSantri) * 100).toFixed(0) : 0;

                        if (data > 0) {
                            ctx.fillStyle = 'rgba(0, 0, 0, 0.8)';
                            ctx.font = 'bold 12px Arial';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';

                            const text = `${data}\n(${percentage}%)`;
                            const lines = text.split('\n');

                            const y = bar.y - 10;

                            lines.forEach((line, i) => {
                                ctx.fillText(line, bar.x, y - (i * 16));
                            });
                        }
                    });
                }
            }]
        });

        // Logika PWA Install
        let deferredPrompt = null;
        const installButton = document.getElementById('install-pwa-button');

        // Event listener untuk beforeinstallprompt
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('beforeinstallprompt event fired');

            // Cegah browser prompt default
            e.preventDefault();

            // Simpan event untuk digunakan nanti
            deferredPrompt = e;

            // Tampilkan tombol install HANYA jika ada event
            if (installButton && deferredPrompt) {
                installButton.classList.remove('hidden');
                installButton.style.display = 'block';
                console.log('Install button shown');
            }
        });

        // Event listener untuk tombol install
        if (installButton) {
            installButton.addEventListener('click', async (e) => {
                console.log('Install button clicked');

                if (!deferredPrompt) {
                    console.warn('No deferredPrompt available');
                    return;
                }

                try {
                    const {
                        outcome
                    } = await deferredPrompt.prompt();

                    console.log(`User response to the install prompt: ${outcome}`);
                    console.log('Was the app installed?', outcome === 'accepted');

                    deferredPrompt = null;

                    installButton.classList.add('hidden');
                    installButton.style.display = 'none';

                    if (outcome === 'accepted') {
                        showToast('Aplikasi berhasil diinstall!', 'success');
                    } else {
                        showToast('Install dibatalkan', 'info');
                    }

                } catch (error) {
                    console.error('Error showing install prompt:', error);
                    if (error.name === 'NotAllowedError') {
                        showToast('Install hanya bisa dilakukan saat ini. Coba klik lagi.', 'warning');
                    }
                }
            });

            // Sembunyikan tombol jika user scroll atau leave page
            window.addEventListener('blur', () => {
                if (deferredPrompt) {
                    installButton.classList.add('hidden');
                    installButton.style.display = 'none';
                }
            });
        }

        // Fungsi helper untuk toast notification
        function showToast(message, type = 'info') {
            const bgColor = {
                success: 'linear-gradient(135deg, #10b981, #34d399)'
                , warning: 'linear-gradient(135deg, #f59e0b, #fbbf24)'
                , info: 'linear-gradient(135deg, #3b82f6, #60a5fa)'
            } [type] || 'linear-gradient(135deg, #6b7280, #9ca3af)';

            Toastify({
                text: message
                , duration: 3000
                , gravity: "top"
                , position: "right"
                , backgroundColor: bgColor
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

        // Logika untuk notifikasi (tanpa suara)
        @if($isTeacher && !$isHoliday)
        // Data jadwal dan waktu dari PHP
        const jamMap = @json($jamMap);
        let scheduleSlots = @json($scheduleSlots);

        // Debugging: Log data jadwal
        console.log('Jam Map:', jamMap);
        console.log('Schedule Slots:', scheduleSlots);

        // Pastikan scheduleSlots adalah array
        if (!Array.isArray(scheduleSlots)) {
            scheduleSlots = Array(7).fill(null);
            console.warn('scheduleSlots bukan array, menggunakan array kosong');
        }

        // Fungsi untuk menampilkan notifikasi modern dengan Toastify.js
        function showNotification(title, message) {
            Toastify({
                text: `${title}\n${message}`
                , duration: 5000
                , gravity: "top"
                , position: "right"
                , backgroundColor: "linear-gradient(135deg, #dc2626, #f87171)"
                , stopOnFocus: true
                , className: "toastify-notification"
                , style: {
                    borderRadius: "8px"
                    , boxShadow: "0 4px 6px rgba(0, 0, 0, 0.1)"
                    , fontFamily: "Arial, sans-serif"
                    , fontSize: "14px"
                    , color: "#fff"
                    , padding: "12px 20px"
                    , display: "flex"
                    , alignItems: "center"
                }
                , escapeMarkup: false
            }).showToast();
        }

        // Fungsi untuk memeriksa waktu dan memicu notifikasi
        function checkScheduleNotifications() {
            const now = new Date();
            const jakartaTime = new Date(now.toLocaleString('en-US', {
                timeZone: 'Asia/Jakarta'
            }));
            const currentTime = jakartaTime.getHours() * 60 + jakartaTime.getMinutes();

            Object.keys(jamMap).forEach(slot => {
                const schedule = scheduleSlots[slot];
                if (!schedule || !schedule.subject || !schedule.kelas || !schedule.room) return;

                const startTimeStr = jamMap[slot].start;
                const [startHours, startMinutes] = startTimeStr.split(':').map(Number);
                const startTimeInMinutes = startHours * 60 + startMinutes;

                const tenMinutesBefore = startTimeInMinutes - 10;
                const fiveMinutesBefore = startTimeInMinutes - 5;

                if (Math.abs(currentTime - tenMinutesBefore) <= 1) {
                    showNotification(
                        'Peringatan Jadwal Mengajar'
                        , `10 menit lagi: ${schedule.subject.nama_pelajaran} di ${schedule.kelas.nama_kelas}, Ruang ${schedule.room.name}`
                    );
                } else if (Math.abs(currentTime - fiveMinutesBefore) <= 1) {
                    showNotification(
                        'Peringatan Jadwal Mengajar'
                        , `5 menit lagi: ${schedule.subject.nama_pelajaran} di ${schedule.kelas.nama_kelas}, Ruang ${schedule.room.name}`
                    );
                }
            });
        }

        // Periksa segera saat halaman dimuat
        checkScheduleNotifications();
        @endif

    </script>

    {{-- CSS kustom untuk notifikasi dan tombol PWA --}}
    <style>
        .toastify-notification {
            min-width: 300px;
            max-width: 400px;
            line-height: 1.5;
            background: linear-gradient(135deg, #dc2626, #f87171) !important;
        }

        .toastify-notification::before {
            content: '🔔';
            margin-right: 10px;
            font-size: 18px;
        }

        /* Responsif untuk tombol PWA di mobile */
        @media (max-width: 640px) {
            #install-pwa-button {
                bottom: 5rem;
                right: 1rem;
                font-size: 14px;
                padding: 0.75rem 1.5rem;
                min-width: 140px;
            }
        }

        @media (min-width: 641px) {
            #install-pwa-button {
                bottom: 1.5rem;
                right: 1.5rem;
            }
        }

    </style>
    @endsection
</x-app-layout>
