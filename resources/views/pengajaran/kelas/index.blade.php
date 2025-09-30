<x-app-layout>
    {{-- [TAMBAHAN] Alpine.js dibutuhkan untuk menu aksi 'kebab' di mobile --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endpush

    {{-- [TAMBAHAN] Style untuk menyembunyikan elemen Alpine.js sebelum dimuat --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-6 sm:space-y-8">

                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Data Master Kelas</h1>
                    <p class="mt-1 text-slate-600">Kelola semua kelas, penanggung jawab, dan ruangan induk.</p>
                </div>

                @if (session('success') || session('error'))
                    {{-- Panel Notifikasi akan tetap di sini --}}
                @endif
                
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Pencarian Santri & Aksi</h3>
                    <div class="space-y-4">
                        <form action="{{ route('pengajaran.kelas.index') }}" method="GET">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <div class="flex-grow relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg></div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-md border-gray-300 pl-10 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari santri berdasarkan nama atau NIS...">
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Cari</button>
                                    <a href="{{ route('pengajaran.kelas.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Reset</a>
                                </div>
                            </div>
                        </form>
                        @can('create', App\Models\Kelas::class)
                        <div class="border-t border-slate-200 pt-4 flex flex-col sm:flex-row items-center gap-3">
                            <p class="text-sm text-slate-600 flex-shrink-0">Aksi Kode Wali:</p>
                            <div class="flex items-center space-x-2">
                                <form action="{{ route('pengajaran.kelas.generate_all_wali_codes') }}" method="POST" onsubmit="return confirm('Anda yakin ingin membuat kode untuk semua santri yang belum memilikinya? Proses ini tidak bisa dibatalkan.')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        <span>Generate Semua</span>
                                    </button>
                                </form>
                                <a href="{{ route('pengajaran.kelas.export_wali_codes') }}" class="inline-flex items-center gap-2 justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span>Export</span>
                                </a>
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>

                @if(request('search'))
                    @if($hasilPencarianSantri->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                        <div class="p-4 border-b border-slate-200"><h3 class="font-semibold text-gray-800">Hasil Pencarian untuk "{{ request('search') }}"</h3></div>
                        <div class="divide-y divide-slate-200">
                            @foreach ($hasilPencarianSantri as $santri)
                                <div class="p-4 flex justify-between items-center hover:bg-slate-50">
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $santri->nama }}</p>
                                        <p class="text-sm text-slate-500">NIS: {{ $santri->nis ?? '-' }} | Kelas: {{ $santri->kelas->nama_kelas ?? 'N/A' }}</p>
                                    </div>
                                    <a href="{{ route('santri.profil.show', $santri) }}" class="text-sm font-medium text-red-600 hover:text-red-800">Lihat Profil &rarr;</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="border-l-4 border-yellow-400 bg-yellow-50 p-4 rounded-r-lg"><p class="text-sm text-yellow-700">Santri dengan nama atau NIS "{{ request('search') }}" tidak ditemukan.</p></div>
                    @endif
                @endif
                
                <div>
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-4">
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-800">Daftar Kelas</h2>
                        @can('create', App\Models\Kelas::class)
                        {{-- Tombol tambah ini disembunyikan di desktop karena sudah ada di header --}}
                        <a href="{{ route('pengajaran.kelas.create') }}" class="sm:hidden w-full mt-2 inline-flex items-center justify-center gap-2 rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                            <span>Tambah Kelas</span>
                        </a>
                        @endcan
                    </div>
                    
                    <div class="md:hidden grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse ($kelas_list as $kelas)
                        <div class="bg-white rounded-xl shadow-lg border border-slate-200 flex flex-col">
                            <div class="p-4 flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-lg text-slate-800">{{ $kelas->nama_kelas }}</h3>
                                    <span class="inline-flex items-center rounded-full {{ $kelas->is_active_for_scheduling ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-2 py-0.5 text-xs font-medium">
                                        {{ $kelas->is_active_for_scheduling ? 'Aktif Jadwal' : 'Nonaktif Jadwal' }}
                                    </span>
                                </div>
                                <div x-data="{ open: false }" class="relative flex-shrink-0 -mr-2 -mt-2">
                                    <button @click="open = !open" class="p-2 text-slate-500 hover:text-slate-800 rounded-full focus:outline-none focus:ring-2 focus:ring-red-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                    </button>
                                    <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5 py-1 z-10" x-cloak>
                                        @can('update', $kelas)
                                        <a href="{{ route('pengajaran.kelas.edit', $kelas) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Edit Kelas</a>
                                        @endcan
                                        @can('delete', $kelas)
                                        <form action="{{ route('pengajaran.kelas.destroy', $kelas) }}" method="POST" onsubmit="return confirm('Yakin hapus? Semua santri di kelas ini juga akan terhapus.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Hapus Kelas</button>
                                        </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 pb-4 space-y-3 text-sm">
                                <div class="grid grid-cols-2 gap-2 text-center">
                                    <div class="bg-slate-100 p-2 rounded-lg">
                                        <div class="text-xs text-slate-500">Santri</div>
                                        <div class="font-bold text-slate-800">{{ $kelas->santris_count }}</div>
                                    </div>
                                    <div class="bg-slate-100 p-2 rounded-lg">
                                        <div class="text-xs text-slate-500">Tingkatan</div>
                                        <div class="font-bold text-slate-800">{{ $kelas->tingkatan ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div><span class="text-slate-500">Ruangan:</span> <span class="font-semibold text-slate-700">{{ $kelas->room->name ?? 'N/A' }}</span></div>
                            </div>
                            <div class="mt-auto p-3 border-t border-slate-200 bg-slate-50/50 rounded-b-xl">
                                <a href="{{ route('pengajaran.santris.index', $kelas) }}" class="w-full inline-flex items-center justify-center rounded-md bg-red-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                    Lihat Daftar Santri
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-1 sm:col-span-2 py-12 text-center text-slate-500">Belum ada data kelas.</div>
                        @endforelse
                    </div>

                    <div class="hidden md:block bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Nama Kelas</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Penanggung Jawab</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Ruangan Induk</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Status Jadwal</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Jumlah Santri</th>
                                    <th class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($kelas_list as $kelas)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 font-medium text-slate-900 align-top">
                                        {{ $kelas->nama_kelas }}
                                        <div class="text-xs text-slate-500 font-normal">Tingkatan: {{ $kelas->tingkatan ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 align-top">
                                        @forelse($kelas->penanggungJawab as $pj)
                                            <div class="text-xs {{ !$loop->last ? 'mb-1' : '' }}">
                                                <span class="font-semibold text-slate-600">{{ $pj->jabatan->nama_jabatan ?? 'N/A' }}:</span>
                                                <span>{{ $pj->user->name ?? 'N/A' }}</span>
                                            </div>
                                        @empty
                                            <span class="text-slate-400 text-xs">-</span>
                                        @endforelse
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 align-top">{{ $kelas->room->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 align-top">
                                        @if($kelas->is_active_for_scheduling)
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Aktif</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 align-top">{{ $kelas->santris_count }} santri</td>
                                    <td class="px-6 py-4 text-right space-x-4 align-top">
                                        <a href="{{ route('pengajaran.santris.index', $kelas) }}" class="font-medium text-red-600 hover:text-red-800">Lihat Santri</a>
                                        @can('update', $kelas)
                                        <a href="{{ route('pengajaran.kelas.edit', $kelas) }}" class="font-medium text-slate-600 hover:text-slate-900">Edit</a>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada data kelas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8">
                    {{ $kelas_list->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>