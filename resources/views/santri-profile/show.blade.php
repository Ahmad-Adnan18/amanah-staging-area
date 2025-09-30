<x-app-layout>
    {{-- AlpineJS helper untuk deteksi mobile (tetap sama) --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.magic('isMobile', () => {
                return () => window.innerWidth < 768;
            });
        });
    </script>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">
                <!-- Header Profil Santri & Aksi (tetap sama) -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col md:flex-row items-center md:items-start gap-4 md:gap-6 text-center md:text-left">
                            <div class="flex-shrink-0 mx-auto md:mx-0">
                                <img class="h-24 w-24 sm:h-28 sm:w-28 rounded-full object-cover ring-4 ring-red-100"
                                     src="{{ $santri->foto ? Storage::url($santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($santri->nama).'&background=FBBF24&color=fff&size=128' }}"
                                     alt="Foto Santri">
                            </div>
                            <div class="flex-grow w-full">
                                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $santri->nama }}</h1>
                                <div class="mt-2 text-slate-600 space-y-1 sm:space-y-0 sm:flex sm:flex-wrap sm:items-center sm:gap-x-3 sm:gap-y-1 text-sm">
                                    <span class="inline-flex items-center gap-1.5"><svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M3.75 3A1.75 1.75 0 002 4.75v10.5c0 .966.784 1.75 1.75 1.75h12.5A1.75 1.75 0 0018 15.25V4.75A1.75 1.75 0 0016.25 3H3.75z"/></svg> {{ $santri->nis }}</span>
                                    <span class="hidden sm:inline text-slate-300">•</span>
                                    <span class="inline-flex items-center gap-1.5"><svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M9 7.5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 019 7.5zm3.75-1.5a.75.75 0 00-1.5 0v7.5a.75.75 0 001.5 0v-7.5z"/></svg> {{ $santri->kelas->nama_kelas ?? 'N/A' }}</span>
                                    <span class="hidden sm:inline text-slate-300">•</span>
                                    <span class="inline-flex items-center gap-1.5"><svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a.75.75 0 01.75.75v.25a.75.75 0 01-1.5 0V2.75A.75.75 0 0110 2zM5.133 4.352a.75.75 0 01.936 1.204l-.793.616a.75.75 0 01-.936-1.204l.793-.616zM13.93 5.556a.75.75 0 01.936-1.204l.793.616a.75.75 0 01-.936 1.204l-.793-.616z"/></svg> {{ $santri->jenis_kelamin }}</span>
                                    <span class="hidden sm:inline text-slate-300">•</span>
                                    <span class="inline-flex items-center gap-1.5"><svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.979.577.75.75 0 00.018.008l.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z"/></svg> {{ $santri->rayon }}</span>
                                </div>
                                @if($namaWaliKelas)
                                    <p class="mt-3 text-sm text-gray-800 bg-green-100 inline-block px-3 py-1 rounded-full">
                                        <span class="font-semibold">Wali Kelas:</span> {{ $namaWaliKelas }}
                                    </p>
                                @endif
                            </div>
                            <!-- Tombol Aksi Cepat (tetap sama) -->
                            @if(in_array(Auth::user()->role, ['admin','pengajaran','pengasuhan','kesehatan','ustadz_umum']))
                                <div class="w-full md:w-auto mt-4 md:mt-0" x-data="{ open: false }">
                                    <div class="relative flex justify-center md:justify-end">
                                        <button @click="open = !open"
                                                class="w-full sm:w-auto flex items-center justify-center gap-2 rounded-md bg-red-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                                            Aksi Cepat
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="open"
                                             @click.outside="open = false"
                                             x-transition
                                             class="absolute z-10 mt-2 w-56 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                             :class="$isMobile() ? 'left-1/2 -translate-x-1/2 bottom-full mb-2' : 'right-0 top-full'"
                                             x-cloak>
                                            <div class="py-1">
                                                @can('create', App\Models\Perizinan::class)
                                                    <a href="{{ route('perizinan.create', $santri) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Buat Izin</a>
                                                @endcan
                                                @can('create', [App\Models\CatatanHarian::class, $santri])
                                                    <a href="{{ route('keasramaan.catatan.create', $santri) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tambah Catatan</a>
                                                @endcan
                                                @can('create', [App\Models\Prestasi::class, $santri])
                                                    <a href="{{ route('keasramaan.prestasi.create', $santri) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tambah Prestasi</a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Kode Registrasi Wali (tetap sama) -->
                    @can('update', $santri)
                        <div class="bg-slate-50 border-t border-slate-200 px-4 sm:px-6 py-4">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Kode Registrasi Wali</h3>
                                    <p class="text-xs text-slate-600 mt-1">Bagikan kode ini kepada wali santri.</p>
                                </div>
                                <div class="flex-shrink-0 w-full sm:w-auto">
                                    @if ($santri->kode_registrasi_wali)
                                        <div class="flex flex-wrap items-center gap-2 justify-end sm:justify-start">
                                            <span class="px-3 py-1.5 bg-slate-200 text-slate-800 font-mono text-base rounded-md">{{ $santri->kode_registrasi_wali }}</span>
                                            <form action="{{ route('santri.profil.generate_wali_code', $santri) }}" method="POST" onsubmit="return confirm('Yakin ingin membuat kode baru? Kode lama akan hangus.')">
                                                @csrf
                                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 whitespace-nowrap">Buat Ulang</button>
                                            </form>
                                        </div>
                                    @else
                                        <form action="{{ route('santri.profil.generate_wali_code', $santri) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full sm:w-auto flex items-center justify-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50">
                                                Generate Kode
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>

                <!-- Konten Kartu (Baru) -->
                <div class="space-y-6">
                    <!-- Kartu Riwayat Perizinan -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Riwayat Perizinan</h2>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="hidden sm:block overflow-x-auto"> <!-- Tambahkan overflow-x-auto -->
                                <table class="divide-y divide-slate-200"> <!-- Hapus min-w-full -->
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jenis Izin</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @forelse ($santri->perizinans as $izin)
                                            <tr>
                                                <td class="px-6 py-4">
                                                    <div class="font-medium text-slate-900">{{ $izin->jenis_izin }}</div>
                                                    <div class="text-sm text-slate-500">{{ Str::limit($izin->keterangan, 40) }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">
                                                    {{ $izin->tanggal_mulai->format('d M Y') }}
                                                    @if($izin->tanggal_akhir) – {{ $izin->tanggal_akhir->format('d M Y') }} @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $izin->status == 'aktif' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst($izin->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="px-6 py-12 text-center text-slate-500">Tidak ada riwayat perizinan.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <ul class="divide-y divide-slate-200 sm:hidden">
                                @forelse ($santri->perizinans as $izin)
                                    <li class="p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-semibold text-slate-900">{{ $izin->jenis_izin }}</p>
                                                <p class="text-xs text-slate-500">{{ $izin->tanggal_mulai->format('d M Y') }}@if($izin->tanggal_akhir) – {{ $izin->tanggal_akhir->format('d M Y') }}@endif</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $izin->status == 'aktif' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($izin->status) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-slate-600 mt-2">{{ $izin->keterangan }}</p>
                                    </li>
                                @empty
                                    <li class="p-8 text-center text-slate-500 text-sm">Tidak ada riwayat perizinan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- Kartu Riwayat Pelanggaran -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Riwayat Pelanggaran</h2>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="hidden sm:block overflow-x-auto"> <!-- Tambahkan overflow-x-auto -->
                                <table class="divide-y divide-slate-200"> <!-- Hapus min-w-full -->
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jenis Pelanggaran</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @forelse ($santri->pelanggarans as $pelanggaran)
                                            <tr>
                                                <td class="px-6 py-4">
                                                    <div class="font-medium text-slate-900">{{ $pelanggaran->jenis_pelanggaran }}</div>
                                                    <div class="text-sm text-slate-500">{{ $pelanggaran->keterangan }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-slate-500">{{ $pelanggaran->tanggal_kejadian->format('d M Y') }}</td>
                                                <td class="px-6 py-4 text-sm text-slate-500">{{ $pelanggaran->dicatat_oleh }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="px-6 py-12 text-center text-slate-500">Tidak ada riwayat pelanggaran.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <ul class="divide-y divide-slate-200 sm:hidden">
                                @forelse ($santri->pelanggarans as $pelanggaran)
                                    <li class="p-4">
                                        <p class="font-semibold text-slate-900">{{ $pelanggaran->jenis_pelanggaran }}</p>
                                        <p class="text-xs text-slate-500">{{ $pelanggaran->tanggal_kejadian->format('d M Y') }} • {{ $pelanggaran->dicatat_oleh }}</p>
                                        <p class="text-sm text-slate-600 mt-2">{{ $pelanggaran->keterangan }}</p>
                                    </li>
                                @empty
                                    <li class="p-8 text-center text-slate-500 text-sm">Tidak ada riwayat pelanggaran.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- Kartu Catatan Harian -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Catatan Harian</h2>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="hidden sm:block overflow-x-auto"> <!-- Tambahkan overflow-x-auto -->
                                <table class="divide-y divide-slate-200"> <!-- Hapus min-w-full -->
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Catatan</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @forelse ($santri->catatanHarians as $catatan)
                                            <tr>
                                                <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">{{ $catatan->tanggal->format('d M Y') }}</td>
                                                <td class="px-6 py-4 text-sm text-slate-800 max-w-xs sm:max-w-none">{{ $catatan->catatan }}</td>
                                                <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">{{ $catatan->pencatat->name }}</td>
                                                <td class="px-6 py-4 text-sm whitespace-nowrap">
                                                    @can('update', $catatan)
                                                        <a href="{{ route('keasramaan.catatan.edit', $catatan) }}" class="text-slate-600 hover:text-red-700 font-medium">Edit</a>
                                                    @endcan
                                                    @can('delete', $catatan)
                                                        <form action="{{ route('keasramaan.catatan.destroy', $catatan) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Tidak ada catatan harian.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <ul class="divide-y divide-slate-200 sm:hidden">
                                @forelse ($santri->catatanHarians as $catatan)
                                    <li class="p-4">
                                        <div class="flex justify-between items-start">
                                            <p class="font-semibold text-slate-900">{{ $catatan->tanggal->format('d M Y') }}</p>
                                            <span class="text-xs text-slate-500">{{ $catatan->pencatat->name }}</span>
                                        </div>
                                        <p class="mt-2 text-slate-700">{{ $catatan->catatan }}</p>
                                        <div class="mt-3 flex gap-3">
                                            @can('update', $catatan)
                                                <a href="{{ route('keasramaan.catatan.edit', $catatan) }}" class="text-sm font-medium text-slate-600 hover:text-red-700">Edit</a>
                                            @endcan
                                            @can('delete', $catatan)
                                                <form action="{{ route('keasramaan.catatan.destroy', $catatan) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </li>
                                @empty
                                    <li class="p-8 text-center text-slate-500 text-sm">Tidak ada catatan harian.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- Kartu Prestasi -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Prestasi</h2>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="hidden sm:block overflow-x-auto"> <!-- Tambahkan overflow-x-auto -->
                                <table class="divide-y divide-slate-200"> <!-- Hapus min-w-full -->
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Prestasi</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Poin</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @forelse ($santri->prestasis as $prestasi)
                                            <tr>
                                                <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">{{ $prestasi->tanggal->format('d M Y') }}</td>
                                                <td class="px-6 py-4">
                                                    <div class="font-medium text-slate-900">{{ $prestasi->nama_prestasi }}</div>
                                                    <div class="text-sm text-slate-500">{{ $prestasi->keterangan }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-center font-bold text-green-600">+{{ $prestasi->poin }}</td>
                                                <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">{{ $prestasi->pencatat->name }}</td>
                                                <td class="px-6 py-4 text-sm whitespace-nowrap">
                                                    @can('update', $prestasi)
                                                        <a href="{{ route('keasramaan.prestasi.edit', $prestasi) }}" class="text-slate-600 hover:text-red-700 font-medium">Edit</a>
                                                    @endcan
                                                    @can('delete', $prestasi)
                                                        <form action="{{ route('keasramaan.prestasi.destroy', $prestasi) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">Tidak ada catatan prestasi.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <ul class="divide-y divide-slate-200 sm:hidden">
                                @forelse ($santri->prestasis as $prestasi)
                                    <li class="p-4">
                                        <div class="flex justify-between items-start">
                                            <p class="font-semibold text-slate-900">{{ $prestasi->nama_prestasi }}</p>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">+{{ $prestasi->poin }}</span>
                                        </div>
                                        <p class="text-sm text-slate-500 mt-1">{{ $prestasi->tanggal->format('d M Y') }} • {{ $prestasi->pencatat->name }}</p>
                                        <p class="mt-2 text-slate-700">{{ $prestasi->keterangan }}</p>
                                        <div class="mt-3 flex gap-3">
                                            @can('update', $prestasi)
                                                <a href="{{ route('keasramaan.prestasi.edit', $prestasi) }}" class="text-sm font-medium text-slate-600 hover:text-red-700">Edit</a>
                                            @endcan
                                            @can('delete', $prestasi)
                                                <form action="{{ route('keasramaan.prestasi.destroy', $prestasi) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </li>
                                @empty
                                    <li class="p-8 text-center text-slate-500 text-sm">Tidak ada catatan prestasi.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- Kartu Rapor Akademik -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Rapor Akademik</h2>
                        </div>
                        <div class="p-4 sm:p-6">
                            <!-- Filter Rapor (tetap sama) -->
                            <div x-data="{
                                raporData: {{ json_encode($nilaiAkademik) }},
                                tahunAjaranList: Object.keys({{ json_encode($nilaiAkademik) }}).sort().reverse(),
                                selectedTahun: '{{ $nilaiAkademik->keys()->sortDesc()->first() ?? '' }}',
                                selectedSemester: '',
                                init() {
                                    this.selectedSemester = this.semesterList.includes('Semester Ganjil') ? 'Semester Ganjil' : (this.semesterList[0] || '');
                                },
                                get semesterList() {
                                    return this.selectedTahun ? Object.keys(this.raporData[this.selectedTahun]).sort() : [];
                                },
                                get selectedRapor() {
                                    if (this.selectedTahun && this.selectedSemester) {
                                        return this.raporData[this.selectedTahun][this.selectedSemester] || [];
                                    }
                                    return [];
                                },
                                calculateAverage(tugas, uts, uas) {
                                    const values = [tugas, uts, uas].map(v => parseFloat(v)).filter(v => !isNaN(v));
                                    if (values.length === 0) return '-';
                                    const sum = values.reduce((a, b) => a + b, 0);
                                    return Math.round(sum / values.length);
                                },
                                getPredicate(avg) {
                                    if (avg === '-' || avg <= 0) return { text: '-', class: 'bg-gray-100 text-gray-800' };
                                    if (avg >= 90) return { text: 'Mumtaz', class: 'bg-blue-100 text-blue-800' };
                                    if (avg >= 80) return { text: 'Jayyid Jiddan', class: 'bg-green-100 text-green-800' };
                                    if (avg >= 70) return { text: 'Jayyid', class: 'bg-yellow-100 text-yellow-800' };
                                    if (avg >= 60) return { text: 'Maqbul', class: 'bg-orange-100 text-orange-800' };
                                    return { text: 'Rasib', class: 'bg-red-100 text-red-800' };
                                }
                            }">
                                <div class="p-4 sm:p-0 mb-4">
                                    <div class="flex flex-col sm:flex-row items-stretch gap-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                                            <div>
                                                <label for="tahun_ajaran_filter" class="block text-xs font-medium text-gray-500">Tahun Ajaran</label>
                                                <select x-model="selectedTahun"
                                                        @change="selectedSemester = semesterList.includes('Semester Ganjil') ? 'Semester Ganjil' : (semesterList[0] || '')"
                                                        id="tahun_ajaran_filter"
                                                        class="mt-1 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                                                    <template x-for="tahun in tahunAjaranList" :key="tahun">
                                                        <option :value="tahun" x-text="tahun"></option>
                                                    </template>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="semester_filter" class="block text-xs font-medium text-gray-500">Semester</label>
                                                <select x-model="selectedSemester"
                                                        :disabled="!selectedTahun"
                                                        id="semester_filter"
                                                        class="mt-1 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                                                    <template x-for="semester in semesterList" :key="semester">
                                                        <option :value="semester" x-text="semester"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap gap-2 justify-end">
                                            <a x-show="selectedTahun && selectedSemester"
                                               :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester"
                                               class="inline-flex items-center justify-center gap-1.5 rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Excel
                                            </a>
                                            <div x-data="{ open: false }" class="relative">
                                                <button @click="open = !open"
                                                        class="inline-flex items-center justify-center gap-1.5 rounded-md bg-red-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    PDF
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </button>
                                                <div x-show="open"
                                                     @click.outside="open = false"
                                                     x-transition
                                                     class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                     x-cloak>
                                                    <div class="py-1">
                                                        <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export-pdf') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester"
                                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Rapor Lengkap</a>
                                                        <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export-pdf') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester + '&jenis_penilaian=nilai_tugas'"
                                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nilai Tugas Saja</a>
                                                        <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export-pdf') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester + '&jenis_penilaian=nilai_uts'"
                                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nilai UTS Saja</a>
                                                        <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export-pdf') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester + '&jenis_penilaian=nilai_uas'"
                                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nilai UAS Saja</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Desktop: Tabel Rapor -->
                                <div class="hidden sm:block overflow-x-auto"> <!-- Tambahkan overflow-x-auto -->
                                    <table class="divide-y divide-slate-200"> <!-- Hapus min-w-full -->
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Mata Pelajaran</th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Tugas</th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">UTS</th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">UAS</th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Akhir</th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Predikat</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-200">
                                            <template x-if="selectedRapor.length > 0">
                                                <template x-for="nilai in selectedRapor" :key="nilai.id">
                                                    <tr>
                                                        <td class="px-6 py-4 font-medium text-slate-900" x-text="nilai.mata_pelajaran.nama_pelajaran"></td>
                                                        <td class="px-6 py-4 text-center text-slate-500" x-text="nilai.nilai_tugas || '-'"></td>
                                                        <td class="px-6 py-4 text-center text-slate-500" x-text="nilai.nilai_uts || '-'"></td>
                                                        <td class="px-6 py-4 text-center text-slate-500" x-text="nilai.nilai_uas || '-'"></td>
                                                        <td class="px-6 py-4 text-center font-semibold text-slate-900" x-text="calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)"></td>
                                                        <td class="px-6 py-4 text-center">
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                                                  :class="getPredicate(calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)).class"
                                                                  x-text="getPredicate(calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)).text">
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </template>
                                            <template x-if="selectedRapor.length === 0">
                                                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-500">Tidak ada data nilai untuk periode ini.</td></tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Mobile: Card Rapor (sudah benar) -->
                                <div class="sm:hidden space-y-4 p-4">
                                    <template x-if="selectedRapor.length > 0">
                                        <template x-for="nilai in selectedRapor" :key="nilai.id">
                                            <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
                                                <h4 class="font-semibold text-slate-900" x-text="nilai.mata_pelajaran.nama_pelajaran"></h4>
                                                <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                                    <div>
                                                        <span class="text-slate-500">Tugas:</span>
                                                        <span class="font-medium ml-1" x-text="nilai.nilai_tugas || '-'"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-500">UTS:</span>
                                                        <span class="font-medium ml-1" x-text="nilai.nilai_uts || '-'"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-500">UAS:</span>
                                                        <span class="font-medium ml-1" x-text="nilai.nilai_uas || '-'"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-500">Akhir:</span>
                                                        <span class="font-bold ml-1" x-text="calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)"></span>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full"
                                                          :class="getPredicate(calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)).class"
                                                          x-text="getPredicate(calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)).text">
                                                    </span>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                    <template x-if="selectedRapor.length === 0">
                                        <p class="text-center text-slate-500 py-6">Tidak ada data nilai untuk periode ini.</p>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>