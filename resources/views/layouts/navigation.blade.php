<!-- 
======================================================================
KOMPONEN NAVIGASI HIBRIDA LENGKAP - PROFESIONAL
======================================================================
-->

<!-- [BAGIAN 1] Overlay Latar Belakang untuk Sidebar Mobile -->
<div x-show="sidebarOpen" class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity md:hidden" @click="sidebarOpen = false" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
</div>

<!-- [BAGIAN 2] Sidebar Utama -->
<aside class="fixed inset-y-0 left-0 z-30 flex w-64 transform flex-col bg-white text-gray-900 border-r border-slate-200 transition-all duration-300 ease-in-out -translate-x-full md:translate-x-0 shadow-xl" :class="{ 'translate-x-0': sidebarOpen }" x-cloak>

    <!-- Logo dan Nama Aplikasi -->
    <div class="flex h-20 flex-shrink-0 items-center justify-center border-b border-slate-200 bg-white/95 backdrop-blur-sm">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 group">
            <div class="relative">
                <img src="{{ asset('images/logo-kunka-merah.png') }}" alt="Logo Pondok" class="block h-9 w-auto transition-transform duration-300 group-hover:scale-110">
            </div>
            <span class="text-xl font-bold tracking-tight text-gray-800" style="font-family: 'Inter', sans-serif;">
                {{ config('app.name', 'Amanah') }}
            </span>
        </a>
    </div>

    <!-- Link Navigasi Sidebar -->
    <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6" x-data="{
            isAkademikOpen: {{ request()->routeIs(['pengajaran.*', 'admin.santri-management.*', 'akademik.*', 'admin.rooms.*', 'admin.teachers.*', 'laporan.*']) ? 'true' : 'false' }},
            isUbudiyahOpen: {{ request()->routeIs(['ubudiyah.*', 'admin.master-data.surats*']) ? 'true' : 'false' }},
            isPenjadwalanOpen: {{ request()->routeIs(['admin.rules.*', 'admin.teacher-availability.*', 'admin.generator.*', 'admin.schedule.view.*', 'admin.schedule.swap.*']) ? 'true' : 'false' }},
            isAdministrasiOpen: {{ request()->routeIs(['admin.users.*']) ? 'true' : 'false' }},
            isKontenOpen: {{ request()->routeIs(['slider.*']) ? 'true' : 'false' }}
         }">

        @php
        $baseClasses = 'flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm transition-all duration-200 border-l-4 font-medium';
        $activeClasses = 'bg-red-50 text-red-700 border-red-600 shadow-sm';
        $inactiveClasses = 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-transparent hover:border-slate-300';

        $childBase = 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-all duration-200 ml-2 border-l-2';
        $childActive = 'bg-red-50 text-red-700 font-medium border-red-500';
        $childInactive = 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-transparent';
        @endphp

        <!-- GRUP: MENU UTAMA -->
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}" class="{{ $baseClasses }} {{ request()->routeIs('dashboard') ? $activeClasses : $inactiveClasses }}">
                <div class="flex items-center justify-center w-5 h-5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <span>{{ __('Dashboard') }}</span>
            </a>

            @if (!in_array(Auth::user()->role, ['wali_santri']))
            <a href="{{ route('perizinan.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('perizinan.*') ? $activeClasses : $inactiveClasses }}">
                <div class="flex items-center justify-center w-5 h-5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <span>{{ __('Daftar Izin') }}</span>
            </a>

            <a href="{{ route('pelanggaran.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('pelanggaran.*') ? $activeClasses : $inactiveClasses }}">
                <div class="flex items-center justify-center w-5 h-5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <span>{{ __('Pelanggaran Santri') }}</span>
            </a>

            <a href="{{ route('jadwal.public.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('jadwal.public.index') ? $activeClasses : $inactiveClasses }}">
                <div class="flex items-center justify-center w-5 h-5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span>{{ __('Lihat Jadwal') }}</span>
            </a>
            @endif
        </div>

        <!-- GRUP: AKADEMIK -->
        @if(in_array(Auth::user()->role, ['admin','pengajaran','pengasuhan','kesehatan','ustadz_umum']))
        <div class="pt-4 mt-4 border-t border-slate-200">
            <button @click="isAkademikOpen = !isAkademikOpen" class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-semibold text-slate-800 hover:bg-slate-50 transition-all duration-200 border-l-4 border-transparent">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-5 h-5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                        </svg>
                    </div>
                    <span>Akademik</span>
                </div>
                <svg class="h-4 w-4 transform transition-transform duration-200 text-slate-500" :class="{'rotate-180': isAkademikOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="isAkademikOpen" x-collapse class="mt-2 space-y-1 pl-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2">

                <a href="{{ route('admin.santri-management.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.santri-management.*') ? $childActive : $childInactive }}">
                    Data Santri
                </a>
                <a href="{{ route('pengajaran.kelas.index') }}" class="{{ $childBase }} {{ request()->routeIs('pengajaran.kelas.*') ? $childActive : $childInactive }}">
                    Data Kelas
                </a>
                <a href="{{ route('laporan.index') }}" class="{{ $childBase }} {{ request()->routeIs('laporan.*') ? $childActive : $childInactive }}">
                    Laporan
                </a>
                <a href="{{ route('pengajaran.absensi.index') }}" class="{{ $childBase }} {{ request()->routeIs('pengajaran.absensi.*') ? $childActive : $childInactive }}">
                    Absensi
                </a>

                @if(in_array(Auth::user()->role, ['admin','pengajaran']))
                <a href="{{ route('admin.teachers.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.teachers.*') ? $childActive : $childInactive }}">
                    Data Guru
                </a>
                <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="{{ $childBase }} {{ request()->routeIs('pengajaran.mata-pelajaran.*') ? $childActive : $childInactive }}">
                    Mata Pelajaran
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.rooms.*') ? $childActive : $childInactive }}">
                    Manajemen Ruangan
                </a>
                <a href="{{ route('akademik.placement.index') }}" class="{{ $childBase }} {{ request()->routeIs('akademik.placement.*') ? $childActive : $childInactive }}">
                    Penempatan Kelas
                </a>
                <a href="{{ route('akademik.nilai.index') }}" class="{{ $childBase }} {{ request()->routeIs('akademik.nilai.*') ? $childActive : $childInactive }}">
                    Input Nilai
                </a>
                @endif
            </div>
        </div>
        @endif

        @if(in_array(Auth::user()->role, ['admin','ubudiyah']))
        <div class="pt-4 mt-4 border-t border-slate-200">
            <button @click="isUbudiyahOpen = !isUbudiyahOpen" class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-semibold text-slate-800 hover:bg-slate-50 transition-all duration-200 border-l-4 border-transparent">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-5 h-5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9l9-7z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 22V12h6v10"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v7"></path>
                        </svg>
                    </div>
                    <span>Ubudiyah</span>
                </div>
                <svg class="h-4 w-4 transform transition-transform duration-200 text-slate-500" :class="{'rotate-180': isUbudiyahOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="isUbudiyahOpen" x-collapse class="mt-2 space-y-1 pl-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2">

                <a href="{{ route('ubudiyah.index') }}" class="{{ $childBase }} {{ request()->routeIs('ubudiyah.index') ? $childActive : $childInactive }}">
                    Input Setoran Tahfidz
                </a>
                <a href="{{ route('ubudiyah.mutabaah') }}" class="{{ $childBase }} {{ request()->routeIs('ubudiyah.mutabaah') ? $childActive : $childInactive }}">
                    Laporan Mutaba'ah
                </a>
                <a href="{{ route('admin.master-data.surats.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.master-data.surats.*') ? $childActive : $childInactive }}">
                    Manajemen Surat
                </a>
            </div>
        </div>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'pengajaran']))
        <!-- GRUP: PENJADWALAN -->
        <div class="pt-4 mt-4 border-t border-slate-200">
            <button @click="isPenjadwalanOpen = !isPenjadwalanOpen" class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-semibold text-slate-800 hover:bg-slate-50 transition-all duration-200 border-l-4 border-transparent">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-5 h-5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span>Penjadwalan</span>
                </div>
                <svg class="h-4 w-4 transform transition-transform duration-200 text-slate-500" :class="{'rotate-180': isPenjadwalanOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="isPenjadwalanOpen" x-collapse class="mt-2 space-y-1 pl-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2">

                <a href="{{ route('admin.rules.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.rules.*') ? $childActive : $childInactive }}">
                    Aturan Penjadwalan
                </a>
                <a href="{{ route('admin.teacher-availability.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.teacher-availability.*') ? $childActive : $childInactive }}">
                    Ketersediaan Guru
                </a>
                <a href="{{ route('admin.generator.show') }}" class="{{ $childBase }} {{ request()->routeIs('admin.generator.*') ? $childActive : $childInactive }}">
                    Generate Jadwal
                </a>
                <a href="{{ route('admin.schedule.view.grid') }}" class="{{ $childBase }} {{ request()->routeIs('admin.schedule.view.*') ? $childActive : $childInactive }}">
                    Lihat Jadwal (Admin)
                </a>
                <a href="{{ route('admin.schedule.swap.show') }}" class="{{ $childBase }} {{ request()->routeIs('admin.schedule.swap.*') ? $childActive : $childInactive }}">
                    Tukar Jadwal Manual
                </a>
            </div>
        </div>
        @endif

        @if(Auth::user()->role === 'admin')
        <!-- GRUP: ADMINISTRASI -->
        <div class="pt-4 mt-4 border-t border-slate-200">
            <button @click="isAdministrasiOpen = !isAdministrasiOpen" class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-semibold text-slate-800 hover:bg-slate-50 transition-all duration-200 border-l-4 border-transparent">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-5 h-5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span>Administrasi</span>
                </div>
                <svg class="h-4 w-4 transform transition-transform duration-200 text-slate-500" :class="{'rotate-180': isAdministrasiOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="isAdministrasiOpen" x-collapse class="mt-2 space-y-1 pl-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2">

                <a href="{{ route('admin.users.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.users.*') ? $childActive : $childInactive }}">
                    Manajemen User
                </a>
                <a href="{{ route('pengajaran.jabatan.index') }}" class="{{ $childBase }} {{ request()->routeIs('pengajaran.jabatan.*') ? $childActive : $childInactive }}">
                    Manajemen Jabatan
                </a>
                <a href="{{ route('admin.settings.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.settings.*') ? $childActive : $childInactive }}">
                    Manajemen Aplikasi Umum
                </a>
            </div>
        </div>
        @endif

        <!-- GRUP: KONTEN -->
        @if(in_array(Auth::user()->role, ['admin', 'dokumentasi']))
        <div class="pt-4 mt-4 border-t border-slate-200">
            <button @click="isKontenOpen = !isKontenOpen" class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-semibold text-slate-800 hover:bg-slate-50 transition-all duration-200 border-l-4 border-transparent">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-5 h-5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span>Konten</span>
                </div>
                <svg class="h-4 w-4 transform transition-transform duration-200 text-slate-500" :class="{'rotate-180': isKontenOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="isKontenOpen" x-collapse class="mt-2 space-y-1 pl-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2">
                <a href="{{ route('slider.index') }}" class="{{ $childBase }} {{ request()->routeIs('slider.*') ? $childActive : $childInactive }}">
                    Manajemen Slider
                </a>
            </div>
        </div>
        @endif
    </nav>

    <!-- USER DROPDOWN (di bawah sidebar) -->
    <div class="border-t border-slate-200 p-4 bg-white/95 backdrop-blur-sm">
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex w-full items-center gap-3 rounded-xl p-3 text-left transition-all duration-200 hover:bg-slate-50 focus:outline-none border border-transparent hover:border-slate-200">
                <img class="h-10 w-10 rounded-full object-cover border-2 border-red-600 hover:border-red-500 transition-colors duration-200 shadow-sm" src="{{ Auth::user()->profile_photo ? Storage::url(Auth::user()->profile_photo) : asset('images/default-profile.png') }}" alt="Foto Profil">
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500 capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</div>
                </div>
                <svg class="h-4 w-4 text-slate-500 transition-transform duration-200 flex-shrink-0" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="absolute bottom-full left-4 right-4 mb-2 rounded-xl bg-white py-2 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none border border-slate-200" @click.outside="open = false" x-cloak>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Log Out
                    </a>
                </form>
            </div>
        </div>
    </div>
