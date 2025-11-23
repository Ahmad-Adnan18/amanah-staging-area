<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8" x-data="{
                    showDeleteConfirm: false,
                    deleteForm: null,
                    confirmDelete(form) {
                        this.deleteForm = form;
                        this.showDeleteConfirm = true;
                    },
                    submitDelete() {
                        if (this.deleteForm) {
                            this.deleteForm.submit();
                        }
                        this.showDeleteConfirm = false;
                        this.deleteForm = null;
                    },
                    cancelDelete() {
                        this.showDeleteConfirm = false;
                        this.deleteForm = null;
                    }
                 }">

                <!-- Custom Delete Confirmation Pop-up -->
                <div x-show="showDeleteConfirm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 max-w-sm w-full mx-4">
                        <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Hapus</h3>
                        <p class="mt-2 text-sm text-slate-600">Apakah Anda yakin ingin menghapus ini? Tindakan ini tidak dapat dibatalkan.</p>
                        <div class="mt-6 flex justify-end gap-3">
                            <button @click="cancelDelete()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-md hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                                Batal
                            </button>
                            <button @click="submitDelete()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Panel 1: Detail Kelas -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('pengajaran.kelas.update', $kela) }}" method="POST" id="update-form">
                        @csrf
                        @method('PUT')
                        <div class="p-6 border-b border-slate-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-lg bg-red-100">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H7m0 0H4m0 0h2m3 4V5a1 1 0 011-1h8a1 1 0 011 1v16a1 1 0 01-1 1H9a1 1 0 01-1-1z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-xl font-bold text-gray-900">Detail Kelas</h2>
                                    <p class="mt-1 text-slate-600">Atur informasi dasar untuk kelas <span class="font-semibold text-red-700">{{ $kela->nama_kelas }}</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                            </svg>
                                        </div>
                                        <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas', $kela->nama_kelas) }}" required class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    </div>
                                </div>

                                <div>
                                    <label for="tingkatan" class="block text-sm font-medium text-gray-700 mb-1">Tingkatan</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <select name="tingkatan" id="tingkatan" required class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                            <option value="">-- Pilih Tingkatan --</option>
                                            @foreach($tingkatans as $tingkatan)
                                            <option value="{{ $tingkatan }}" @selected(old('tingkatan', $kela->tingkatan) == $tingkatan)>{{ $tingkatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="room_id" class="block text-sm font-medium text-gray-700 mb-1">Ruangan Induk (Home Room)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </div>
                                        <select name="room_id" id="room_id" class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                            <option value="">-- Tidak ada --</option>
                                            @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" @selected(old('room_id', $kela->room_id) == $room->id)>{{ $room->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for="is_active_for_scheduling" class="block text-sm font-medium text-gray-700 mb-1">Status Penjadwalan</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <select name="is_active_for_scheduling" id="is_active_for_scheduling" class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                            <option value="1" @selected($kela->is_active_for_scheduling == 1)>Aktif</option>
                                            <option value="0" @selected($kela->is_active_for_scheduling == 0)>Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 transition-colors duration-200">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Panel Hapus Kelas -->
                <div class="bg-white rounded-2xl shadow-lg border border-red-200 overflow-hidden">
                    <div class="p-6 border-b border-red-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-lg bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-bold text-red-900">Hapus Kelas</h2>
                                <p class="mt-1 text-red-600">Hapus kelas ini jika tidak diperlukan lagi</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Peringatan Penting</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>Operasi ini akan menghapus kelas secara permanen beserta semua data yang terkait termasuk jadwal, santri, dan penilaian.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-red-50 border-t border-red-200 flex justify-end">
                        <form action="{{ route('pengajaran.kelas.destroy', $kela) }}" method="POST" x-ref="deleteKelasForm">
                            @csrf
                            @method('DELETE')
                            <button type="button" @click="confirmDelete($refs.deleteKelasForm)" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 transition-colors duration-200">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Kelas
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Panel 2: Alokasi Guru Mengajar -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('pengajaran.kelas.assignSubjects', $kela) }}" method="POST">
                        @csrf
                        <div class="p-6 border-b border-slate-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-xl font-bold text-gray-900">Alokasi Guru Mengajar</h2>
                                    <p class="mt-1 text-slate-600">Tentukan guru yang akan mengajar setiap mata pelajaran di kelas ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($allMataPelajarans->isEmpty())
                            <div class="text-center py-12 px-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-yellow-800">Tidak ada mata pelajaran</h3>
                                <p class="mt-1 text-yellow-700">Tidak ada mata pelajaran yang cocok untuk tingkatan <span class="font-bold">"{{ $kela->tingkatan }}"</span>.</p>
                                <p class="mt-2 text-sm text-yellow-600">Pastikan Anda telah membuat mata pelajaran untuk tingkatan ini di menu <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="underline hover:text-yellow-800">Manajemen Mata Pelajaran</a>.</p>
                            </div>
                            @else
                            <div class="space-y-4">
                                @foreach($allMataPelajarans as $mapel)
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200">
                                    <label for="subject_{{ $mapel->id }}" class="font-medium text-gray-700 min-w-0 flex-1 pr-4">{{ $mapel->nama_pelajaran }}</label>
                                    <div class="relative flex-1 max-w-xs">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <select name="subjects[{{ $mapel->id }}]" id="subject_{{ $mapel->id }}" class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 tom-select-guru">
                                            <option value="">-- Pilih Guru --</option>
                                            @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" @selected(isset($assignedSubjects[$mapel->id]) && $assignedSubjects[$mapel->id] == $teacher->id)>
                                                {{ $teacher->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 transition-colors duration-200">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Alokasi Guru
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Panel 3: Penanggung Jawab Kelas -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('pengajaran.kelas.assign_jabatan', $kela) }}" method="POST">
                        @csrf
                        <div class="p-6 border-b border-slate-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-lg bg-green-100">
                                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-xl font-bold text-gray-900">Penanggung Jawab Kelas</h2>
                                    <p class="mt-1 text-slate-600">Tentukan pengguna yang memiliki jabatan tertentu di kelas ini (misal: Wali Kelas)</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Pengguna</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <select id="user_id" name="user_id" class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <option value="">-- Pilih Pengguna --</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="jabatan_id" class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <select id="jabatan_id" name="jabatan_id" class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <option value="">-- Pilih Jabatan --</option>
                                        @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ date('Y') }}/{{ date('Y')+1 }}" class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 transition-colors duration-200">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tugaskan Jabatan
                            </button>
                        </div>
                    </form>

                    <!-- Daftar Penanggung Jawab -->
                    <div class="border-t border-slate-200">
                        <div class="p-6 border-b border-slate-200">
                            <h3 class="text-lg font-medium text-gray-900">Daftar Penanggung Jawab Kelas</h3>
                            <p class="mt-1 text-sm text-slate-600">Daftar pengguna yang saat ini menjabat sebagai penanggung jawab kelas ini</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jabatan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tahun Ajaran</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @forelse($penanggungJawab as $pj)
                                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-slate-700">{{ strtoupper(substr($pj->user->name ?? 'N/A', 0, 1)) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-slate-900">{{ $pj->user->name ?? 'N/A' }}</div>
                                                    <div class="text-sm text-slate-500">{{ $pj->user->email ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-slate-900">{{ $pj->jabatan->nama_jabatan ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {{ $pj->tahun_ajaran }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('pengajaran.kelas.remove_jabatan', $pj) }}" method="POST" x-ref="deleteJabatanForm-{{ $pj->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" @click="confirmDelete($refs['deleteJabatanForm-{{ $pj->id }}'])" class="text-red-600 hover:text-red-900 transition-colors duration-150">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-slate-900">Tidak ada penanggung jawab</h3>
                                            <p class="mt-1 text-sm text-slate-500">Belum ada penanggung jawab yang ditugaskan untuk kelas ini.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Tom-Select for all guru dropdowns
            document.querySelectorAll('.tom-select-guru').forEach(function(selectElement) {
                new TomSelect(selectElement, {
                    plugins: ['dropdown_input'],
                    create: false,
                    allowEmptyOption: true,
                    sortField: 'text',
                    maxOptions: 1000, // Sesuaikan dengan jumlah guru yang ada
                    dropdownParent: 'body', // Pastikan dropdown muncul di atas elemen lain
                    render: {
                        option: function(data, escape) {
                            return '<div class="py-2 px-3 hover:bg-blue-50 cursor-pointer">' +
                                    '<span class="block text-sm font-medium text-gray-900">' + escape(data.text) + '</span>' +
                                   '</div>';
                        },
                        item: function(data, escape) {
                            return '<div class="py-1 px-2 text-sm font-medium text-blue-800 bg-blue-100 rounded">' + escape(data.text) + '</div>';
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>
