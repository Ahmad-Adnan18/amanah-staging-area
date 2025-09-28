<!-- 
======================================================================
KOMPONEN NAVIGASI HIBRIDA LENGKAP
======================================================================
-->

<!-- [BAGIAN 1] Overlay Latar Belakang untuk Sidebar Mobile -->
<div x-show="sidebarOpen" class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity md:hidden" @click="sidebarOpen = false" x-cloak></div>

<!-- [BAGIAN 2] Sidebar Utama -->
<!-- Di mobile, ini berfungsi sebagai panel "Menu Lengkap" yang tersembunyi -->
<aside class="fixed inset-y-0 left-0 z-30 flex w-64 transform flex-col bg-white text-gray-900 border-r border-slate-200 transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0" :class="{ 'translate-x-0': sidebarOpen }" x-cloak>

    <!-- Logo dan Nama Aplikasi -->
    <div class="flex h-20 flex-shrink-0 items-center justify-center border-b border-slate-200">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4">
            <img src="{{ asset('images/logo-kunka-merah.png') }}" alt="Logo Pondok" class="block h-9 w-auto">
            <span class="text-xl font-bold tracking-wider text-gray-800" style="font-family: 'Inter', sans-serif;">
                {{ config('app.name', 'Amanah') }}
            </span>
        </a>
    </div>

    <!-- Link Navigasi Sidebar -->
    <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6" x-data="{
        isAkademikOpen: {{ request()->routeIs(['pengajaran.*', 'admin.santri-management.*', 'akademik.*', 'admin.rooms.*', 'admin.teachers.*', 'laporan.*']) ? 'true' : 'false' }},
        isPenjadwalanOpen: {{ request()->routeIs(['admin.rules.*', 'admin.teacher-availability.*', 'admin.generator.*', 'admin.schedule.view.*', 'admin.schedule.swap.*']) ? 'true' : 'false' }},
        isAdministrasiOpen: {{ request()->routeIs(['admin.users.*']) ? 'true' : 'false' }}
    }">
        @php
            $baseClasses = 'flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-all duration-200 border-l-4';
            $activeClasses = 'bg-red-50 text-red-700 font-semibold border-red-600';
            $inactiveClasses = 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 border-transparent';

            $childBase = 'flex items-center gap-3 rounded-md px-3 py-2 text-sm transition-all duration-200';
            $childActive = 'bg-slate-100 text-slate-900 font-medium';
            $childInactive = 'text-slate-500 hover:bg-slate-100 hover:text-slate-800';
        @endphp

        <!-- GRUP: MENU UTAMA -->
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}" class="{{ $baseClasses }} {{ request()->routeIs('dashboard') ? $activeClasses : $inactiveClasses }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>{{ __('Dashboard') }}</span>
            </a>
            @if (Auth::user()->role !== 'wali_santri')
            <a href="{{ route('perizinan.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('perizinan.*') ? $activeClasses : $inactiveClasses }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <span>{{ __('Daftar Izin') }}</span>
            </a>
            <a href="{{ route('pelanggaran.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('pelanggaran.*') ? $activeClasses : $inactiveClasses }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ __('Pelanggaran Santri') }}</span>
            </a>
            <a href="{{ route('jadwal.public.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('jadwal.public.index') ? $activeClasses : $inactiveClasses }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>{{ __('Lihat Jadwal') }}</span>
            </a>
            @endif
        </div>

        <!-- GRUP: AKADEMIK -->
        <div class="pt-4 mt-4 border-t border-slate-200">
            @if(in_array(Auth::user()->role, ['admin','pengajaran','pengasuhan','kesehatan','ustadz_umum']))
            <button @click="isAkademikOpen = !isAkademikOpen" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-semibold text-slate-800 hover:bg-slate-100">
                <span>Akademik</span>
                <svg class="h-5 w-5 transform transition-transform" :class="{'rotate-180': isAkademikOpen}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
            @endif
            <div x-show="isAkademikOpen" x-collapse class="mt-1 space-y-1 pl-4">
                @if(in_array(Auth::user()->role, ['admin','pengajaran','pengasuhan','kesehatan','ustadz_umum']))
                <a href="{{ route('admin.santri-management.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.santri-management.*') ? $childActive : $childInactive }}">Data Santri</a>
                <a href="{{ route('pengajaran.kelas.index') }}" class="{{ $childBase }} {{ request()->routeIs('pengajaran.kelas.*') ? $childActive : $childInactive }}">Data Kelas</a>
                <a href="{{ route('laporan.index') }}" class="{{ $childBase }} {{ request()->routeIs('laporan.*') ? $childActive : $childInactive }}">Laporan</a>
                @endif
                @if(in_array(Auth::user()->role, ['admin','pengajaran']))
                <a href="{{ route('admin.teachers.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.teachers.*') ? $childActive : $childInactive }}">Data Guru</a>
                <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="{{ $childBase }} {{ request()->routeIs('pengajaran.mata-pelajaran.*') ? $childActive : $childInactive }}">Mata Pelajaran</a>
                <a href="{{ route('admin.rooms.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.rooms.*') ? $childActive : $childInactive }}">Manajemen Ruangan</a>
                <a href="{{ route('akademik.placement.index') }}" class="{{ $childBase }} {{ request()->routeIs('akademik.placement.*') ? $childActive : $childInactive }}">Penempatan Kelas</a>
                <a href="{{ route('akademik.nilai.index') }}" class="{{ $childBase }} {{ request()->routeIs('akademik.nilai.*') ? $childActive : $childInactive }}">Input Nilai</a>
                @endif
            </div>
        </div>

        @if(in_array(Auth::user()->role, ['admin', 'pengajaran']))
        <!-- GRUP: PENJADWALAN -->
        <div class="pt-4 mt-4 border-t border-slate-200">
            <button @click="isPenjadwalanOpen = !isPenjadwalanOpen" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-semibold text-slate-800 hover:bg-slate-100">
                <span>Penjadwalan</span>
                <svg class="h-5 w-5 transform transition-transform" :class="{'rotate-180': isPenjadwalanOpen}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
            <div x-show="isPenjadwalanOpen" x-collapse class="mt-1 space-y-1 pl-4">
                <a href="{{ route('admin.rules.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.rules.*') ? $childActive : $childInactive }}">Aturan Penjadwalan</a>
                <a href="{{ route('admin.teacher-availability.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.teacher-availability.*') ? $childActive : $childInactive }}">Ketersediaan Guru</a>
                <a href="{{ route('admin.generator.show') }}" class="{{ $childBase }} {{ request()->routeIs('admin.generator.*') ? $childActive : $childInactive }}">Generate Jadwal</a>
                <a href="{{ route('admin.schedule.view.grid') }}" class="{{ $childBase }} {{ request()->routeIs('admin.schedule.view.*') ? $childActive : $childInactive }}">Lihat Jadwal (Admin)</a>
                <a href="{{ route('admin.schedule.swap.show') }}" class="{{ $childBase }} {{ request()->routeIs('admin.schedule.swap.*') ? $childActive : $childInactive }}">Tukar Jadwal Manual</a>
            </div>
        </div>
        @endif

        @if(Auth::user()->role === 'admin')
        <!-- GRUP: ADMINISTRASI -->
        <div class="pt-4 mt-4 border-t border-slate-200">
            <button @click="isAdministrasiOpen = !isAdministrasiOpen" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-semibold text-slate-800 hover:bg-slate-100">
                <span>Administrasi</span>
                <svg class="h-5 w-5 transform transition-transform" :class="{'rotate-180': isAdministrasiOpen}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
            <div x-show="isAdministrasiOpen" x-collapse class="mt-1 space-y-1 pl-4">
                <a href="{{ route('admin.users.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.users.*') ? $childActive : $childInactive }}">Manajemen User</a>
                <a href="{{ route('pengajaran.jabatan.index') }}" class="{{ $childBase }} {{ request()->routeIs('pengajaran.jabatan.*') ? $childActive : $childInactive }}">Manajemen Jabatan</a>
            </div>
        </div>
        @endif
    </nav>

    <!-- USER DROPDOWN (di bawah sidebar) -->
    <div class="border-t border-slate-200 p-4">
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex w-full items-center rounded-lg p-2 text-left transition-colors duration-200 hover:bg-slate-100 focus:outline-none">
                <img class="mr-3 h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FBBF24&color=78350F" alt="Avatar">
                <div class="flex-1">
                    <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}</div>
                </div>
                <svg class="h-5 w-5 fill-current text-slate-500 transition-transform duration-200" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
            </button>
            <div x-show="open" x-transition class="absolute bottom-full mb-2 w-56 origin-bottom-left rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" @click.outside="open = false" x-cloak>
                <a href="{{ route('profile.edit') }}" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">Log Out</a>
                </form>
            </div>
        </div>
    </div>
