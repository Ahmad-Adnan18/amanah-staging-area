<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Tambah Kelas Baru</h1>
                        <p class="mt-1 text-slate-600">Buat kelas baru dan tentukan tingkatan serta ruangan induknya.</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('pengajaran.kelas.store') }}" method="POST">
                        @csrf
                        <div class="p-6 space-y-6">
                            <div>
                                <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                                <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas') }}" required autofocus placeholder="Contoh: Kelas 7A" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <x-input-error class="mt-2" :messages="$errors->get('nama_kelas')" />
                            </div>

                            <div>
                                <label for="tingkatan" class="block text-sm font-medium text-gray-700">Tingkatan</label>
                                <select name="tingkatan" id="tingkatan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">-- Pilih Tingkatan --</option>
                                    {{-- [PENYESUAIAN] Menggunakan daftar tingkatan dinamis --}}
                                    @foreach($tingkatans as $tingkatan)
                                        <option value="{{ $tingkatan }}" @selected(old('tingkatan') == $tingkatan)>{{ $tingkatan }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('tingkatan')" />
                            </div>

                            <div>
                                <label for="room_id" class="block text-sm font-medium text-gray-700">Ruangan Induk (Home Room)</label>
                                <select name="room_id" id="room_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">-- Tidak ada --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>{{ $room->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('room_id')" />
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-4">
                            <a href="{{ route('pengajaran.kelas.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</a>
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

