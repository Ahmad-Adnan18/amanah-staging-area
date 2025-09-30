<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">

            {{-- Salam & Header --}}
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Selamat Datang Kembali, {{ Auth::user()->name }}!</h1>
                
            </div>

            {{-- Layout 2 Kolom --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

                {{-- Kolom Utama (Lebih Lebar) --}}
                <div class="lg:col-span-2 space-y-6 sm:space-y-8">
                    @if ($isTeacher)
                        {{-- [MODIFIKASI] Kartu Jadwal dibuat lebih modern dan berwarna --}}
                        <div class="bg-red-700 rounded-2xl shadow-lg text-white p-4 sm:p-6">
                            <h2 class="text-xl font-bold">Jadwal Mengajar Hari Ini,</h2>
                            <p class="text-sm opacity-80 mt-1"> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}.</p>
                        </div>

                        <div class="space-y-3 -mt-2">
                            @php
                                $jamMap = [
                                    1 => ['start' => '07:00', 'end' => '07:45', 'label' => '07:00 - 07:45'], 2 => ['start' => '07:45', 'end' => '08:30', 'label' => '07:45 - 08:30'], 3 => ['start' => '09:00', 'end' => '09:45', 'label' => '09:00 - 09:45'],
                                    4 => ['start' => '09:45', 'end' => '10:30', 'label' => '09:45 - 10:30'], 5 => ['start' => '11:00', 'end' => '11:45', 'label' => '11:00 - 11:45'], 6 => ['start' => '11:45', 'end' => '12:30', 'label' => '11:45 - 12:30'],
                                    7 => ['start' => '14:15', 'end' => '15:00', 'label' => '14:15 - 15:00'],
                                ];
                            @endphp
                            @foreach($scheduleSlots as $slot => $schedule)
                                @php
                                    $now = now();
                                    $start = \Carbon\Carbon::parse($jamMap[$slot]['start']);
                                    $end = \Carbon\Carbon::parse($jamMap[$slot]['end']);
                                    $isCurrent = $now->between($start, $end);
                                @endphp
                                {{-- Kartu Jadwal per Slot --}}
                                <div class="rounded-2xl flex items-center transition-all duration-300 {{ $isCurrent ? 'bg-white scale-105 shadow-xl border-red-400' : 'bg-white shadow-lg border-transparent' }} border p-3 sm:p-4">
                                    {{-- Sisi Kiri: Waktu --}}
                                    <div class="w-1/3 sm:w-1/4 pr-3 sm:pr-4 text-center">
                                        <p class="font-bold {{ $isCurrent ? 'text-red-600' : 'text-slate-800' }} text-base sm:text-lg">{{ $jamMap[$slot]['label'] }}</p>
                                        <p class="{{ $isCurrent ? 'text-red-500' : 'text-slate-500' }} text-xs sm:text-sm">Jam Ke-{{ $slot }}</p>
                                    </div>
                                    {{-- Sisi Kanan: Detail Pelajaran --}}
                                    <div class="w-2/3 sm:w-3/4 pl-3 sm:pl-4 border-l border-slate-200">
                                        @if($schedule)
                                            <p class="font-bold {{ $isCurrent ? 'text-red-700' : 'text-gray-900' }} text-base sm:text-lg">{{ $schedule->subject->nama_pelajaran }}</p>
                                            <div class="text-xs sm:text-sm text-slate-600 flex items-center gap-x-3 mt-1">
                                                <span class="inline-flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.084-1.28-.24-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.084-1.28.24-1.857m10.416-5.408a3 3 0 11-5.832 0 3 3 0 015.832 0z"></path></svg>{{ $schedule->kelas->nama_kelas }}</span>
                                                <span class="inline-flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>{{ $schedule->room->name }}</span>
                                            </div>
                                        @else
                                            <div class="flex items-center h-full"><p class="text-sm sm:text-base text-slate-400 font-medium italic">Jam Kosong</p></div>
                                        @endif
                                    </div>
                                    @if($isCurrent)
                                        <div class="absolute top-2 right-2 flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800 animate-pulse">
                                            <svg class="-ml-0.5 mr-1 h-2 w-2 text-red-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                            Live
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Menu Akses Cepat --}}
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-800 mb-4 @if($isTeacher) mt-8 @endif">Menu Akses Cepat</h2>
                        <div class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @php
                                // [MODIFIKASI] Menambahkan 'color' untuk setiap item menu
                                $menuItems = [
                                    ['route' => 'admin.santri-management.index', 'roles' => null, 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.084-1.28-.24-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.084-1.28.24-1.857m10.416-5.408a3 3 0 11-5.832 0 3 3 0 015.832 0z', 'label' => 'Data Santri', 'color' => 'blue'],
                                    ['route' => 'pengajaran.kelas.index', 'roles' => null, 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Data Kelas', 'color' => 'indigo'],
                                    ['route' => 'perizinan.index', 'roles' => null, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'label' => 'Perizinan', 'color' => 'green'],
                                    ['route' => 'pelanggaran.index', 'roles' => null, 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Pelanggaran', 'color' => 'amber'],
                                    ['route' => 'admin.teachers.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 21a6 6 0 004.777-9.417M15 14.074c-3.146 0-5.777-2.52-5.777-5.646 0-3.127 2.63-5.647 5.777-5.647 3.146 0 5.777 2.52 5.777 5.647 0 3.127-2.63 5.647-5.777-5.647z', 'label' => 'Data Guru', 'color' => 'rose'],
                                ];
                                // [MODIFIKASI] Mendefinisikan kelas warna Tailwind untuk setiap tema
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
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"></path></svg>
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
                            <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-lg border border-slate-200 flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.084-1.28-.24-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.084-1.28.24-1.857m10.416-5.408a3 3 0 11-5.832 0 3 3 0 015.832 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Total Santri</p>
                                    <p class="text-2xl sm:text-3xl font-bold text-slate-800 mt-1">{{ $totalSantri }}</p>
                                    <p class="text-xs text-slate-400">{{ $totalSantriPutra }} Putra, {{ $totalSantriPutri }} Putri</p>
                                </div>
                            </div>
                             <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-lg border border-slate-200 flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Izin Aktif Saat Ini</p>
                                    <p class="text-2xl sm:text-3xl font-bold text-slate-800 mt-1">{{ $totalIzinAktif }}</p>
                                </div>
                            </div>
                             <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-lg border border-slate-200 flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                                     <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Santri Terlambat Kembali</p>
                                    <p class="text-2xl sm:text-3xl font-bold text-slate-800 mt-1">{{ $jumlahTerlambat }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>