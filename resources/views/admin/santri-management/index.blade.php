<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- [BAGIAN 1] HEADER HALAMAN -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Pusat Manajemen Santri</h1>
                            <p class="mt-1 text-slate-600">Cari, filter, dan kelola semua data santri di pondok.</p>
                        </div>
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <a href="{{ route('pengajaran.kelas.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Tambah Manual
                            </a>
                            <a href="{{ route('admin.santri-management.import.show') }}" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Import dari Excel
                            </a>
                        </div>
                    </div>
                </div>

                <!-- [BAGIAN 2] KARTU STATISTIK -->
                <!-- Dirapikan agar lebih konsisten dan mudah dibaca -->
                @php
                    $statCardClass = 'bg-white p-5 rounded-2xl shadow-lg border border-slate-200 flex items-start gap-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-red-300';
                    $iconContainerClass = 'bg-slate-100 p-3 rounded-full';
                    $iconClass = 'h-6 w-6 text-slate-600';
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                    <div class="{{ $statCardClass }}">
                        <div class="{{ $iconContainerClass }}"><svg class="{{ $iconClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.5-2.962a3.75 3.75 0 015.968 0 3.75 3.75 0 01-5.968 0zM21 12.75A9 9 0 113 12.75v6.75a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 19.5v-6.75z" /></svg></div>
                        <div>
                            <p class="text-sm text-slate-600">Total Santri</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                    <div class="{{ $statCardClass }}">
                        <div class="{{ $iconContainerClass }}"><svg class="{{ $iconClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg></div>
                        <div>
                            <p class="text-sm text-slate-600">Jumlah Putra</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $stats['putra'] }}</p>
                        </div>
                    </div>
                    <div class="{{ $statCardClass }}">
                        <div class="{{ $iconContainerClass }}"><svg class="{{ $iconClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg></div>
                        <div>
                            <p class="text-sm text-slate-600">Jumlah Putri</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $stats['putri'] }}</p>
                        </div>
                    </div>
                    <!-- Kartu Peringatan (dibuat konsisten dengan ikon) -->
                    <div class="bg-amber-50 p-5 rounded-2xl shadow-lg border border-amber-200 flex items-start gap-4">
                        <div class="bg-amber-100 p-3 rounded-full"><svg class="h-6 w-6 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg></div>
                        <div>
                            <p class="text-sm text-amber-700">Tanpa NIS</p>
                            <p class="text-2xl font-bold text-amber-900">{{ $stats['tanpa_nis'] }}</p>
                        </div>
                    </div>
                    <div class="bg-amber-50 p-5 rounded-2xl shadow-lg border border-amber-200 flex items-start gap-4">
                        <div class="bg-amber-100 p-3 rounded-full"><svg class="h-6 w-6 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg></div>
                        <div>
                            <p class="text-sm text-amber-700">Tanpa Rayon</p>
                            <p class="text-2xl font-bold text-amber-900">{{ $stats['tanpa_rayon'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- [BAGIAN 3] FILTER DAN PENCARIAN (TELAH DIPERBAIKI) -->
                <!-- Masalah terpotong diselesaikan dengan layout grid yang lebih adaptif -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200" x-data="{ filtersOpen: window.innerWidth >= 768 }">
                    <button @click="filtersOpen = !filtersOpen" class="w-full p-4 flex justify-between items-center md:hidden">
                        <span class="text-lg font-semibold text-slate-800">Filter & Cari</span>
                        <svg class="h-6 w-6 transform transition-transform text-slate-500" :class="{'rotate-180': filtersOpen}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                    <!-- PERBAIKAN: Grid diubah menjadi lebih fleksibel untuk berbagai ukuran layar -->
                    <form action="{{ route('admin.santri-management.index') }}" method="GET" class="p-6 border-t border-slate-200 md:border-t-0" x-show="filtersOpen" x-collapse>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 items-end">
                            <!-- Input Pencarian -->
                            <div class="sm:col-span-2 lg:col-span-2 xl:col-span-2">
                                <label for="search" class="block text-sm font-medium text-slate-700 mb-1">Cari Santri</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg></div>
                                    <input type="text" id="search" name="search" value="{{ request('search') }}" class="block w-full rounded-md border-gray-300 pl-10 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Nama atau NIS...">
                                </div>
                            </div>
                            <!-- Filter Kelas -->
                            <div>
                                <label for="kelas_id" class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
                                <select id="kelas_id" name="kelas_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" @selected(request('kelas_id') == $kelas->id)>{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Filter Rayon -->
                            <div>
                                <label for="rayon" class="block text-sm font-medium text-slate-700 mb-1">Rayon</label>
                                <select id="rayon" name="rayon" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">Semua Rayon</option>
                                    @foreach($rayonList as $rayon)
                                    <option value="{{ $rayon }}" @selected(request('rayon') == $rayon)>{{ $rayon }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- PERBAIKAN: Tombol dipisahkan untuk layout yang lebih baik -->
                            <div class="flex items-center space-x-2">
                                <button type="submit" class="w-full inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Filter</button>
                                <a href="{{ route('admin.santri-management.index') }}" class="w-full text-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- [BAGIAN 4] DAFTAR DATA (Mobile & Desktop) -->
                <!-- Tampilan Kartu untuk Mobile -->
                <div class="space-y-4 md:hidden">
                    @forelse ($santris as $santri)
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                            <a href="{{ route('santri.profil.show', $santri) }}" class="block p-4">
                                <div class="flex items-center gap-4">
                                    <img class="h-12 w-12 rounded-full object-cover ring-2 ring-slate-100" src="{{ $santri->foto ? Storage::url($santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($santri->nama).'&background=DC2626&color=fff' }}" alt="Avatar">
                                    <div class="flex-1">
                                        <p class="font-semibold text-slate-900">{{ $santri->nama }}</p>
                                        <p class="text-sm text-slate-500">{{ $santri->nis ?? 'NIS belum diisi' }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-slate-200 text-sm text-slate-600 grid grid-cols-2 gap-2">
                                    <div><span class="font-medium text-slate-800">Kelas:</span> {{ $santri->kelas->nama_kelas ?? 'N/A' }}</div>
                                    <div><span class="font-medium text-slate-800">Rayon:</span> {{ $santri->rayon ?? 'N/A' }}</div>
                                </div>
                            </a>
                            <div class="bg-slate-50 border-t border-slate-200 px-4 py-2 text-right">
                                <a href="{{ route('pengajaran.santris.edit', $santri) }}" class="text-sm font-medium text-slate-600 hover:text-red-700">Edit Data</a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center text-slate-500">
                            <p>Tidak ada data santri yang cocok dengan filter.</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Tampilan Tabel untuk Desktop -->
                <div class="hidden md:block bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Santri</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Kelas</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Rayon</th>
                                    <th class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($santris as $santri)
                                <tr class="hover:bg-slate-50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('santri.profil.show', $santri) }}" class="flex items-center group">
                                            <img class="h-10 w-10 rounded-full object-cover ring-2 ring-slate-100 group-hover:ring-red-300" src="{{ $santri->foto ? Storage::url($santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($santri->nama).'&background=DC2626&color=fff' }}" alt="Avatar">
                                            <div class="ml-4">
                                                <div class="font-medium text-slate-900 group-hover:text-red-700">{{ $santri->nama }}</div>
                                                <div class="text-sm text-slate-500">{{ $santri->nis ?? 'NIS belum diisi' }}</div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 text-sm">{{ $santri->kelas->nama_kelas ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-slate-600 text-sm">{{ $santri->rayon ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('pengajaran.santris.edit', $santri) }}" class="font-medium text-slate-600 hover:text-red-700">Edit</a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Tidak ada data santri yang cocok dengan filter.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if ($santris->hasPages())
                <div class="p-4 bg-white rounded-2xl shadow-lg border border-slate-200">
                    {{ $santris->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
