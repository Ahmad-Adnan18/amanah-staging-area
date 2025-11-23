<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Edit Data Surat</h1>
                        <p class="mt-1 text-sm text-slate-600">Perbarui data untuk: <span class="font-medium">{{ $surat->nama_surat }}</span></p>
                    </div>
                    <div>
                        <a href="{{ route('admin.master-data.surats.index') }}" class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <form action="{{ route('admin.master-data.surats.update', $surat) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="id" class="block text-sm font-medium text-gray-700">Nomor Surat (ID)</label>
                            <input type="number" name="id" id="id" min="1" max="114" value="{{ old('id', $surat->id) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" required>
                            @error('id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nama_surat" class="block text-sm font-medium text-gray-700">Nama Surat</label>
                            <input type="text" name="nama_surat" id="nama_surat" value="{{ old('nama_surat', $surat->nama_surat) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" required>
                            @error('nama_surat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah_ayat" class="block text-sm font-medium text-gray-700">Jumlah Ayat</label>
                            <input type="number" name="jumlah_ayat" id="jumlah_ayat" min="1" value="{{ old('jumlah_ayat', $surat->jumlah_ayat) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" required>
                            @error('jumlah_ayat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                        <button type="submit" class="rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                            Update Surat
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
