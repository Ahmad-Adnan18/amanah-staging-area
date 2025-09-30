<x-app-layout>
    {{-- Alpine.js dibutuhkan untuk notifikasi yang bisa hilang otomatis --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endpush
    <style> [x-cloak] { display: none !important; } </style>

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-6 sm:space-y-8">

                {{-- Header Halaman --}}
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Manajemen Guru</h1>
                        <p class="mt-1 text-slate-600">Kelola semua data guru untuk keperluan penjadwalan.</p>
                    </div>
                    <a href="{{ route('admin.teachers.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                        <span>Tambah Guru</span>
                    </a>
                </div>

                {{-- Notifikasi Sukses atau Error --}}
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                         class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-2xl shadow-sm flex justify-between items-center" role="alert">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                        <button @click="show = false" class="text-green-600 hover:text-green-800">&times;</button>
                    </div>
                @endif
                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                         class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-2xl shadow-sm flex justify-between items-center" role="alert">
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                        <button @click="show = false" class="text-red-600 hover:text-red-800">&times;</button>
                    </div>
                @endif
                @if ($errors->any())
                     <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-2xl shadow-sm" role="alert">
                        <p class="font-bold">Terjadi Kesalahan</p>
                        <ul class="mt-1 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- [MODIFIKASI] Kartu Statistik dan Form Pencarian/Impor --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Kartu Jumlah Guru --}}
                    <div class="bg-white p-5 rounded-2xl shadow-lg border border-slate-200 flex items-start gap-4 md:col-span-1">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">
                            <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 21a6 6 0 004.777-9.417M15 14.074c-3.146 0-5.777-2.52-5.777-5.646 0-3.127 2.63-5.647 5.777-5.647 3.146 0 5.777 2.52 5.777 5.647 0 3.127-2.63-5.647-5.777-5.647z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Guru</p>
                            <p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalTeachers }}</p>
                        </div>
                    </div>

                    {{-- Form Pencarian & Impor --}}
                    <div class="bg-white p-5 rounded-2xl shadow-lg border border-slate-200 md:col-span-2 space-y-4">
                        <form action="{{ route('admin.teachers.index') }}" method="GET">
                            <label for="search" class="block text-sm font-medium text-gray-700">Cari Guru</label>
                            <div class="mt-1 flex flex-col sm:flex-row gap-2">
                                <div class="flex-grow relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg></div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-md border-gray-300 pl-10 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Ketik nama atau kode guru...">
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Cari</button>
                                    <a href="{{ route('admin.teachers.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Reset</a>
                                </div>
                            </div>
                        </form>
                        <div class="border-t border-slate-200 pt-4">
                             <form action="{{ route('admin.teachers.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-3">
                                @csrf
                                <label for="file" class="text-sm font-medium text-gray-700 flex-shrink-0">Impor dari Excel:</label>
                                <p class="text-sm text-slate-500 mt-1">Unggah file .xlsx atau .xls dengan format: Kolom A untuk Nama, Kolom B untuk Kode Guru.</p>
                                <input type="file" name="file" id="file" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 flex-1"/>
                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-slate-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-600">Impor</button>
                            </form>
                        </div>
                    </div>
                </div>


                {{-- Tabel Daftar Guru --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Nama Guru</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Kode Guru</th>
                                <th class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($teachers as $teacher)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 font-medium text-slate-900">{{ $teacher->name }}</td>
                                    <td class="px-6 py-4 text-slate-500 font-mono text-sm">{{ $teacher->teacher_code ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right space-x-4">
                                        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="font-medium text-slate-600 hover:text-red-700">Edit</a>
                                        <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data guru ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-500">
                                        @if(request('search'))
                                            Guru dengan nama atau kode "{{ request('search') }}" tidak ditemukan.
                                        @else
                                            Belum ada data guru. Silakan tambahkan secara manual atau impor dari Excel.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if ($teachers->hasPages())
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 px-4 py-3">
                        {{-- [MODIFIKASI] Menambahkan withQueryString() agar filter pencarian tetap aktif saat ganti halaman --}}
                        {{ $teachers->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>