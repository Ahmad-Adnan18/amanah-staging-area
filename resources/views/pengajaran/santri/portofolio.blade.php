<x-app-layout>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endpush

    <style>
        [x-cloak] {
            display: none !important;
        }

    </style>

    <div class="bg-slate-50 min-h-screen overflow-x-hidden">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Header Portofolio -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0">
                                <img class="h-16 w-16 rounded-full object-cover ring-4 ring-red-100" src="{{ $santri->foto ? Storage::url($santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($santri->nama).'&background=FBBF24&color=fff&size=128' }}" alt="Foto Santri">
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $santri->nama }}</h1>
                                <p class="text-slate-600 mt-1">Portofolio Lengkap Santri {{ $santri->nis }}</p>
                                <p class="text-sm text-slate-500 mt-1">Kelas: {{ $santri->kelas->nama_kelas ?? 'N/A' }} | Rayon: {{ $santri->rayon }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            <a href="{{ route('santri.portofolio.list') }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                Daftar Santri
                            </a>
                            <a href="{{ route('santri.profil.portofolio.export-pdf', $santri) }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Export PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Cepat -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-blue-100">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Total Izin</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $santri->perizinans->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-red-100">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Pelanggaran</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $santri->pelanggarans->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-green-100">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Prestasi</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $santri->prestasis->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-yellow-100">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Riwayat Penyakit</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $santri->riwayatPenyakits->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs untuk berbagai bagian portofolio -->
            <div x-data="{ activeTab: 'perizinan' }" class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <!-- Tab Headers -->
                <div class="border-b border-slate-200">
                    <!-- Mobile Select -->
                    <div class="md:hidden p-4">
                        <select x-model="activeTab" class="w-full rounded-md border border-slate-300 text-sm font-medium text-slate-700">
                            <option value="perizinan">Perizinan ({{ $santri->perizinans->count() }})</option>
                            <option value="pelanggaran">Pelanggaran ({{ $santri->pelanggarans->count() }})</option>
                            <option value="prestasi">Prestasi ({{ $santri->prestasis->count() }})</option>
                            <option value="penyakit">Riwayat Penyakit ({{ $santri->riwayatPenyakits->count() }})</option>
                            <option value="catatan">Catatan Harian ({{ $santri->catatanHarians->count() }})</option>
                        </select>
                    </div>
                    <!-- Desktop Nav -->
                    <nav class="hidden md:flex -mb-px space-x-2">
                        <button @click="activeTab = 'perizinan'" :class="{ 'border-red-500 text-red-600': activeTab === 'perizinan', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'perizinan' }" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            Perizinan ({{ $santri->perizinans->count() }})
                        </button>
                        <button @click="activeTab = 'pelanggaran'" :class="{ 'border-red-500 text-red-600': activeTab === 'pelanggaran', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'pelanggaran' }" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            Pelanggaran ({{ $santri->pelanggarans->count() }})
                        </button>
                        <button @click="activeTab = 'prestasi'" :class="{ 'border-red-500 text-red-600': activeTab === 'prestasi', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'prestasi' }" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            Prestasi ({{ $santri->prestasis->count() }})
                        </button>
                        <button @click="activeTab = 'penyakit'" :class="{ 'border-red-500 text-red-600': activeTab === 'penyakit', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'penyakit' }" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            Riwayat Penyakit ({{ $santri->riwayatPenyakits->count() }})
                        </button>
                        <button @click="activeTab = 'catatan'" :class="{ 'border-red-500 text-red-600': activeTab === 'catatan', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'catatan' }" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            Catatan Harian ({{ $santri->catatanHarians->count() }})
                        </button>
                    </nav>
                </div>

                <!-- Tab Contents -->
                <div class="p-6">
                    <!-- Tab Perizinan -->
                    <div x-show="activeTab === 'perizinan'">
                        @if($santri->perizinans->count() > 0)
                        <!-- Desktop Table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jenis Izin</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Keterangan</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach($santri->perizinans->sortByDesc('created_at') as $izin)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $izin->jenis_izin }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {{ $izin->tanggal_mulai->format('d M Y') }}
                                            @if($izin->tanggal_akhir && $izin->tanggal_akhir != $izin->tanggal_mulai)
                                            - {{ $izin->tanggal_akhir->format('d M Y') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-500">{{ Str::limit($izin->keterangan, 50) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $izin->status == 'aktif' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($izin->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Mobile Cards -->
                        <div class="md:hidden grid grid-cols-1 gap-4">
                            @foreach($santri->perizinans->sortByDesc('created_at') as $izin)
                            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-4">
                                <div class="text-sm font-medium text-slate-900">{{ $izin->jenis_izin }}</div>
                                <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                    <div class="bg-slate-100 p-2 rounded-lg">
                                        <div class="text-xs text-slate-500">Tanggal</div>
                                        <div class="font-medium text-slate-800">
                                            {{ $izin->tanggal_mulai->format('d M Y') }}
                                            @if($izin->tanggal_akhir && $izin->tanggal_akhir != $izin->tanggal_mulai)
                                            - {{ $izin->tanggal_akhir->format('d M Y') }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="bg-slate-100 p-2 rounded-lg">
                                        <div class="text-xs text-slate-500">Status</div>
                                        <div class="font-medium">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $izin->status == 'aktif' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($izin->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="text-xs text-slate-500">Keterangan</div>
                                    <div class="text-sm text-slate-800">{{ Str::limit($izin->keterangan, 50) }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 text-slate-500">
                            <p>Tidak ada data perizinan.</p>
                        </div>
                        @endif
                    </div>

                    <!-- Tab Pelanggaran -->
                    <div x-show="activeTab === 'pelanggaran'">
                        @if($santri->pelanggarans->count() > 0)
                        <!-- Desktop Table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jenis Pelanggaran</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Keterangan</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach($santri->pelanggarans->sortByDesc('tanggal_kejadian') as $pelanggaran)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $pelanggaran->jenis_pelanggaran }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $pelanggaran->tanggal_kejadian->format('d M Y') }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-500">{{ $pelanggaran->keterangan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $pelanggaran->dicatat_oleh }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Mobile Cards -->
                        <div class="md:hidden grid grid-cols-1 gap-4">
                            @foreach($santri->pelanggarans->sortByDesc('tanggal_kejadian') as $pelanggaran)
                            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-4">
                                <div class="text-sm font-medium text-slate-900">{{ $pelanggaran->jenis_pelanggaran }}</div>
                                <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                    <div class="bg-slate-100 p-2 rounded-lg">
                                        <div class="text-xs text-slate-500">Tanggal</div>
                                        <div class="font-medium text-slate-800">{{ $pelanggaran->tanggal_kejadian->format('d M Y') }}</div>
                                    </div>
                                    <div class="bg-slate-100 p-2 rounded-lg">
                                        <div class="text-xs text-slate-500">Dicatat Oleh</div>
                                        <div class="font-medium text-slate-800">{{ $pelanggaran->dicatat_oleh }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="text-xs text-slate-500">Keterangan</div>
                                    <div class="text-sm text-slate-800">{{ $pelanggaran->keterangan }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 text-slate-500">
                            <p>Tidak ada data pelanggaran.</p>
                        </div>
                        @endif
                    </div>

                    <!-- Tab Prestasi -->
                    <div x-show="activeTab === 'prestasi'">
                        @if($santri->prestasis->count() > 0)
                        <!-- Desktop Table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Prestasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Poin</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Keterangan</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach($santri->prestasis->sortByDesc('tanggal') as $prestasi)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $prestasi->nama_prestasi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $prestasi->tanggal->format('d M Y') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                +{{ $prestasi->poin }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-500">{{ $prestasi->keterangan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $prestasi->pencatat->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Mobile Cards -->
                        <div class="md:hidden grid grid-cols-1 gap-4">
                            @foreach($santri->prestasis->sortByDesc('tanggal') as $prestasi)
                            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-4">
                                <div class="text-sm font-medium text-slate-900">{{ $prestasi->nama_prestasi }}</div>
                                <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                    <div class="bg-slate-100 p-2 rounded-lg">
                                        <div class="text-xs text-slate-500">Tanggal</div>
                                        <div class="font-medium text-slate-800">{{ $prestasi->tanggal->format('d M Y') }}</div>
                                    </div>
                                    <div class="bg-slate-100 p-2 rounded-lg">
                                        <div class="text-xs text-slate-500">Poin</div>
                                        <div class="font-medium">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                +{{ $prestasi->poin }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="bg-slate-100 p-2 rounded-lg col-span-2">
                                        <div class="text-xs text-slate-500">Dicatat Oleh</div>
                                        <div class="font-medium text-slate-800">{{ $prestasi->pencatat->name }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="text-xs text-slate-500">Keterangan</div>
                                    <div class="text-sm text-slate-800">{{ $prestasi->keterangan }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 text-slate-500">
                            <p>Tidak ada data prestasi.</p>
                        </div>
                        @endif
                    </div>

                    <!-- Tab Riwayat Penyakit -->
                    <div x-show="activeTab === 'penyakit'">
                        @if($santri->riwayatPenyakits->count() > 0)
                        <!-- Desktop Table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Penyakit</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal Diagnosis</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Keterangan</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach($santri->riwayatPenyakits->sortByDesc('tanggal_diagnosis') as $penyakit)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $penyakit->nama_penyakit }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $penyakit->tanggal_diagnosis->format('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                            $statusColors = [
                                            'aktif' => 'bg-red-100 text-red-800',
                                            'sembuh' => 'bg-green-100 text-green-800',
                                            'kronis' => 'bg-yellow-100 text-yellow-800'
                                            ];
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$penyakit->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($penyakit->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-500">{{ Str::limit($penyakit->keterangan, 50) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $penyakit->pencatat->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Mobile Cards -->
                        <div class="md:hidden grid grid-cols-1 gap-4">
                            @foreach($santri->riwayatPenyakits->sortByDesc('tanggal_diagnosis') as $penyakit)
                            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-4">
                                <div class="text-sm font-medium text-slate-900">{{ $penyakit->nama_penyakit }}</div>
                                <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                    <div class="bg-slate-100 p-2 rounded-lg">
                                        <div class="text-xs text-slate-500">Tanggal Diagnosis</div>
                                        <div class="font-medium text-slate-800">{{ $penyakit->tanggal_diagnosis->format('d M Y') }}</div>
                                    </div>
                                    <div class="bg-slate-100 p-2 rounded-lg">
                                        <div class="text-xs text-slate-500">Status</div>
                                        <div class="font-medium">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$penyakit->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($penyakit->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="bg-slate-100 p-2 rounded-lg col-span-2">
                                        <div class="text-xs text-slate-500">Dicatat Oleh</div>
                                        <div class="font-medium text-slate-800">{{ $penyakit->pencatat->name }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="text-xs text-slate-500">Keterangan</div>
                                    <div class="text-sm text-slate-800">{{ Str::limit($penyakit->keterangan, 50) }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 text-slate-500">
                            <p>Tidak ada data riwayat penyakit.</p>
                        </div>
                        @endif
                    </div>

                    <!-- Tab Catatan Harian -->
                    <div x-show="activeTab === 'catatan'">
                        @if($santri->catatanHarians->count() > 0)
                        <!-- Desktop Table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Catatan</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach($santri->catatanHarians->sortByDesc('tanggal') as $catatan)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $catatan->tanggal->format('d M Y') }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-800">{{ $catatan->catatan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $catatan->pencatat->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Mobile Cards -->
                        <div class="md:hidden grid grid-cols-1 gap-4">
                            @foreach($santri->catatanHarians->sortByDesc('tanggal') as $catatan)
                            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-4">
                                <div class="text-sm font-medium text-slate-900">{{ $catatan->tanggal->format('d M Y') }}</div>
                                <div class="mt-2 grid grid-cols-1 gap-2 text-sm">
                                    <div class="bg-slate-100 p-2 rounded-lg">
                                        <div class="text-xs text-slate-500">Dicatat Oleh</div>
                                        <div class="font-medium text-slate-800">{{ $catatan->pencatat->name }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="text-xs text-slate-500">Catatan</div>
                                    <div class="text-sm text-slate-800">{{ $catatan->catatan }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 text-slate-500">
                            <p>Tidak ada data catatan harian.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
