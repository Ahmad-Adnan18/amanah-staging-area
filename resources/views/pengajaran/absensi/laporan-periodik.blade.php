<x-app-layout>
    <div class="bg-slate-50 min-h-screen" x-data="{
        selectedKelas: '{{ request('kelas_id') }}',
        scheduleList: [],
        isLoading: false,
        activeTab: 'table',
        scheduleSelect: null,
        fetchSchedules() {
            if (!this.selectedKelas) {
                this.scheduleList = [];
                if (this.scheduleSelect) this.scheduleSelect.clearOptions();
                return;
            }
            this.isLoading = true;
            fetch(`/pengajaran/absensi/get-schedules-by-kelas/${this.selectedKelas}`)
                .then(response => response.json())
                .then(data => {
                    this.scheduleList = data;
                    this.isLoading = false;
                    if (this.scheduleSelect) {
                        this.scheduleSelect.clearOptions();
                        this.scheduleSelect.addOptions(data.map(schedule => ({
                            value: schedule.id,
                            text: schedule.display,
                            group: schedule.day_name
                        })));
                    }
                })
                .catch(() => {
                    this.isLoading = false;
                    this.showNotification('Gagal memuat daftar jadwal.', 'error');
                });
        },
        showNotification(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 max-w-xs w-full text-white px-4 py-2 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        },
        initScheduleSelect() {
            this.scheduleSelect = new TomSelect('#schedule_id', {
                options: this.scheduleList.map(schedule => ({
                    value: schedule.id,
                    text: schedule.display,
                    group: schedule.day_name
                })),
                optgroupField: 'group',
                optgroups: [
                    { value: 'Sabtu', label: 'Sabtu' },
                    { value: 'Ahad', label: 'Ahad' },
                    { value: 'Senin', label: 'Senin' },
                    { value: 'Selasa', label: 'Selasa' },
                    { value: 'Rabu', label: 'Rabu' },
                    { value: 'Kamis', label: 'Kamis' }
                ],
                searchField: ['text'],
                placeholder: '-- Pilih Jadwal --',
                create: false,
                render: {
                    option: function(data, escape) {
                        return `<div>${escape(data.text)}</div>`;
                    },
                    item: function(data, escape) {
                        return `<div>${escape(data.text)}</div>`;
                    }
                }
            });
        }
    }" x-init="fetchSchedules(); initScheduleSelect();">

        <div class="max-w-7xl mx-auto py-4 sm:py-8 px-3 sm:px-6 lg:px-8">
            <div class="space-y-6 sm:space-y-8">
                <!-- Header -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-center sm:text-left">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold tracking-tight text-gray-900">Laporan Absensi Periodik</h1>
                            <p class="mt-1 text-xs sm:text-sm text-slate-600">Rekap absensi santri per periode bulanan</p>
                        </div>
                        <div class="mt-3 sm:mt-0 flex justify-center sm:justify-end">
                            <a href="{{ route('pengajaran.absensi.index') }}" class="inline-flex items-center justify-center rounded-md bg-red-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors duration-200 w-full sm:w-auto">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Form Filter -->
                <div class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-lg border border-slate-200">
                    <form action="{{ route('pengajaran.absensi.laporan-periodik') }}" method="GET" class="grid grid-cols-1 gap-3 sm:gap-4 sm:grid-cols-2 lg:grid-cols-4 items-end">
                        <div class="sm:col-span-2 lg:col-span-1">
                            <label for="kelas_id" class="block text-xs sm:text-sm font-medium text-gray-700">Kelas</label>
                            <select name="kelas_id" id="kelas_id" x-model="selectedKelas" @change="fetchSchedules()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-xs sm:text-sm py-2">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2 lg:col-span-1">
                            <label for="bulan" class="block text-xs sm:text-sm font-medium text-gray-700">Bulan</label>
                            <input type="month" name="bulan" id="bulan" value="{{ request('bulan', now()->format('Y-m')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-xs sm:text-sm py-2">
                        </div>

                        <div class="sm:col-span-2 lg:col-span-1">
                            <label for="schedule_id" class="block text-xs sm:text-sm font-medium text-gray-700">Mata Pelajaran</label>
                            <select name="schedule_id" id="schedule_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-xs sm:text-sm py-2" :disabled="isLoading">
                                <template x-if="isLoading">
                                    <option>Memuat jadwal...</option>
                                </template>
                                <template x-if="!isLoading && scheduleList.length === 0 && selectedKelas">
                                    <option value="">-- Tidak ada jadwal --</option>
                                </template>
                            </select>
                        </div>

                        <div class="sm:col-span-2 lg:col-span-1 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm hover:bg-red-600 transition-colors duration-200 w-full">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Tampilkan
                            </button>
                            @if(request()->filled(['kelas_id', 'bulan']))
                            <a href="{{ route('pengajaran.absensi.export-periodik', request()->query()) }}" class="inline-flex items-center justify-center rounded-md bg-green-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors duration-200 w-full">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                @if(isset($summary))
                <!-- Summary Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-4">
                    @php
                    $summaryCards = [
                    ['value' => $summary['total_santri'], 'label' => 'Total Santri', 'color' => 'blue'],
                    ['value' => number_format($summary['rata_rata_presentase'], 1) . '%', 'label' => 'Rata2 Kehadiran', 'color' => 'green'],
                    ['value' => $summary['total_hadir'], 'label' => 'Total Hadir', 'color' => 'green'],
                    ['value' => $summary['total_izin'], 'label' => 'Total Izin', 'color' => 'yellow'],
                    ['value' => $summary['total_sakit'], 'label' => 'Total Sakit', 'color' => 'orange'],
                    ['value' => $summary['total_alfa'], 'label' => 'Total Alfa', 'color' => 'red'],
                    ];
                    @endphp

                    @foreach($summaryCards as $card)
                    <div class="bg-white p-3 sm:p-4 rounded-lg shadow text-center border border-slate-200 transition-transform duration-200 hover:scale-105">
                        <div class="text-lg sm:text-xl lg:text-2xl font-bold text-{{ $card['color'] }}-600">{{ $card['value'] }}</div>
                        <div class="text-xs sm:text-sm text-gray-600 mt-1">{{ $card['label'] }}</div>
                    </div>
                    @endforeach
                </div>

                <!-- Info Periode -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4">
                    <div class="flex items-start sm:items-center">
                        <div class="flex-shrink-0 mt-1 sm:mt-0">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-2 sm:ml-3 flex-1">
                            <h3 class="text-xs sm:text-sm font-medium text-blue-800 leading-tight">
                                {{ $summary['kelas'] }} - {{ $summary['periode'] }}
                                @if($selectedSchedule && $selectedSchedule->subject)
                                - {{ $selectedSchedule->subject->nama_pelajaran }}
                                @endif
                            </h3>
                            <p class="text-xs text-blue-600 mt-1">
                                Pertemuan: {{ $summary['total_pertemuan'] }} • Santri: {{ $summary['total_santri'] }}
                            </p>
                        </div>
                        <div class="text-right hidden sm:block">
                            <p class="text-xs text-blue-600">
                                {{ now()->translatedFormat('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mobile Tabs -->
                <div class="sm:hidden bg-white rounded-lg shadow border border-slate-200">
                    <div class="flex border-b border-slate-200">
                        <button @click="activeTab = 'table'" :class="activeTab === 'table' ? 'border-b-2 border-red-500 text-red-600' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-3 px-4 text-center text-sm font-medium transition-colors duration-200">
                            Tabel
                        </button>
                        <button @click="activeTab = 'cards'" :class="activeTab === 'cards' ? 'border-b-2 border-red-500 text-red-600' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-3 px-4 text-center text-sm font-medium transition-colors duration-200">
                            Kartu
                        </button>
                    </div>
                </div>

                <!-- Tabel Rekap (Desktop) -->
                <div class="hidden sm:block bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-center">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Rekap Absensi per Santri</h3>
                        <div class="mt-1 sm:mt-0 text-xs sm:text-sm text-slate-600">
                            {{ $rekapData->count() }} santri
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 sm:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Nama Santri
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Hadir</th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Izin</th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Sakit</th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Alfa</th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                                    <th class="px-4 sm:px-6 py-2 sm:py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Presentase
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @foreach($rekapData as $santri)
                                <tr class="hover:bg-slate-50 transition-colors duration-150">
                                    <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <div class="font-medium text-slate-900 text-sm">{{ $santri['nama'] }}</div>
                                        <div class="text-xs text-slate-500">NIS: {{ $santri['nis'] }}</div>
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $santri['hadir'] }}
                                        </span>
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $santri['izin'] }}
                                        </span>
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            {{ $santri['sakit'] }}
                                        </span>
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $santri['alfa'] }}
                                        </span>
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 sm:py-4 whitespace-nowrap text-center text-sm text-slate-600 font-medium">
                                        {{ $santri['total'] }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            {{ $santri['presentase'] >= 90 ? 'bg-green-100 text-green-800' : 
                                               ($santri['presentase'] >= 75 ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-red-100 text-red-800') }}">
                                            {{ $santri['presentase'] }}%
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Cards View -->
                <div x-show="activeTab === 'cards'" class="sm:hidden space-y-3">
                    @foreach($rekapData as $santri)
                    <div class="bg-white rounded-lg shadow border border-slate-200 p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-slate-900 text-sm">{{ $santri['nama'] }}</h4>
                                <p class="text-xs text-slate-500">NIS: {{ $santri['nis'] }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $santri['presentase'] >= 90 ? 'bg-green-100 text-green-800' : 
                                   ($santri['presentase'] >= 75 ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800') }}">
                                {{ $santri['presentase'] }}%
                            </span>
                        </div>

                        <div class="grid grid-cols-4 gap-2 text-center">
                            <div>
                                <div class="text-xs text-slate-500">Hadir</div>
                                <div class="text-sm font-semibold text-green-600">{{ $santri['hadir'] }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500">Izin</div>
                                <div class="text-sm font-semibold text-yellow-600">{{ $santri['izin'] }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500">Sakit</div>
                                <div class="text-sm font-semibold text-orange-600">{{ $santri['sakit'] }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500">Alfa</div>
                                <div class="text-sm font-semibold text-red-600">{{ $santri['alfa'] }}</div>
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-t border-slate-200 text-center">
                            <div class="text-xs text-slate-500">Total Pertemuan</div>
                            <div class="text-sm font-semibold text-slate-700">{{ $santri['total'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Mobile Table View -->
                <div x-show="activeTab === 'table'" class="sm:hidden bg-white rounded-lg shadow border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-3 py-2 text-left font-semibold text-slate-500">Nama</th>
                                    <th class="px-1 py-2 text-center font-semibold text-slate-500">H</th>
                                    <th class="px-1 py-2 text-center font-semibold text-slate-500">I</th>
                                    <th class="px-1 py-2 text-center font-semibold text-slate-500">S</th>
                                    <th class="px-1 py-2 text-center font-semibold text-slate-500">A</th>
                                    <th class="px-1 py-2 text-center font-semibold text-slate-500">T</th>
                                    <th class="px-3 py-2 text-center font-semibold text-slate-500">%</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($rekapData as $santri)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-3 py-2">
                                        <div class="font-medium text-slate-900">{{ Str::limit($santri['nama'], 15) }}</div>
                                        <div class="text-slate-500">{{ $santri['nis'] }}</div>
                                    </td>
                                    <td class="px-1 py-2 text-center text-green-600 font-semibold">{{ $santri['hadir'] }}</td>
                                    <td class="px-1 py-2 text-center text-yellow-600 font-semibold">{{ $santri['izin'] }}</td>
                                    <td class="px-1 py-2 text-center text-orange-600 font-semibold">{{ $santri['sakit'] }}</td>
                                    <td class="px-1 py-2 text-center text-red-600 font-semibold">{{ $santri['alfa'] }}</td>
                                    <td class="px-1 py-2 text-center text-slate-600 font-semibold">{{ $santri['total'] }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            {{ $santri['presentase'] >= 90 ? 'bg-green-100 text-green-800' : 
                                               ($santri['presentase'] >= 75 ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-red-100 text-red-800') }}">
                                            {{ $santri['presentase'] }}%
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @endif

                @if(request()->filled(['kelas_id', 'bulan']) && (!isset($rekapData) || $rekapData->isEmpty()))
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="mt-2 sm:mt-4 text-base sm:text-lg font-medium text-yellow-800">Data Tidak Ditemukan</h3>
                    <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-yellow-700">
                        Tidak ada data absensi untuk periode yang dipilih.
                    </p>
                </div>
                @endif

                @if(!request()->filled(['kelas_id', 'bulan']))
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-8 sm:p-12 text-center">
                    <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 sm:mt-4 text-base sm:text-lg font-medium text-slate-600">Pilih Filter untuk Melihat Laporan</h3>
                    <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-slate-500">
                        Silakan pilih kelas dan bulan terlebih dahulu.
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
