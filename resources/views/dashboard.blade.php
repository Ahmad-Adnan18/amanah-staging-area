<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Salam & Header -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Selamat Datang, {{ Auth::user()->name }}!</h1>
                    <p class="mt-1 text-slate-600">Pilih menu di bawah untuk memulai aktivitas Anda.</p>
                </div>

                @if ($isTeacher)
                    {{-- TAMPILAN UNTUK GURU --}}
                    <!-- Kartu Jadwal Hari Ini -->
                    <div class="bg-red-700 text-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold">Jadwal Hari Ini</h2>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-2 opacity-90">
                            <p class="font-medium flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span>{{ $teacher->name }}</span>
                            </p>
                            <p class="font-medium flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>{{ $todayDateString }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Daftar Slot Jadwal -->
                    <div class="space-y-4">
                        @php
                            $jamMap = [
                                1 => '07:00 - 07:45', 2 => '07:45 - 08:30', 3 => '09:00 - 09:45',
                                4 => '09:45 - 10:30', 5 => '11:00 - 11:45', 6 => '11:45 - 12:30',
                                7 => '14:15 - 15:00',
                            ];
                        @endphp
                        @foreach($scheduleSlots as $slot => $schedule)
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-5 flex items-center">
                            <div class="w-1/3 md:w-1/4">
                                <p class="text-slate-500 text-sm">Jam Ke-{{ $slot }}</p>
                                <p class="font-bold text-slate-800 text-lg">{{ $jamMap[$slot] }}</p>
                            </div>
                            <div class="w-2/3 md:w-3/4 pl-5 border-l-2 border-slate-200">
                                @if($schedule)
                                    <p class="font-bold text-red-700 text-lg">{{ $schedule->subject->nama_pelajaran }}</p>
                                    <p class="text-slate-600">Kelas: {{ $schedule->kelas->nama_kelas }} | Ruang: {{ $schedule->room->name }}</p>
                                @else
                                    <p class="text-lg text-slate-400 font-medium">Kosong</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                <!-- Kartu Statistik -->
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 mt-12">Statistik Pondok Hari Ini</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-800">Total Santri</h3>
                            <p class="text-4xl font-bold text-red-700 mt-2">{{ $totalSantri }}</p>
                            <p class="text-sm text-slate-500 mt-1">{{ $totalSantriPutra }} Putra, {{ $totalSantriPutri }} Putri</p>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-800">Izin Aktif Saat Ini</h3>
                            <p class="text-4xl font-bold text-red-700 mt-2">{{ $totalIzinAktif }}</p>
                            <p class="text-sm text-slate-500 mt-1">Santri sedang tidak berada di pondok</p>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-800">Terlambat Kembali</h3>
                            <p class="text-4xl font-bold text-red-700 mt-2">{{ $jumlahTerlambat }}</p>
                            <p class="text-sm text-slate-500 mt-1">Santri belum kembali dari perizinan</p>
                        </div>
                    </div>
                </div>

                <!-- [PERUBAHAN] Menu Akses Cepat dengan Ikon Baru -->
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 mt-12">Menu Akses Cepat</h2>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
                        @php
                            $menuItems = [
                                ['route' => 'admin.santri-management.index', 'roles' => null, 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.084-1.28-.24-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.084-1.28.24-1.857m10.416-5.408a3 3 0 11-5.832 0 3 3 0 015.832 0z', 'label' => 'Data Santri'],
                                ['route' => 'pengajaran.kelas.index', 'roles' => null, 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Data Kelas'],
                                ['route' => 'perizinan.index', 'roles' => null, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'label' => 'Perizinan'],
                                ['route' => 'pelanggaran.index', 'roles' => null, 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Pelanggaran'],
                                ['route' => 'admin.teachers.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 21a6 6 0 004.777-9.417M15 14.074c-3.146 0-5.777-2.52-5.777-5.646 0-3.127 2.63-5.647 5.777-5.647 3.146 0 5.777 2.52 5.777 5.647 0 3.127-2.63 5.647-5.777-5.647z', 'label' => 'Data Guru'],
                            ];
                        @endphp

                        @foreach ($menuItems as $item)
                            @if (is_null($item['roles']) || in_array(Auth::user()->role, $item['roles']))
                                <a href="{{ route($item['route']) }}" class="flex flex-col items-center justify-center text-center p-4 bg-white rounded-2xl shadow-lg border border-slate-200 hover:bg-red-50 hover:border-red-200 transition-all duration-300">
                                    <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg>
                                    </div>
                                    <p class="mt-3 text-sm font-semibold text-slate-800">{{ $item['label'] }}</p>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

