<x-app-layout>
    {{-- AlpineJS helper untuk deteksi mobile --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.magic('isMobile', () => {
                return () => window.innerWidth < 768;
            });
        });

    </script>

    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">
                {{-- Header Profil Santri --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row items-center lg:items-start gap-6 text-center lg:text-left">
                            {{-- Foto Profil --}}
                            <div class="flex-shrink-0">
                                <img class="h-24 w-24 lg:h-28 lg:w-28 rounded-full object-cover ring-4 ring-red-100 shadow-lg" src="{{ $santri->foto ? Storage::url($santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($santri->nama).'&background=FBBF24&color=fff&size=128' }}" alt="Foto Santri">
                            </div>

                            <div class="flex-grow">
                                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $santri->nama }}</h1>

                                {{-- Info Santri --}}
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                                    @php
                                    $infoItems = [
                                    ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => $santri->nis, 'title' => 'NIS'],
                                    ['icon' => 'M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479l6.16-3.422z', 'label' => $santri->kelas->nama_kelas ?? 'N/A', 'title' => 'Kelas'],
                                    ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => $santri->jenis_kelamin, 'title' => 'Jenis Kelamin'],
                                    ['icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z', 'label' => $santri->rayon, 'title' => 'Rayon']
                                    ];
                                    @endphp

                                    <div class="flex flex-wrap gap-6">
                                        @foreach($infoItems as $item)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                                            </svg>
                                            <span class="font-medium text-gray-700">{{ $item['label'] }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Wali Kelas --}}
                                @if($namaWaliKelas)
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 text-green-800 text-sm rounded-full border border-green-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="font-medium">Wali Kelas: {{ $namaWaliKelas }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    {{-- Kode Registrasi Wali --}}
                    <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Kode Registrasi Wali</h3>
                                <p class="text-xs text-gray-500 mt-1">Bagikan kode ini kepada wali santri untuk akses profil.</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                @if ($santri->kode_registrasi_wali)
                                <span class="px-4 py-2 bg-white text-gray-800 font-mono text-sm rounded-lg border border-gray-300 shadow-sm">
                                    {{ $santri->kode_registrasi_wali }}
                                </span>
                                <form action="{{ route('santri.profil.generate_wali_code', $santri) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membuat kode baru? Kode lama akan hangus.')" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 transition-colors whitespace-nowrap flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Buat Ulang
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('santri.profil.generate_wali_code', $santri) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        Generate Kode
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            {{-- Grid Konten Utama --}}
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Kolom Kiri: Riwayat Penyakit & Perizinan --}}
                <div class="space-y-6">
                    {{-- Kartu Riwayat Penyakit --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Riwayat Penyakit
                            </h2>
                            @can('create', [App\Models\RiwayatPenyakit::class, $santri])
                            <a href="{{ route('kesehatan.riwayat_penyakit.create', $santri) }}" class="flex items-center gap-2 px-3 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah
                            </a>
                            @endcan
                        </div>

                        <div class="p-0">
                            {{-- Desktop Table --}}
                            <div class="hidden sm:block overflow-x-auto">
                                <table class="w-full min-w-max">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Penyakit</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dicatat</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($santri->riwayatPenyakits as $penyakit)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900">{{ $penyakit->nama_penyakit }}</div>
                                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($penyakit->keterangan ?? '', 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $penyakit->tanggal_diagnosis->format('d M Y') }}</td>
                                            <td class="px-6 py-4">
                                                @php
                                                $statusColors = [
                                                'aktif' => 'bg-red-100 text-red-800 border-red-200',
                                                'sembuh' => 'bg-green-100 text-green-800 border-green-200',
                                                'kronis' => 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                                ];
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColors[$penyakit->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                                    {{ ucfirst($penyakit->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $penyakit->pencatat->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-sm font-medium space-x-2 whitespace-nowrap">
                                                @can('update', $penyakit)
                                                <a href="{{ route('kesehatan.riwayat_penyakit.edit', $penyakit) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                                @endcan
                                                @can('delete', $penyakit)
                                                <form action="{{ route('kesehatan.riwayat_penyakit.destroy', $penyakit) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus riwayat penyakit ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                                @endcan
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <p>Tidak ada riwayat penyakit</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Mobile List --}}
                            <ul class="sm:hidden divide-y divide-gray-200">
                                @forelse ($santri->riwayatPenyakits as $penyakit)
                                <li class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 text-sm">{{ $penyakit->nama_penyakit }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">{{ $penyakit->tanggal_diagnosis->format('d M Y') }}</p>
                                        </div>
                                        @php
                                        $statusColors = [
                                        'aktif' => 'bg-red-100 text-red-800 border-red-200',
                                        'sembuh' => 'bg-green-100 text-green-800 border-green-200',
                                        'kronis' => 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                        ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border ml-2 flex-shrink-0 {{ $statusColors[$penyakit->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                            {{ ucfirst($penyakit->status) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($penyakit->keterangan ?? '', 80) }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Dicatat: {{ $penyakit->pencatat->name ?? 'N/A' }}</span>
                                        <div class="flex gap-3">
                                            @can('update', $penyakit)
                                            <a href="{{ route('kesehatan.riwayat_penyakit.edit', $penyakit) }}" class="text-xs font-medium text-blue-600 hover:text-blue-900">Edit</a>
                                            @endcan
                                            @can('delete', $penyakit)
                                            <form action="{{ route('kesehatan.riwayat_penyakit.destroy', $penyakit) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus riwayat penyakit ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                            @endcan
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="p-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-sm text-gray-500">Tidak ada riwayat penyakit</p>
                                    </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    {{-- Kartu Riwayat Perizinan --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Riwayat Perizinan
                            </h2>
                            @can('create', [App\Models\Perizinan::class, $santri])
                            <a href="{{ route('perizinan.create', $santri) }}" class="flex items-center gap-2 px-3 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah
                            </a>
                            @endcan
                        </div>

                        <div class="p-0">
                            {{-- Desktop Table --}}
                            <div class="hidden sm:block overflow-x-auto">
                                <table class="w-full min-w-max">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Izin</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Periode</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($santri->perizinans as $izin)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900">{{ $izin->jenis_izin }}</div>
                                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($izin->keterangan ?? '', 40) }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $izin->tanggal_mulai->format('d M Y') }}
                                                @if($izin->tanggal_akhir) <span class="text-gray-400">–</span> {{ $izin->tanggal_akhir->format('d M Y') }} @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $izin->status == 'aktif' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 'bg-gray-100 text-gray-800 border-gray-200' }} border">
                                                    {{ ucfirst($izin->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <p>Tidak ada riwayat perizinan</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Mobile List --}}
                            <ul class="sm:hidden divide-y divide-gray-200">
                                @forelse ($santri->perizinans as $izin)
                                <li class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 text-sm">{{ $izin->jenis_izin }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $izin->tanggal_mulai->format('d M Y') }}@if($izin->tanggal_akhir) <span class="text-gray-400">–</span> {{ $izin->tanggal_akhir->format('d M Y') }}@endif
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ml-2 flex-shrink-0 {{ $izin->status == 'aktif' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 'bg-gray-100 text-gray-800 border-gray-200' }} border">
                                            {{ ucfirst($izin->status) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($izin->keterangan ?? '', 60) }}</p>
                                </li>
                                @empty
                                <li class="p-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-sm text-gray-500">Tidak ada riwayat perizinan</p>
                                    </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Pelanggaran & Catatan --}}
                <div class="space-y-6">
                    {{-- Kartu Riwayat Pelanggaran --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Riwayat Pelanggaran
                            </h2>
                            @can('create', [App\Models\Pelanggaran::class, $santri])
                            <a href="{{ route('pelanggaran.create', $santri) }}" class="flex items-center gap-2 px-3 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah
                            </a>
                            @endcan
                        </div>

                        <div class="p-0">
                            {{-- Desktop Table --}}
                            <div class="hidden sm:block overflow-x-auto">
                                <table class="w-full min-w-max">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggaran</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dicatat</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($santri->pelanggarans as $pelanggaran)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900">{{ $pelanggaran->jenis_pelanggaran }}</div>
                                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($pelanggaran->keterangan ?? '', 40) }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $pelanggaran->tanggal_kejadian->format('d M Y') }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $pelanggaran->dicatat_oleh }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <p>Tidak ada riwayat pelanggaran</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Mobile List --}}
                            <ul class="sm:hidden divide-y divide-gray-200">
                                @forelse ($santri->pelanggarans as $pelanggaran)
                                <li class="p-4 hover:bg-gray-50 transition-colors">
                                    <h4 class="font-semibold text-gray-900 text-sm mb-2">{{ $pelanggaran->jenis_pelanggaran }}</h4>
                                    <p class="text-xs text-gray-500 mb-2">{{ $pelanggaran->tanggal_kejadian->format('d M Y') }} • {{ $pelanggaran->dicatat_oleh }}</p>
                                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($pelanggaran->keterangan ?? '', 60) }}</p>
                                </li>
                                @empty
                                <li class="p-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-sm text-gray-500">Tidak ada riwayat pelanggaran</p>
                                    </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    {{-- Kartu Catatan Pembelajaran --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Catatan Pembelajaran
                            </h2>
                            @can('create', [App\Models\CatatanHarian::class, $santri])
                            <a href="{{ route('keasramaan.catatan.create', $santri) }}" class="flex items-center gap-2 px-3 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah
                            </a>
                            @endcan
                        </div>

                        <div class="p-0">
                            {{-- Desktop Table --}}
                            <div class="hidden sm:block overflow-x-auto">
                                <table class="w-full min-w-max">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dicatat</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($santri->catatanHarians as $catatan)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $catatan->tanggal->format('d M Y') }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700 max-w-md">{{ Str::limit($catatan->catatan ?? '', 60) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $catatan->pencatat->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-sm font-medium space-x-2 whitespace-nowrap">
                                                @can('update', $catatan)
                                                <a href="{{ route('keasramaan.catatan.edit', $catatan) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                                @endcan
                                                @can('delete', $catatan)
                                                <form action="{{ route('keasramaan.catatan.destroy', $catatan) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                                @endcan
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <p>Tidak ada catatan harian</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Mobile List --}}
                            <ul class="sm:hidden divide-y divide-gray-200">
                                @forelse ($santri->catatanHarians as $catatan)
                                <li class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-semibold text-gray-900 text-sm">{{ $catatan->tanggal->format('d M Y') }}</h4>
                                        <span class="text-xs text-gray-500">{{ $catatan->pencatat->name ?? 'N/A' }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-3">{{ $catatan->catatan ?? '' }}</p>
                                    <div class="flex gap-3">
                                        @can('update', $catatan)
                                        <a href="{{ route('keasramaan.catatan.edit', $catatan) }}" class="text-xs font-medium text-blue-600 hover:text-blue-900">Edit</a>
                                        @endcan
                                        @can('delete', $catatan)
                                        <form action="{{ route('keasramaan.catatan.destroy', $catatan) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                        @endcan
                                    </div>
                                </li>
                                @empty
                                <li class="p-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-sm text-gray-500">Tidak ada catatan harian</p>
                                    </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kartu Full Width: Prestasi & Rapor --}}
            <div class="space-y-6">
                {{-- Kartu Prestasi --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            Prestasi
                        </h2>
                        @can('create', [App\Models\Prestasi::class, $santri])
                        <a href="{{ route('keasramaan.prestasi.create', $santri) }}" class="flex items-center gap-2 px-3 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah
                        </a>
                        @endcan
                    </div>

                    <div class="p-0">
                        {{-- Desktop Table --}}
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="w-full min-w-max">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Prestasi</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Poin</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dicatat</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($santri->prestasis as $prestasi)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $prestasi->tanggal->format('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $prestasi->nama_prestasi }}</div>
                                            <div class="text-sm text-gray-500 mt-1">{{ Str::limit($prestasi->keterangan ?? '', 50) }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                +{{ $prestasi->poin }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $prestasi->pencatat->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm font-medium space-x-2 whitespace-nowrap">
                                            @can('update', $prestasi)
                                            <a href="{{ route('keasramaan.prestasi.edit', $prestasi) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                            @endcan
                                            @can('delete', $prestasi)
                                            <form action="{{ route('keasramaan.prestasi.destroy', $prestasi) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                </svg>
                                                <p>Tidak ada catatan prestasi</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile List --}}
                        <ul class="sm:hidden divide-y divide-gray-200">
                            @forelse ($santri->prestasis as $prestasi)
                            <li class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-900 text-sm flex-1">{{ $prestasi->nama_prestasi }}</h4>
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200 ml-2 flex-shrink-0">
                                        +{{ $prestasi->poin }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mb-2">{{ $prestasi->tanggal->format('d M Y') }} • {{ $prestasi->pencatat->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-700 mb-3">{{ Str::limit($prestasi->keterangan ?? '', 80) }}</p>
                                <div class="flex gap-3">
                                    @can('update', $prestasi)
                                    <a href="{{ route('keasramaan.prestasi.edit', $prestasi) }}" class="text-xs font-medium text-blue-600 hover:text-blue-900">Edit</a>
                                    @endcan
                                    @can('delete', $prestasi)
                                    <form action="{{ route('keasramaan.prestasi.destroy', $prestasi) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                    @endcan
                                </div>
                            </li>
                            @empty
                            <li class="p-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                    <p class="text-sm text-gray-500">Tidak ada catatan prestasi</p>
                                </div>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Kartu Rapor Akademik --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Rapor Akademik
                        </h2>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                {{ $santri->kelas->nama_kelas ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
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
                                    if (avg === '-' || avg <= 0) return { text: '-', class: 'bg-gray-100 text-gray-800 border-gray-200' };
                                    if (avg >= 90) return { text: 'Mumtaz', class: 'bg-blue-100 text-blue-800 border-blue-200' };
                                    if (avg >= 80) return { text: 'Jayyid Jiddan', class: 'bg-green-100 text-green-800 border-green-200' };
                                    if (avg >= 70) return { text: 'Jayyid', class: 'bg-yellow-100 text-yellow-800 border-yellow-200' };
                                    if (avg >= 60) return { text: 'Maqbul', class: 'bg-orange-100 text-orange-800 border-orange-200' };
                                    return { text: 'Rasib', class: 'bg-red-100 text-red-800 border-red-200' };
                                }
                            }">
                            {{-- Filter Controls --}}
                            <div class="mb-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2 grid grid-cols-1 lg:grid-cols-2 gap-4">
                                        <div>
                                            <label for="tahun_ajaran_filter" class="block text-xs font-medium text-gray-500 mb-1">Tahun Ajaran</label>
                                            <select x-model="selectedTahun" @change="selectedSemester = semesterList.includes('Semester Ganjil') ? 'Semester Ganjil' : (semesterList[0] || '')" id="tahun_ajaran_filter" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
                                                <template x-for="tahun in tahunAjaranList" :key="tahun">
                                                    <option :value="tahun" x-text="tahun"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="semester_filter" class="block text-xs font-medium text-gray-500 mb-1">Semester</label>
                                            <select x-model="selectedSemester" :disabled="!selectedTahun" id="semester_filter" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed">
                                                <template x-for="semester in semesterList" :key="semester">
                                                    <option :value="semester" x-text="semester"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Export Buttons --}}
                                    <div class="flex flex-wrap gap-3 items-end justify-end" x-show="selectedTahun && selectedSemester">
                                        {{-- Excel Export --}}
                                        <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Excel
                                        </a>

                                        {{-- PDF Dropdown --}}
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                PDF
                                                <svg x-show="!open" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 top-full mt-2 w-56 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-10">
                                                <div class="py-1">
                                                    <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export-pdf') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                                        Rapor Lengkap
                                                    </a>

                                                    <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export-pdf') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester + '&jenis_penilaian=nilai_tugas'" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                                        Nilai Tugas Saja
                                                    </a>

                                                    <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export-pdf') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester + '&jenis_penilaian=nilai_uts'" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                                        Nilai UTS Saja
                                                    </a>

                                                    <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export-pdf') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester + '&jenis_penilaian=nilai_uas'" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                                        Nilai UAS Saja
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Summary Stats --}}
                                <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3" x-show="selectedTahun && selectedSemester && selectedRapor.length > 0">
                                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                                        <div class="text-xs text-blue-600 font-medium">Jumlah Mata Pelajaran</div>
                                        <div class="text-lg font-bold text-blue-800 mt-1" x-text="selectedRapor.length"></div>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                                        <div class="text-xs text-green-600 font-medium">Rata-rata Nilai</div>
                                        <div class="text-lg font-bold text-green-800 mt-1" x-text="(() => {
                                    const nilai = selectedRapor.map(n => calculateAverage(n.nilai_tugas, n.nilai_uts, n.nilai_uas)).filter(n => n !== '-' && !isNaN(n));
                                    if (nilai.length === 0) return '-';
                                    return Math.round(nilai.reduce((a, b) => a + b, 0) / nilai.length);
                                })()"></div>
                                    </div>
                                    <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                        <div class="text-xs text-yellow-600 font-medium">Predikat Terbanyak</div>
                                        <div class="text-lg font-bold text-yellow-800 mt-1" x-text="(() => {
                                    const predikats = selectedRapor.map(n => getPredicate(calculateAverage(n.nilai_tugas, n.nilai_uts, n.nilai_uas)).text);
                                    const counts = {};
                                    predikats.forEach(p => counts[p] = (counts[p] || 0) + 1);
                                    return Object.keys(counts).reduce((a, b) => counts[a] > counts[b] ? a : b, '');
                                })()"></div>
                                    </div>
                                    <div class="bg-purple-50 p-3 rounded-lg border border-purple-200">
                                        <div class="text-xs text-purple-600 font-medium">Nilai Tertinggi</div>
                                        <div class="text-lg font-bold text-purple-800 mt-1" x-text="(() => {
                                    const nilai = selectedRapor.map(n => calculateAverage(n.nilai_tugas, n.nilai_uts, n.nilai_uas)).filter(n => n !== '-' && !isNaN(n));
                                    if (nilai.length === 0) return '-';
                                    return Math.max(...nilai);
                                })()"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Desktop Table --}}
                            <div class="hidden sm:block overflow-x-auto mt-6">
                                <table class="w-full min-w-max divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tugas</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">UTS</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">UAS</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Akhir</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Predikat</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template x-if="selectedRapor.length > 0">
                                            <template x-for="nilai in selectedRapor" :key="nilai.id">
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-6 py-4">
                                                        <div class="font-medium text-gray-900" x-text="nilai.mata_pelajaran.nama_pelajaran"></div>
                                                    </td>
                                                    <td class="px-6 py-4 text-center text-sm text-gray-500" x-text="nilai.nilai_tugas || '-'"></td>
                                                    <td class="px-6 py-4 text-center text-sm text-gray-500" x-text="nilai.nilai_uts || '-'"></td>
                                                    <td class="px-6 py-4 text-center text-sm text-gray-500" x-text="nilai.nilai_uas || '-'"></td>
                                                    <td class="px-6 py-4 text-center">
                                                        <span class="font-semibold text-gray-900" x-text="calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)"></span>
                                                    </td>
                                                    <td class="px-6 py-4 text-center">
                                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium border" :class="getPredicate(calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)).class" x-text="getPredicate(calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)).text">
                                                        </span>
                                                    </td>
                                                </tr>
                                            </template>
                                        </template>
                                        <template x-if="selectedRapor.length === 0">
                                            <tr>
                                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                                    <div class="flex flex-col items-center">
                                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                        </svg>
                                                        <p>Tidak ada data nilai untuk periode ini</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Mobile Cards --}}
                            <div class="sm:hidden space-y-4 mt-6" x-show="selectedTahun && selectedSemester">
                                <template x-if="selectedRapor.length > 0">
                                    <template x-for="nilai in selectedRapor" :key="nilai.id">
                                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                            <h4 class="font-semibold text-gray-900 mb-3 text-sm" x-text="nilai.mata_pelajaran.nama_pelajaran"></h4>
                                            <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 text-xs">Tugas:</span>
                                                    <span class="font-medium" x-text="nilai.nilai_tugas || '-'"></span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 text-xs">UTS:</span>
                                                    <span class="font-medium" x-text="nilai.nilai_uts || '-'"></span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 text-xs">UAS:</span>
                                                    <span class="font-medium" x-text="nilai.nilai_uas || '-'"></span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 text-xs">Akhir:</span>
                                                    <span class="font-bold" x-text="calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)"></span>
                                                </div>
                                            </div>
                                            <div class="flex justify-center">
                                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium border" :class="getPredicate(calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)).class" x-text="getPredicate(calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)).text">
                                                </span>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                                <template x-if="selectedRapor.length === 0">
                                    <div class="text-center py-8">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <p class="text-sm text-gray-500">Tidak ada data nilai untuk periode ini</p>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- No Selection Message --}}
                            <div x-show="!selectedTahun || !selectedSemester" class="text-center py-12">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-sm text-gray-500">Pilih tahun ajaran dan semester untuk melihat rapor akademik</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
