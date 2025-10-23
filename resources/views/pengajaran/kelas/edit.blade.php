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
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900">Detail Kelas</h2>
                            <p class="mt-1 text-slate-600">Atur informasi dasar untuk kelas <span class="font-semibold text-red-700">{{ $kela->nama_kelas }}</span>.</p>
                        </div>
                        <div class="p-6 border-t border-slate-200 space-y-6">
                            <div>
                                <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                                <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas', $kela->nama_kelas) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            </div>

                            <div>
                                <label for="tingkatan" class="block text-sm font-medium text-gray-700">Tingkatan</label>
                                <select name="tingkatan" id="tingkatan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">-- Pilih Tingkatan --</option>
                                    @foreach($tingkatans as $tingkatan)
                                    <option value="{{ $tingkatan }}" @selected(old('tingkatan', $kela->tingkatan) == $tingkatan)>{{ $tingkatan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="room_id" class="block text-sm font-medium text-gray-700">Ruangan Induk (Home Room)</label>
                                <select name="room_id" id="room_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">-- Tidak ada --</option>
                                    @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" @selected(old('room_id', $kela->room_id) == $room->id)>{{ $room->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="is_active_for_scheduling" class="block text-sm font-medium text-gray-700">Status Penjadwalan</label>
                                <select name="is_active_for_scheduling" id="is_active_for_scheduling" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="1" @selected($kela->is_active_for_scheduling == 1)>Aktif</option>
                                    <option value="0" @selected($kela->is_active_for_scheduling == 0)>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Simpan Detail Kelas</button>
                        </div>
                    </form>
                    <!-- Panel Hapus Kelas -->
                    <div class="p-6 border-t border-slate-200">
                        <h2 class="text-xl font-bold text-gray-900">Hapus Kelas</h2>
                        <p class="mt-1 text-slate-600">Hapus kelas ini jika tidak diperlukan lagi. <span class="text-red-600 font-semibold">Peringatan: Semua santri di kelas ini juga akan terhapus.</span></p>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                        <form action="{{ route('pengajaran.kelas.destroy', $kela) }}" method="POST" x-ref="deleteKelasForm">
                            @csrf
                            @method('DELETE')
                            <button type="button" @click="confirmDelete($refs.deleteKelasForm)" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Hapus Kelas</button>
                        </form>
                    </div>
                </div>

                <!-- Panel 2: Alokasi Guru Mengajar -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('pengajaran.kelas.assignSubjects', $kela) }}" method="POST">
                        @csrf
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900">Alokasi Guru Mengajar</h2>
                            <p class="mt-1 text-slate-600">Tentukan guru yang akan mengajar setiap mata pelajaran di kelas ini.</p>
                        </div>
                        <div class="p-6 border-t border-slate-200">
                            @if($allMataPelajarans->isEmpty())
                            <div class="text-center py-8 px-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-yellow-800">Tidak ada mata pelajaran yang cocok untuk tingkatan <span class="font-bold">"{{ $kela->tingkatan }}"</span>.</p>
                                <p class="text-sm text-yellow-700 mt-1">Pastikan Anda telah membuat mata pelajaran untuk tingkatan ini di menu <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="underline hover:text-yellow-900">Manajemen Mata Pelajaran</a>.</p>
                            </div>
                            @else
                            <div class="space-y-6">
                                @foreach($allMataPelajarans as $mapel)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                    <label for="subject_{{ $mapel->id }}" class="font-medium text-gray-700">{{ $mapel->nama_pelajaran }}</label>
                                    <select name="subjects[{{ $mapel->id }}]" id="subject_{{ $mapel->id }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        <option value="">-- Pilih Guru --</option>
                                        @forelse($mapel->teachers as $teacher)
                                        <option value="{{ $teacher->id }}" @selected(isset($assignedSubjects[$mapel->id]) && $assignedSubjects[$mapel->id] == $teacher->id)>
                                            {{ $teacher->name }}
                                        </option>
                                        @empty
                                        <option value="" disabled>Tidak ada kandidat guru</option>
                                        @endforelse
                                    </select>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Simpan Alokasi Guru</button>
                        </div>
                    </form>
                </div>

                <!-- Panel 3: Penanggung Jawab Kelas (DIPERBAIKI) -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('pengajaran.kelas.assign_jabatan', $kela) }}" method="POST">
                        @csrf
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900">Penanggung Jawab Kelas</h2>
                            <p class="mt-1 text-slate-600">Tentukan pengguna yang memiliki jabatan tertentu di kelas ini (misal: Wali Kelas).</p>
                        </div>
                        <div class="p-6 border-t border-slate-200 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">Pengguna</label>
                                <select id="user_id" name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="jabatan_id" class="block text-sm font-medium text-gray-700">Jabatan</label>
                                <select id="jabatan_id" name="jabatan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                                <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ date('Y') }}/{{ date('Y')+1 }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Tugaskan Jabatan</button>
                        </div>
                    </form>

                    <!-- Daftar Penanggung Jawab -->
                    <div class="border-t border-slate-200">
                        <!-- Tambahkan div ini untuk membuat tabel dapat di-scroll secara horizontal di mobile -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase whitespace-nowrap">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase whitespace-nowrap">Jabatan</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase whitespace-nowrap">Tahun Ajaran</th>
                                        <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @forelse($penanggungJawab as $pj)
                                    <tr>
                                        <td class="px-6 py-4 font-medium text-slate-900 whitespace-nowrap">{{ $pj->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-slate-500 whitespace-nowrap">{{ $pj->jabatan->nama_jabatan ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-slate-500 whitespace-nowrap">{{ $pj->tahun_ajaran }}</td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <form action="{{ route('pengajaran.kelas.remove_jabatan', $pj) }}" method="POST" x-ref="deleteJabatanForm-{{ $pj->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" @click="confirmDelete($refs['deleteJabatanForm-{{ $pj->id }}'])" class="font-medium text-red-600 hover:text-red-800">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada penanggung jawab yang ditugaskan.</td>
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
</x-app-layout>
