<x-app-layout>
    {{-- [TAMBAHAN] Alpine.js dibutuhkan untuk interaktivitas --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endpush

    {{-- [TAMBAHAN] Style untuk menyembunyikan elemen Alpine.js sebelum dimuat --}}
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Style untuk tombol kembali */
        .back-button {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background-color: #6b7280;
            color: white;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .back-button:hover {
            background-color: #4b5563;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .back-button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.5);
        }

        @media (max-width: 640px) {
            .header-actions {
                flex-direction: column;
                gap: 0.75rem;
                align-items: stretch;
            }

            .back-button {
                justify-content: center;
                width: 100%;
            }
        }

    </style>

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex-1">
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                            Laporan Rekapan Kehadiran
                        </h1>
                        <p class="mt-2 text-slate-600 text-sm sm:text-base">
                            Lihat rekapan kehadiran santri per periode (Harian, Mingguan, Bulanan)
                        </p>
                    </div>

                    {{-- Tombol Kembali - Hanya untuk admin dan pengajaran --}}
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'pengajaran'))
                    <div class="header-actions flex items-center">
                        <a href="{{ route('pengajaran.rekapan-harian.index') }}" class="back-button">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Form Filter --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4 sm:gap-6 items-end">
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="jenis_periode" class="block text-sm font-medium text-gray-700">Jenis Periode</label>
                        <select name="jenis_periode" id="jenis_periode" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            <option value="harian" {{ $jenisPeriode == 'harian' ? 'selected' : '' }}>Harian</option>
                            <option value="mingguan" {{ $jenisPeriode == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                            <option value="bulanan" {{ $jenisPeriode == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        </select>
                    </div>

                    {{-- Field Harian --}}
                    <div id="periode-harian" class="periode-field {{ $jenisPeriode != 'harian' ? 'hidden' : '' }}">
                        <label for="periode_harian" class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" name="periode" id="periode_harian" value="{{ $jenisPeriode == 'harian' ? request('periode', now()->format('Y-m-d')) : '' }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" max="{{ now()->format('Y-m-d') }}">
                    </div>

                    {{-- Field Mingguan --}}
                    <div id="periode-mingguan" class="periode-field {{ $jenisPeriode != 'mingguan' ? 'hidden' : '' }}">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="periode_mingguan_start" class="block text-xs font-medium text-gray-700">Dari Tanggal</label>
                                <input type="date" name="periode_start" id="periode_mingguan_start" value="{{ $jenisPeriode == 'mingguan' ? request('periode_start', now()->startOfWeek()->format('Y-m-d')) : '' }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-xs" max="{{ now()->format('Y-m-d') }}">
                            </div>
                            <div>
                                <label for="periode_mingguan_end" class="block text-xs font-medium text-gray-700">Sampai Tanggal</label>
                                <input type="date" name="periode_end" id="periode_mingguan_end" value="{{ $jenisPeriode == 'mingguan' ? request('periode_end', now()->endOfWeek()->format('Y-m-d')) : '' }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-xs" max="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Field Bulanan --}}
                    <div id="periode-bulanan" class="periode-field {{ $jenisPeriode != 'bulanan' ? 'hidden' : '' }}">
                        <label for="periode_bulanan" class="block text-sm font-medium text-gray-700">Bulan</label>
                        <input type="month" name="periode_bulan" id="periode_bulanan" value="{{ $jenisPeriode == 'bulanan' ? request('periode', now()->format('Y-m')) : '' }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" max="{{ now()->format('Y-m') }}">
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Tampilkan Laporan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tombol Export --}}
            @if(isset($summary))
            <div class="flex justify-end mb-6">
                <button onclick="openExportModal()" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Laporan
                </button>
            </div>
            @endif

            @if(isset($summary))
            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $summary['total_santri'] }}</div>
                    <div class="text-sm text-slate-600 mt-1">Total Santri</div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ number_format($summary['rata_rata_presentase'], 1) }}%</div>
                    <div class="text-sm text-slate-600 mt-1">Rata-rata Kehadiran</div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 text-center">
                    <div class="text-2xl font-bold text-orange-600">{{ round($summary['total_hari']) }}</div>
                    <div class="text-sm text-slate-600 mt-1">
                        {{ $summary['jenis_periode'] == 'harian' ? 'Hari' : ($summary['jenis_periode'] == 'mingguan' ? 'Hari' : 'Hari') }}
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $rekapData->count() }}</div>
                    <div class="text-sm text-slate-600 mt-1">Santri Tercatat</div>
                </div>
            </div>

            {{-- Info Periode --}}
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-blue-800">{{ $summary['kelas'] }} - {{ $summary['periode_label'] }}</h3>
                        <p class="text-sm text-blue-600">
                            @if($summary['jenis_periode'] == 'mingguan')
                            Periode: {{ $summary['tanggal_mulai'] }} s/d {{ $summary['tanggal_selesai'] }} |
                            @else
                            Periode: {{ ucfirst($summary['jenis_periode']) }} |
                            @endif
                            Santri: {{ $summary['total_santri'] }} |
                            Hari: {{ round($summary['total_hari']) }} |
                            Rata-rata: {{ number_format($summary['rata_rata_presentase'], 1) }}%
                        </p>
                    </div>
                </div>
            </div>

            {{-- Detail Rekapan per Santri --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Detail Rekapan per Santri</h3>
                        <div class="mt-2 sm:mt-0 text-sm text-slate-600">
                            {{ $rekapData->count() }} santri
                        </div>
                    </div>
                </div>

                {{-- Table for Desktop --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Nama Santri
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Total Hadir
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Total Jam
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Total Hari
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Presentase
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse($rekapData as $data)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $data['santri']->nama }}</div>
                                    <div class="text-sm text-gray-500">NIS: {{ $data['santri']->nis }}</div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        {{ $data['total_hadir'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    {{ $data['total_jam'] }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    {{ $data['total_hari'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                {{ $data['presentase'] >= 90 ? 'bg-green-100 text-green-800' : 
                                                   ($data['presentase'] >= 75 ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-red-100 text-red-800') }}">
                                        {{ $data['presentase'] }}%
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada data rekapan untuk periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden grid grid-cols-1 gap-4 p-4">
                    @forelse($rekapData as $data)
                    <div class="bg-white rounded-xl shadow-lg border border-slate-200 flex flex-col">
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-lg text-slate-800">{{ $data['santri']->nama }}</h3>
                                    <p class="text-xs text-slate-500">NIS: {{ $data['santri']->nis }}</p>
                                </div>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-2 text-sm">
                                <div class="bg-slate-100 p-2 rounded-lg text-center">
                                    <div class="text-xs text-slate-500">Total Hadir</div>
                                    <div class="font-bold text-green-600">{{ $data['total_hadir'] }}</div>
                                </div>
                                <div class="bg-slate-100 p-2 rounded-lg text-center">
                                    <div class="text-xs text-slate-500">Total Jam</div>
                                    <div class="font-bold text-slate-800">{{ $data['total_jam'] }}</div>
                                </div>
                                <div class="bg-slate-100 p-2 rounded-lg text-center">
                                    <div class="text-xs text-slate-500">Total Hari</div>
                                    <div class="font-bold text-slate-800">{{ $data['total_hari'] }}</div>
                                </div>
                                <div class="bg-slate-100 p-2 rounded-lg text-center">
                                    <div class="text-xs text-slate-500">Presentase</div>
                                    <div class="font-bold 
                                                {{ $data['presentase'] >= 90 ? 'text-green-600' : 
                                                   ($data['presentase'] >= 75 ? 'text-yellow-600' : 
                                                   'text-red-600') }}">
                                        {{ $data['presentase'] }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-1 py-12 text-center text-slate-500">
                        Tidak ada data rekapan untuk periode ini.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Legend --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 mt-6">
                <h4 class="font-semibold text-gray-900 mb-3">Keterangan Presentase:</h4>
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                        <span>90% - 100% (Sangat Baik)</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                        <span>75% - 89% (Baik)</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                        <span>0% - 74% (Perlu Perhatian)</span>
                    </div>
                </div>
            </div>
            @elseif(request()->filled(['kelas_id']))
            {{-- Empty State --}}
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-yellow-800">Data Tidak Ditemukan</h3>
                <p class="mt-2 text-yellow-700">
                    Tidak ada data rekapan untuk periode yang dipilih.
                </p>
            </div>
            @else
            {{-- Initial State --}}
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-slate-600">Pilih Filter untuk Melihat Laporan</h3>
                <p class="mt-2 text-slate-500">
                    Silakan pilih kelas dan periode terlebih dahulu untuk menampilkan laporan rekapan.
                </p>
            </div>
            @endif
        </div>
    </div>

    {{-- Modal Export --}}
    <div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Laporan</h3>

            <form id="exportForm" action="{{ route('pengajaran.rekapan-harian.export') }}" method="POST">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ request('kelas_id') }}">
                <input type="hidden" name="jenis_periode" value="{{ $jenisPeriode }}">
                <input type="hidden" name="periode" id="export_periode" value="{{ request('periode') }}">
                <input type="hidden" name="periode_start" id="export_periode_start" value="{{ request('periode_start') }}">
                <input type="hidden" name="periode_end" id="export_periode_end" value="{{ request('periode_end') }}">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Export</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="jenis_export" value="excel" class="text-red-600 focus:ring-red-500" checked>
                                <span class="ml-2">Excel</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="jenis_export" value="pdf" class="text-red-600 focus:ring-red-500">
                                <span class="ml-2">PDF</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rentang Data</label>
                        <div class="space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="export_periode" value="current" class="text-red-600 focus:ring-red-500" checked>
                                <span class="ml-2">Data yang Ditampilkan ({{ $summary['periode_label'] ?? 'Current' }})</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="export_periode" value="keseluruhan" class="text-red-600 focus:ring-red-500">
                                <span class="ml-2">Keseluruhan Data</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeExportModal()" class="rounded-md bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600">
                        Export
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsi untuk menampilkan field periode berdasarkan jenis periode
        function togglePeriodeFields() {
            const jenisPeriode = document.getElementById('jenis_periode').value;

            // Sembunyikan semua field periode
            document.querySelectorAll('.periode-field').forEach(function(field) {
                field.classList.add('hidden');
            });

            // Tampilkan field yang sesuai
            const activeField = document.getElementById('periode-' + jenisPeriode);
            if (activeField) {
                activeField.classList.remove('hidden');
            }

            // Hapus required attribute dari semua input periode terlebih dahulu
            document.querySelectorAll('[name="periode"], [name="periode_start"], [name="periode_end"]').forEach(function(input) {
                input.removeAttribute('required');
            });

            // Tambahkan required hanya pada input yang terlihat
            if (jenisPeriode === 'harian') {
                const harianInput = document.querySelector('#periode-harian [name="periode"]');
                if (harianInput) harianInput.setAttribute('required', 'required');
            } else if (jenisPeriode === 'mingguan') {
                const startInput = document.querySelector('#periode-mingguan [name="periode_start"]');
                const endInput = document.querySelector('#periode-mingguan [name="periode_end"]');
                if (startInput) startInput.setAttribute('required', 'required');
                if (endInput) endInput.setAttribute('required', 'required');
            } else if (jenisPeriode === 'bulanan') {
                const bulananInput = document.querySelector('#periode-bulanan [name="periode"]');
                if (bulananInput) bulananInput.setAttribute('required', 'required');
            }
        }

        // Validasi tanggal untuk periode mingguan
        function setupMingguanValidation() {
            const startDateInput = document.getElementById('periode_mingguan_start');
            const endDateInput = document.getElementById('periode_mingguan_end');

            if (startDateInput && endDateInput) {
                startDateInput.addEventListener('change', function() {
                    const startDate = new Date(this.value);
                    const endDate = new Date(endDateInput.value);

                    if (endDateInput.value && endDate < startDate) {
                        endDateInput.value = this.value;
                    }
                });

                endDateInput.addEventListener('change', function() {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(this.value);

                    if (startDateInput.value && endDate < startDate) {
                        alert('Tanggal akhir tidak boleh sebelum tanggal mulai');
                        this.value = startDateInput.value;
                    }
                });
            }
        }

        // Fungsi untuk membuka modal export
        function openExportModal() {
            const jenisPeriode = document.getElementById('jenis_periode').value;

            // Set nilai untuk export berdasarkan jenis periode
            if (jenisPeriode === 'harian') {
                const harianInput = document.getElementById('periode_harian');
                if (harianInput && harianInput.value) {
                    document.getElementById('export_periode').value = harianInput.value;
                }
            } else if (jenisPeriode === 'mingguan') {
                const startInput = document.getElementById('periode_mingguan_start');
                const endInput = document.getElementById('periode_mingguan_end');
                if (startInput && startInput.value) {
                    document.getElementById('export_periode_start').value = startInput.value;
                }
                if (endInput && endInput.value) {
                    document.getElementById('export_periode_end').value = endInput.value;
                }
            } else if (jenisPeriode === 'bulanan') {
                const bulananInput = document.getElementById('periode_bulanan');
                if (bulananInput && bulananInput.value) {
                    document.getElementById('export_periode').value = bulananInput.value;
                }
            }

            document.getElementById('exportModal').classList.remove('hidden');
        }

        // Fungsi untuk menutup modal export
        function closeExportModal() {
            document.getElementById('exportModal').classList.add('hidden');
        }

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Setup event listener untuk jenis periode
            const jenisPeriodeSelect = document.getElementById('jenis_periode');
            if (jenisPeriodeSelect) {
                jenisPeriodeSelect.addEventListener('change', togglePeriodeFields);
            }

            // Inisialisasi field periode
            togglePeriodeFields();

            // Setup validasi periode mingguan
            setupMingguanValidation();

            // Setup export form submission
            const exportForm = document.getElementById('exportForm');
            if (exportForm) {
                exportForm.addEventListener('submit', function(e) {
                    const exportPeriodeRadios = document.querySelectorAll('input[name="export_periode"]');
                    let exportPeriodeValue = 'current';

                    // Cari radio button yang dicentang
                    for (let i = 0; i < exportPeriodeRadios.length; i++) {
                        if (exportPeriodeRadios[i].checked) {
                            exportPeriodeValue = exportPeriodeRadios[i].value;
                            break;
                        }
                    }

                    const jenisPeriodeInput = document.querySelector('input[name="jenis_periode"]');

                    if (exportPeriodeValue === 'keseluruhan') {
                        if (jenisPeriodeInput) {
                            jenisPeriodeInput.value = 'keseluruhan';
                        }
                        document.getElementById('export_periode').value = '';
                        document.getElementById('export_periode_start').value = '';
                        document.getElementById('export_periode_end').value = '';
                    }
                });
            }

            // Close modal when clicking outside
            const exportModal = document.getElementById('exportModal');
            if (exportModal) {
                exportModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeExportModal();
                    }
                });
            }

            // Tambahkan event listener untuk tombol escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeExportModal();
                }
            });
        });

    </script>
</x-app-layout>
