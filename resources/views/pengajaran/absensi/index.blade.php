<x-app-layout>
    <div class="bg-slate-50 min-h-screen" x-data="{
        selectedKelas: '{{ request('kelas_id') }}',
        selectedSchedule: '{{ request('schedule_id') }}',
        scheduleList: [],
        isLoading: false,
        editAbsensi: null,
        editStatus: '',
        editKeterangan: '',
        toastMessage: '',
        toastType: 'success',
        showToast: false,
        scheduleSelect: null, // Store TomSelect instance
        openEditModal(absensi) {
            this.editAbsensi = absensi;
            this.editStatus = absensi.status || 'hadir';
            this.editKeterangan = absensi.keterangan || '';
        },
        closeEditModal() {
            this.editAbsensi = null;
        },
        showNotification(message, type = 'success') {
            this.toastMessage = message;
            this.toastType = type;
            this.showToast = true;
            setTimeout(() => this.showToast = false, 3000);
        },
        updateAbsensi() {
            fetch(`/pengajaran/absensi/${this.editAbsensi.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: this.editStatus,
                    keterangan: this.editKeterangan
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal memperbarui absensi');
                return response.json();
            })
            .then(data => {
                this.showNotification(data.message);
                this.closeEditModal();
                setTimeout(() => window.location.reload(), 1000);
            })
            .catch(error => {
                this.showNotification(error.message, 'error');
            });
        },
        deleteAbsensi(id) {
            if (confirm('Yakin ingin menghapus absensi ini?')) {
                fetch(`/pengajaran/absensi/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Gagal menghapus absensi');
                    return response.json();
                })
                .then(data => {
                    this.showNotification(data.message);
                    setTimeout(() => window.location.reload(), 1000);
                })
                .catch(error => {
                    this.showNotification(error.message, 'error');
                });
            }
        },
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
                            group: schedule.day_name // Untuk grouping per hari
                        })));
                        if (this.selectedSchedule) this.scheduleSelect.setValue(this.selectedSchedule);
                    }
                })
                .catch(() => {
                    this.isLoading = false;
                    this.showNotification('Gagal memuat daftar jadwal.', 'error');
                });
        },
        initScheduleSelect() {
            this.scheduleSelect = new TomSelect('#schedule_id', {
                options: this.scheduleList.map(schedule => ({
                    value: schedule.id,
                    text: schedule.display,
                    group: schedule.day_name // Grouping berdasarkan hari
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
                onChange: value => this.selectedSchedule = value,
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

        <!-- Toast Notification -->
        <div x-show="showToast" class="fixed top-4 right-4 z-50 max-w-xs w-full" x-transition>
            <div :class="toastType === 'success' ? 'bg-green-500' : 'bg-red-500'" class="text-white px-4 py-2 rounded-lg shadow-lg">
                <span x-text="toastMessage"></span>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">
                <!-- Header Halaman dengan Link Laporan -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Input Absensi Santri</h1>
                            <p class="mt-1 text-sm sm:text-base text-slate-600">Pilih kelas, jadwal, dan tanggal untuk memulai.</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <a href="{{ route('pengajaran.absensi.laporan-periodik') }}" class="inline-flex items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Lihat Laporan Periodik
                            </a>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                <div class="mt-8 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                <!-- Form Filter -->
                <div class="mt-8 bg-white p-6 rounded-2xl shadow-lg border border-slate-200">
                    <form action="{{ route('pengajaran.absensi.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 sm:gap-6 items-end">
                        <div>
                            <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                            <select name="kelas_id" id="kelas_id" x-model="selectedKelas" @change="fetchSchedules()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm sm:text-base" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="schedule_id" class="block text-sm font-medium text-gray-700">Jadwal</label>
                            <select name="schedule_id" id="schedule_id" x-model="selectedSchedule" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm sm:text-base" :disabled="isLoading || !selectedKelas" required>
                                <template x-if="isLoading">
                                    <option>Memuat...</option>
                                </template>
                                <template x-if="!isLoading && !selectedKelas">
                                    <option value="">-- Pilih Kelas Dulu --</option>
                                </template>
                                <template x-if="!isLoading && selectedKelas && scheduleList.length === 0">
                                    <option value="">-- Jadwal Kosong --</option>
                                </template>
                                <template x-if="!isLoading && selectedKelas && scheduleList.length > 0">
                                    <option value="">-- Pilih Jadwal --</option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal', now()->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm sm:text-base" required>
                        </div>

                        <div class="col-span-full sm:col-auto">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 w-full sm:w-auto">
                                Tampilkan Santri
                            </button>
                        </div>
                    </form>
                </div>

                @if ($santris->isNotEmpty())
                <div class="mt-8 bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('pengajaran.absensi.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ request('kelas_id') }}">
                        <input type="hidden" name="schedule_id" value="{{ request('schedule_id') }}">
                        <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">

                        <div class="px-4 sm:px-6 py-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Input Absensi</h3>
                                <p class="text-sm text-slate-600">Jadwal: <span class="font-medium">{{ $selectedSchedule->subject->nama_pelajaran ?? '-' }}</span></p>
                            </div>
                            <div class="flex space-x-2 mt-2 sm:mt-0">
                                <a href="{{ route('pengajaran.absensi.export', request()->query()) }}" class="inline-flex items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                                    Export Leger
                                </a>
                                <a href="{{ route('pengajaran.absensi.laporan-periodik', ['kelas_id' => request('kelas_id'), 'bulan' => request('tanggal', now()->format('Y-m'))]) }}" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                                    Laporan Bulan Ini
                                </a>
                            </div>
                        </div>

                        <!-- Tabel untuk Desktop -->
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Nama Santri</th>
                                        <th class="w-40 px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase">Status</th>
                                        <th class="w-64 px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase">Keterangan</th>
                                        <th class="w-32 px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach ($santris as $index => $santri)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4">
                                            <input type="hidden" name="absensis[{{ $index }}][santri_id]" value="{{ $santri->id }}">
                                            <div class="font-medium text-slate-900">{{ $santri->nama }}</div>
                                            <div class="text-sm text-slate-500">NIS: {{ $santri->nis }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="absensis[{{ $index }}][status]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                                                <option value="hadir" {{ optional($santri->absensis->first())->status === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                <option value="izin" {{ optional($santri->absensis->first())->status === 'izin' ? 'selected' : '' }}>Izin</option>
                                                <option value="sakit" {{ optional($santri->absensis->first())->status === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                                <option value="alfa" {{ optional($santri->absensis->first())->status === 'alfa' ? 'selected' : '' }}>Alfa</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="absensis[{{ $index }}][keterangan]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" value="{{ optional($santri->absensis->first())->keterangan ?? '' }}" />
                                        </td>
                                        <td class="px-6 py-4 text-center space-x-2">
                                            @if ($santri->absensis->isNotEmpty())
                                            <button type="button" @click="openEditModal({ id: {{ $santri->absensis->first()->id }}, status: '{{ $santri->absensis->first()->status }}', keterangan: '{{ $santri->absensis->first()->keterangan }}' })" class="text-blue-600 hover:text-blue-800 text-sm" aria-label="Edit absensi untuk {{ $santri->nama }}">Edit</button>
                                            <button type="button" @click="deleteAbsensi({{ $santri->absensis->first()->id }})" class="text-red-600 hover:text-red-800 text-sm" aria-label="Hapus absensi untuk {{ $santri->nama }}">Hapus</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Card Layout untuk Mobile -->
                        <div class="sm:hidden space-y-4 px-4 py-4">
                            @foreach ($santris as $index => $santri)
                            <div class="bg-white border border-slate-200 rounded-lg p-4">
                                <input type="hidden" name="absensis[{{ $index }}][santri_id]" value="{{ $santri->id }}">
                                <div class="font-medium text-slate-900">{{ $santri->nama }}</div>
                                <div class="text-sm text-slate-500 mb-2">NIS: {{ $santri->nis }}</div>
                                <div class="grid grid-cols-1 gap-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Status</label>
                                        <select name="absensis[{{ $index }}][status]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                                            <option value="hadir" {{ optional($santri->absensis->first())->status === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                            <option value="izin" {{ optional($santri->absensis->first())->status === 'izin' ? 'selected' : '' }}>Izin</option>
                                            <option value="sakit" {{ optional($santri->absensis->first())->status === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                            <option value="alfa" {{ optional($santri->absensis->first())->status === 'alfa' ? 'selected' : '' }}>Alfa</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Keterangan</label>
                                        <input type="text" name="absensis[{{ $index }}][keterangan]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" value="{{ optional($santri->absensis->first())->keterangan ?? '' }}" />
                                    </div>
                                    @if ($santri->absensis->isNotEmpty())
                                    <div class="flex space-x-2">
                                        <button type="button" @click="openEditModal({ id: {{ $santri->absensis->first()->id }}, status: '{{ $santri->absensis->first()->status }}', keterangan: '{{ $santri->absensis->first()->keterangan }}' })" class="flex-1 rounded-md bg-blue-600 text-white py-2 text-sm hover:bg-blue-700" aria-label="Edit absensi untuk {{ $santri->nama }}">Edit</button>
                                        <button type="button" @click="deleteAbsensi({{ $santri->absensis->first()->id }})" class="flex-1 rounded-md bg-red-600 text-white py-2 text-sm hover:bg-red-700" aria-label="Hapus absensi untuk {{ $santri->nama }}">Hapus</button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="px-4 sm:px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Simpan Semua Absensi
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Modal Edit -->
        <div x-show="editAbsensi" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Absensi</h3>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select x-model="editStatus" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alfa">Alfa</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                        <input type="text" x-model="editKeterangan" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="closeEditModal" class="rounded-md bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300">Batal</button>
                    <button @click="updateAbsensi" class="rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