</aside>

<!-- 
======================================================================
[BAGIAN 3] Bottom Navigation Bar (Hanya untuk Mobile) - VERSI LEBIH PROFESIONAL
====================================================================== 
-->
@php
    $dockItems = [];
    $santriWali = null;

    if (Auth::user()->role === 'wali_santri') {
        $santriWali = \App\Models\Santri::where('wali_id', Auth::id())->first();

        $dockItems[] = [
            'label' => 'Beranda',
            'url' => route('dashboard'),
            'icon' => 'home',
            'active' => request()->routeIs('dashboard'),
            'disabled' => false,
        ];

        $dockItems[] = [
            'label' => $santriWali ? 'Data Santri' : 'Tidak Ada Santri',
            'url' => $santriWali ? route('pengajaran.santris.edit', $santriWali) : null,
            'icon' => 'user',
            'active' => request()->routeIs('pengajaran.santris.edit'),
            'disabled' => !$santriWali,
        ];

        $dockItems[] = [
            'label' => 'Menu',
            'url' => route('menu.index'),
            'icon' => 'menu',
            'active' => request()->routeIs('menu.index'),
            'disabled' => false,
        ];
    } else {
        $dockItems = [
            [
                'label' => 'Beranda',
                'url' => route('dashboard'),
                'icon' => 'home',
                'active' => request()->routeIs('dashboard'),
                'disabled' => false,
            ],
            [
                'label' => 'Kelas',
                'url' => route('pengajaran.kelas.index'),
                'icon' => 'kelas',
                'active' => request()->routeIs('pengajaran.kelas.*'),
                'disabled' => false,
            ],
            [
                'label' => 'Jadwal',
                'url' => route('jadwal.public.index'),
                'icon' => 'calendar',
                'active' => request()->routeIs('jadwal.public.index'),
                'disabled' => false,
            ],
            [
                'label' => 'Laporan',
                'url' => route('laporan.index'),
                'icon' => 'chart',
                'active' => request()->routeIs('laporan.*'),
                'disabled' => false,
            ],
            [
                'label' => 'Menu',
                'url' => route('menu.index'),
                'icon' => 'menu',
                'active' => request()->routeIs('menu.index'),
                'disabled' => false,
            ],
        ];
    }

    $dockIconSvgs = [
        'home' => '<svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
</svg>',
        'user' => '<svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
</svg>',
        'kelas' => '<svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
</svg>',
        'calendar' => '<svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
</svg>',
        'chart' => '<svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
</svg>',
        'menu' => '<svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
</svg>',
    ];

    $dockBaseClass = 'flex flex-col items-center justify-center gap-0.5 text-[11px] font-semibold transition-all duration-200 py-2';
    $dockActiveClass = 'text-red-600';
    $dockInactiveClass = 'text-slate-500';