</aside>

<!-- 
======================================================================
[BAGIAN 3] BARU: Bottom Navigation Bar (Hanya untuk Mobile)
====================================================================== 
-->
<footer class="fixed bottom-0 left-0 z-10 w-full bg-white border-t border-slate-200 md:hidden">
    <div class="grid h-16 max-w-lg grid-cols-5 mx-auto">
        
        <a href="{{ route('dashboard') }}" class="inline-flex flex-col items-center justify-center px-5 font-medium {{ request()->routeIs('dashboard') ? 'text-red-600 bg-red-50' : 'text-slate-500 hover:bg-slate-50' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-xs">Beranda</span>
        </a>

        <a href="{{ route('pengajaran.kelas.index') }}" class="inline-flex flex-col items-center justify-center px-5 font-medium {{ request()->routeIs('pengajaran.kelas.*') ? 'text-red-600 bg-red-50' : 'text-slate-500 hover:bg-slate-50' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l-4 16M4 12h16"></path></svg>
            <span class="text-xs">Kelas</span>
        </a>
        
        <a href="{{ route('jadwal.public.index') }}" class="inline-flex flex-col items-center justify-center px-5 font-medium {{ request()->routeIs('jadwal.public.index') ? 'text-red-600 bg-red-50' : 'text-slate-500 hover:bg-slate-50' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-xs">Jadwal</span>
        </a>

        <a href="{{ route('laporan.index') }}" class="inline-flex flex-col items-center justify-center px-5 font-medium {{ request()->routeIs('laporan.*') ? 'text-red-600 bg-red-50' : 'text-slate-500 hover:bg-slate-50' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="text-xs">Laporan</span>
        </a>

        <!-- [PERUBAHAN] Mengubah <button> menjadi <a> yang mengarah ke halaman menu baru -->
        <a href="{{ route('menu.index') }}" class="inline-flex flex-col items-center justify-center px-5 font-medium {{ request()->routeIs('menu.index') ? 'text-red-600 bg-red-50' : 'text-slate-500 hover:bg-slate-50' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            <span class="text-xs">Menu</span>
        </a>

    </div>
</footer>
