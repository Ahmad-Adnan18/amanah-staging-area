<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Panel 1: Detail Kelas -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('pengajaran.kelas.update', $kela) }}" method="POST">
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
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jabatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tahun Ajaran</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse($penanggungJawab as $pj)
                                    <tr>
                                        <td class="px-6 py-4 font-medium text-slate-900">{{ $pj->user->name }}</td>
                                        <td class="px-6 py-4 text-slate-500">{{ $pj->jabatan->nama_jabatan }}</td>
                                        <td class="px-6 py-4 text-slate-500">{{ $pj->tahun_ajaran }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('pengajaran.kelas.remove_jabatan', $pj) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus penugasan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:text-red-800">Hapus</button>
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
</x-app-layout>

