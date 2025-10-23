<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">
                
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('admin.rooms.store') }}" method="POST">
                        @csrf
                        <div class="p-6 space-y-6">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Tambah Ruangan Baru</h1>
                                <p class="mt-1 text-slate-600">Isi detail ruangan di bawah ini.</p>
                            </div>
                            <hr>
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Ruangan</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus placeholder="Contoh: Kelas 7A atau Lab Komputer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Tipe Ruangan</label>
                                <select name="type" id="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="Biasa" {{ old('type') == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                                    <option value="Khusus" {{ old('type') == 'Khusus' ? 'selected' : '' }}>Khusus (Lab, Aula, dll)</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('type')" />
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-4">
                            <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>