@endphp

<footer class="fixed bottom-0 left-0 z-10 w-full border-t border-slate-200 bg-white/95 backdrop-blur supports-backdrop-blur:bg-white/80 md:hidden">
    <nav class="mx-auto max-w-screen-md">
        <ul class="flex items-stretch justify-between gap-1 px-2">
            @foreach($dockItems as $item)
                @php
                    $isDisabled = $item['disabled'] ?? false;
                    $isActive = $item['active'] ?? false;
                    $iconKey = $item['icon'] ?? 'menu';
                    $label = $item['label'] ?? 'Menu';
                    $linkClasses = $dockBaseClass . ' ' . ($isActive ? $dockActiveClass : $dockInactiveClass);
                    $linkClasses .= $isDisabled ? ' opacity-40 pointer-events-none' : '';
                @endphp
                <li class="flex-1">
                    @if($isDisabled || empty($item['url']))
                        <div class="{{ $linkClasses }} text-center">
                            {!! $dockIconSvgs[$iconKey] ?? '' !!}
                            <span>{{ $label }}</span>
                        </div>
                    @else
                        <a href="{{ $item['url'] }}" class="{{ $linkClasses }} text-center" aria-current="{{ $isActive ? 'page' : 'false' }}">
                            {!! $dockIconSvgs[$iconKey] ?? '' !!}
                            <span>{{ $label }}</span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>
</footer>
