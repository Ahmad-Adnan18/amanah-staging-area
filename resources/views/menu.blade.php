<x-app-layout>
    <div class="bg-white min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Menu Lengkap</h1>
                    <p class="mt-1 text-slate-600">Pilih menu untuk melanjutkan aktivitas Anda.</p>
                </div>

                <!--
                ======================================================================
                MENU LENGKAP DARI SIDEBAR
                ======================================================================
                -->
                @php
                    // Array ini berisi semua menu dari sidebar Anda, lengkap dengan role-based access
                    $menuGroups = [
                        'Akademik' => [
                            ['route' => 'admin.santri-management.index', 'roles' => ['admin','pengajaran','pengasuhan','kesehatan','ustadz_umum'], 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.084-1.28-.24-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.084-1.28.24-1.857m10.416-5.408a3 3 0 11-5.832 0 3 3 0 015.832 0z', 'label' => 'Data Santri'],
                            ['route' => 'pengajaran.kelas.index', 'roles' => ['admin','pengajaran','pengasuhan','kesehatan','ustadz_umum'], 'icon' => 'M10 20l4-16m4 4l-4 16M4 12h16', 'label' => 'Data Kelas'],
                            ['route' => 'admin.teachers.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 21a6 6 0 004.777-9.417M15 14.074c-3.146 0-5.777-2.52-5.777-5.646 0-3.127 2.63-5.647 5.777-5.647 3.146 0 5.777 2.52 5.777 5.647 0 3.127-2.63 5.647-5.777-5.647z', 'label' => 'Data Guru'],
                            ['route' => 'pengajaran.mata-pelajaran.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2', 'label' => 'Mata Pelajaran'],
                            ['route' => 'admin.rooms.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5', 'label' => 'Manajemen Ruangan'],
                            ['route' => 'akademik.placement.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.5-2.964A3 3 0 0112 12c1.657 0 3 1.343 3 3s-1.343 3-3 3-3-1.343-3-3z', 'label' => 'Penempatan Kelas'],
                            ['route' => 'akademik.nilai.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'label' => 'Input Nilai'],
                        ],
                        'Penjadwalan' => [
                            ['route' => 'admin.rules.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Aturan Jadwal'],
                            ['route' => 'admin.teacher-availability.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Ketersediaan Guru'],
                            ['route' => 'admin.generator.show', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2h8a2 2 0 002-2v-1a2 2 0 012-2h1.945M7.88 15.12a3 3 0 10-3.76 0M16.12 15.12a3 3 0 11-3.76 0M12 18.5a3 3 0 100-6 3 3 0 000 6z', 'label' => 'Generate Jadwal'],
                            ['route' => 'admin.schedule.view.grid', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'label' => 'Lihat Jadwal (Admin)'],
                            ['route' => 'admin.schedule.swap.show', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M7 16V4m0 12L3 8m4 8l4-8m1 12V4m0 12l4-8m-4 8l-4-8', 'label' => 'Tukar Jadwal'],
                        ],
                        'Administrasi' => [
                            ['route' => 'admin.users.index', 'roles' => ['admin'], 'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.663M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z', 'label' => 'Manajemen User'],
                            ['route' => 'pengajaran.jabatan.index', 'roles' => ['admin'], 'icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100 4 2 2 0 000-4z', 'label' => 'Manajemen Jabatan'],
                        ]
                    ];
                @endphp
                
                @foreach($menuGroups as $groupName => $items)
                    <div class="space-y-4">
                        <h2 class="text-2xl font-bold text-slate-800">{{ $groupName }}</h2>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-4">
                            @foreach ($items as $item)
                                @if (is_null($item['roles']) || in_array(Auth::user()->role, $item['roles']))
                                    <a href="{{ route($item['route']) }}" class="flex flex-col items-center justify-center text-center p-4 bg-slate-50 rounded-2xl border border-slate-200 hover:bg-red-50 hover:border-red-200 transition-all duration-300">
                                        <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg>
                                        </div>
                                        <p class="mt-3 text-sm font-semibold text-slate-800">{{ $item['label'] }}</p>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

