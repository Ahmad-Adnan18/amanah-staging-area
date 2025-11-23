<x-app-layout>
    {{-- Link ke CSS untuk Tom-Select, agar pilihan guru lebih modern --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
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

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Mata Pelajaran</h1>
                            <p class="mt-1 text-slate-600">Perbarui detail untuk mata pelajaran: <span class="font-semibold">{{ $mataPelajaran->nama_pelajaran }}</span></p>
                        </div>
                        <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 flex-shrink-0">
                            Kembali
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('pengajaran.mata-pelajaran.update', $mataPelajaran->id) }}" method="POST" id="edit-form">
                        @csrf
                        @method('PUT')
                        <div class="p-6 space-y-6">

                            {{-- Baris 1: Nama Pelajaran & Tingkatan --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nama_pelajaran" class="block text-sm font-medium text-gray-700">Nama Pelajaran</label>
                                    <input type="text" name="nama_pelajaran" id="nama_pelajaran" value="{{ old('nama_pelajaran', $mataPelajaran->nama_pelajaran) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <x-input-error class="mt-2" :messages="$errors->get('nama_pelajaran')" />
                                </div>

                                {{-- Tingkatan (Dinamis dari database dengan TomSelect) --}}
                                <div>
                                    <label for="tingkatan" class="block text-sm font-medium text-gray-700">Tingkatan</label>
                                    <select name="tingkatan" id="tingkatan" required class="mt-1 block w-full">
                                        <option value="">-- Pilih Tingkatan --</option>
                                        @foreach ($tingkatans as $tingkatan)
                                        <option value="{{ $tingkatan }}" {{ old('tingkatan', $mataPelajaran->tingkatan) == $tingkatan ? 'selected' : '' }}>
                                            {{ $tingkatan }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Pilih tingkatan atau ketik tingkatan baru jika tidak ada di daftar.</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('tingkatan')" />
                                </div>
                            </div>

                            {{-- Baris 2: Kategori & Durasi --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                                    <select name="kategori" id="kategori" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        <option value="Umum" {{ old('kategori', $mataPelajaran->kategori) == 'Umum' ? 'selected' : '' }}>Umum</option>
                                        <option value="Diniyah" {{ old('kategori', $mataPelajaran->kategori) == 'Diniyah' ? 'selected' : '' }}>Diniyah</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('kategori')" />
                                </div>
                                <div>
                                    <label for="duration_jp" class="block text-sm font-medium text-gray-700">Durasi (Jam Pelajaran)</label>
                                    <input type="number" name="duration_jp" id="duration_jp" value="{{ old('duration_jp', $mataPelajaran->duration_jp) }}" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <x-input-error class="mt-2" :messages="$errors->get('duration_jp')" />
                                </div>
                            </div>

                            {{-- Penjelasan Logika Ruangan --}}
                            <div class="space-y-4 rounded-lg bg-slate-50 p-4 border border-slate-200">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="requires_special_room" name="requires_special_room" type="checkbox" value="1" {{ old('requires_special_room', $mataPelajaran->requires_special_room) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="requires_special_room" class="font-medium text-gray-900">Butuh Ruang Khusus?</label>
                                    </div>
                                </div>
                                <div class="relative rounded-lg border-l-4 bg-blue-50 p-4 border-blue-400">
                                    <p class="text-sm text-blue-800">
                                        Opsi ini menentukan bagaimana jadwal akan dialokasikan:
                                    </p>
                                    <ul class="mt-2 list-disc list-inside text-sm text-blue-700 space-y-1">
                                        <li>Jika <strong>TIDAK DICENTANG</strong>, pelajaran ini akan dijadwalkan di <strong>Ruangan Induk (Home Room)</strong> yang telah ditetapkan untuk setiap kelas.</li>
                                        <li>Jika <strong>DICENTANG</strong>, pelajaran ini akan dijadwalkan di salah satu <strong>Ruangan Khusus</strong> (misal: Laboratorium) yang tersedia dan akan <strong>MENGABAIKAN</strong> Ruangan Induk kelas.</li>
                                    </ul>
                                </div>
                            </div>



                        </div>
                    </form>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row-reverse sm:justify-between items-center gap-4">
                        <div class="flex items-center gap-4 w-full sm:w-auto flex-col sm:flex-row">
                            <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 w-full sm:w-auto">
                                Batal
                            </a>
                            <button type="submit" form="edit-form" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 w-full sm:w-auto">
                                Simpan Perubahan
                            </button>
                        </div>
                        <div class="w-full sm:w-auto">
                            <form action="{{ route('pengajaran.mata-pelajaran.destroy', $mataPelajaran->id) }}" method="POST" x-ref="deleteForm">
                                @csrf
                                @method('DELETE')
                                <button type="button" @click="confirmDelete($refs.deleteForm)" class="inline-flex items-center justify-center rounded-md bg-transparent px-4 py-2 text-sm font-semibold text-red-700 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50 w-full sm:w-auto">
                                    Hapus Pelajaran
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Custom Delete Confirmation Pop-up --}}
                <div x-show="showDeleteConfirm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 max-w-sm w-full mx-4">
                        <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Hapus</h3>
                        <p class="mt-2 text-sm text-slate-600">Apakah Anda yakin ingin menghapus mata pelajaran <span class="font-semibold">{{ $mataPelajaran->nama_pelajaran }}</span>? Tindakan ini tidak dapat dibatalkan.</p>
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
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Tom-Select for tingkatan with input support
            new TomSelect('#tingkatan', {
                create: true
                , createFilter: function(input) {
                    return input.length >= 2; // Sesuai validasi backend (min:2)
                }
                , placeholder: 'Pilih atau ketik tingkatan...'
            , });
        });

    </script>
</x-app-layout>
