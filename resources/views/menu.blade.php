<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">
                {{-- Header --}}
                <div class="text-center sm:text-left">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">Menu Lengkap</h1>
                    <p class="text-slate-600">Pilih menu untuk melanjutkan aktivitas Anda.</p>
                </div>

                {{-- Menu Groups --}}
                @php
                $menuGroups = [
                'Akademik' => [
                'color' => 'blue',
                'items' => [
                ['route' => 'admin.santri-management.index', 'roles' => ['admin','pengajaran','pengasuhan','kesehatan','ustadz_umum'], 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Data Santri'],
                ['route' => 'pengajaran.kelas.index', 'roles' => ['admin','pengajaran','pengasuhan','kesehatan','ustadz_umum'], 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222', 'label' => 'Data Kelas'],
                ['route' => 'admin.teachers.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Data Guru'],
                ['route' => 'pengajaran.mata-pelajaran.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.523 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.523 18.246 18 16.5 18s-3.332.477-4.5 1.253', 'label' => 'Mata Pelajaran'],
                ['route' => 'santri.portofolio.list', 'roles' => ['admin','pengajaran','pengasuhan','kesehatan','ustadz_umum'], 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Portofolio Santri'],
                ['route' => 'akademik.nilai.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Input Nilai'],
                ['route' => 'pengajaran.absensi.index', 'roles' => ['admin','pengajaran','pengasuhan','kesehatan','ustadz_umum'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'label' => 'Absensi'],
                ['route' => 'pengajaran.rekapan-harian.index', 'roles' => ['admin','pengajaran'], 'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z', 'label' => 'Rekapan Absensi Harian'],
                ['route' => 'pengajaran.rekapan-harian.laporan', 'roles' => ['admin','pengajaran','pengasuhan','kesehatan','ustadz_umum'], 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Laporan Absensi'],
                ]
                ],
                // ==================================
                // [BARU] GRUP UBUDIYAH
                // ==================================
                'Ubudiyah' => [
                'color' => 'emerald', // Warna baru
                'items' => [
                ['route' => 'ubudiyah.index', 'roles' => ['admin','ubudiyah'], 'icon' => 'M12 2l9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9l9-7z M9 22V12h6v10 M12 2v7', 'label' => 'Setoran Tahfidz'],
                ['route' => 'ubudiyah.mutabaah', 'roles' => ['admin','ubudiyah'], 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Laporan Tahfidz'],
                ['route' => 'admin.master-data.surats.index', 'roles' => ['admin','ubudiyah'], 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.523 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.523 18.246 18 16.5 18s-3.332.477-4.5 1.253', 'label' => 'Manajemen Surat'],
                ]
                ],
                'Penjadwalan' => [
                'color' => 'amber',
                'items' => [
                ['route' => 'admin.rooms.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Manajemen Ruangan'],
                ['route' => 'admin.teacher-availability.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Ketersediaan Guru'],
                ['route' => 'admin.rules.index', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'label' => 'Aturan Jadwal'],
                ['route' => 'admin.generator.show', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'label' => 'Generate Jadwal'],
                ['route' => 'admin.schedule.view.grid', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Lihat Jadwal (Admin)'],
                ['route' => 'admin.schedule.swap.show', 'roles' => ['admin', 'pengajaran'], 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'label' => 'Tukar Jadwal'],
                ]
                ],
                'Administrasi' => [
                'color' => 'violet',
                'items' => [
                ['route' => 'admin.users.index', 'roles' => ['admin'], 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Manajemen User'],
                ['route' => 'pengajaran.jabatan.index', 'roles' => ['admin'], 'icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2', 'label' => 'Manajemen Jabatan'],
                ['route' => 'admin.settings.index', 'roles' => ['admin'], 'icon' => 'M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75', 'label' => 'Manajemen Aplikasi'],
                ]
                ],
                'Konten' => [
                'color' => 'green',
                'items' => [
                ['route' => 'slider.index', 'roles' => ['admin', 'dokumentasi'], 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Manajemen Slider'],
                ]
                ],
                'Akun' => [
                'color' => 'slate',
                'items' => [
                ['route' => 'profile.edit', 'roles' => null, 'icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Profile'],
                ['route' => 'logout', 'roles' => null, 'icon' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1', 'label' => 'Log Out'],
                ]
                ]
                ];
                @endphp

                @foreach($menuGroups as $groupName => $group)
                @php
                $color = $group['color'];
                $items = $group['items'];

                // Check if user can view any item in this group
                $canViewGroup = false;
                foreach ($items as $item) {
                if (is_null($item['roles']) || (Auth::check() && in_array(Auth::user()->role, $item['roles']))) {
                $canViewGroup = true;
                break;
                }
                }
                @endphp

                @if($canViewGroup)
                <section class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    {{-- Group Header --}}
                    <h2 class="text-xl font-semibold text-slate-800 mb-6 flex items-center gap-3">
                        {{-- PERBAIKAN: Gunakan class warna statis --}}
                        @if($color === 'blue')
                        <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-600 rounded-full"></div>
                        @elseif($color === 'emerald') <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-emerald-600 rounded-full"></div>
                        @elseif($color === 'amber')
                        <div class="w-2 h-8 bg-gradient-to-b from-amber-500 to-amber-600 rounded-full"></div>
                        @elseif($color === 'violet')
                        <div class="w-2 h-8 bg-gradient-to-b from-violet-500 to-violet-600 rounded-full"></div>
                        @elseif($color === 'green')
                        <div class="w-2 h-8 bg-gradient-to-b from-green-500 to-green-600 rounded-full"></div>
                        @elseif($color === 'slate')
                        <div class="w-2 h-8 bg-gradient-to-b from-slate-500 to-slate-600 rounded-full"></div>
                        @else
                        <div class="w-2 h-8 bg-gradient-to-b from-gray-500 to-gray-600 rounded-full"></div>
                        @endif
                        {{ $groupName }}
                    </h2>

                    {{-- Menu Items Grid --}}
                    <div class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        @foreach ($items as $item)
                        @if (is_null($item['roles']) || (Auth::check() && in_array(Auth::user()->role, $item['roles'])))
                        @if ($item['route'] === 'logout')
                        {{-- Logout Form --}}
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="menu-item logout-item relative group w-full h-full">
                                {{-- Icon Container --}}
                                <div class="icon-container bg-slate-100 text-slate-600">
                                    <svg class="w-6 h-6 transition-transform duration-300 ease-out" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"></path>
                                    </svg>
                                </div>
                                {{-- Label --}}
                                <p class="menu-label">{{ $item['label'] }}</p>
                                <span class="ripple-effect absolute inset-0 rounded-xl pointer-events-none"></span>
                            </button>
                        </form>
                        @else
                        {{-- Regular Menu Item --}}
                        <a href="{{ route($item['route']) }}" class="menu-item relative group" aria-label="{{ $item['label'] }}">
                            {{-- PERBAIKAN: Gunakan class warna statis untuk icon container --}}
                            @if($color === 'blue')
                            <div class="icon-container bg-blue-100 text-blue-600">
                                @elseif($color === 'emerald') <div class="icon-container bg-emerald-100 text-emerald-600">
                                    @elseif($color === 'amber')
                                    <div class="icon-container bg-amber-100 text-amber-600">
                                        @elseif($color === 'violet')
                                        <div class="icon-container bg-violet-100 text-violet-600">
                                            @elseif($color === 'green')
                                            <div class="icon-container bg-green-100 text-green-600">
                                                @elseif($color === 'slate')
                                                <div class="icon-container bg-slate-100 text-slate-600">
                                                    @else
                                                    <div class="icon-container bg-gray-100 text-gray-600">
                                                        @endif
                                                        <svg class="w-6 h-6 transition-transform duration-300 ease-out" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"></path>
                                                        </svg>
                                                    </div>
                                                    {{-- Label --}}
                                                    <p class="menu-label">{{ $item['label'] }}</p>
                                                    <span class="ripple-effect absolute inset-0 rounded-xl pointer-events-none"></span>
                        </a>
                        @endif
                        @endif
                        @endforeach
                    </div>
                </section>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Hapus JavaScript yang tidak perlu --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.menu-item');

            menuItems.forEach(item => {
                // Hover Effects
                item.addEventListener('mouseenter', function() {
                    const iconContainer = item.querySelector('.icon-container');
                    if (iconContainer) {
                        iconContainer.classList.add('hover-active');
                    }
                    item.classList.add('hover-active');
                });

                item.addEventListener('mouseleave', function() {
                    const iconContainer = item.querySelector('.icon-container');
                    if (iconContainer) {
                        iconContainer.classList.remove('hover-active');
                    }
                    item.classList.remove('hover-active');
                });
            });
        });

    </script>

    {{-- Custom Styles --}}
    <style>
        .menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0.75rem;
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid rgb(226, 232, 240);
            transition: all 0.3s ease-out;
            min-height: 100px;
            position: relative;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            border: none;
            width: 100%;
        }

        .menu-item:hover {
            background-color: rgb(248, 250, 252);
            border-color: rgb(203, 213, 225);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transform: translateY(-0.125rem);
        }

        .menu-item.hover-active .icon-container {
            transform: scale(1.1) rotate(6deg);
        }

        .menu-item.hover-active .icon-container svg {
            transform: rotate(12deg);
        }

        .menu-item.hover-active .menu-label {
            color: rgb(15, 23, 42);
            font-weight: 600;
        }

        .icon-container {
            width: 3rem;
            height: 3rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease-out;
            margin-bottom: 0.75rem;
        }

        .icon-container svg {
            width: 1.5rem;
            height: 1.5rem;
            transition: transform 0.3s ease-out;
        }

        .menu-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: rgb(51, 65, 85);
            transition: all 0.3s ease-out;
            line-clamp: 2;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            padding: 0 0.25rem;
            max-width: 100%;
            text-align: center;
        }

        /* Logout Item Special Styling */
        .logout-item:hover {
            background-color: rgb(254, 242, 242);
            border-color: rgb(254, 202, 202);
        }

        .logout-item:hover .icon-container {
            background-color: rgb(254, 226, 226);
            color: rgb(220, 38, 38);
        }

        /* Focus states for accessibility */
        .menu-item:focus {
            outline: 2px solid rgb(59, 130, 246);
            outline-offset: 2px;
        }

    </style>
</x-app-layout>
