<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                {{-- Header Halaman --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Guru</h1>
                            <p class="mt-1 text-slate-600">Kelola semua data guru untuk keperluan penjadwalan.</p>
                        </div>
                        <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                            <span>Tambah Guru</span>
                        </a>
                    </div>
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


                {{-- Fitur Impor Excel --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                    <form action="{{ route('admin.teachers.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800">Impor dari Excel</h3>
                            <p class="text-sm text-slate-500 mt-1">Unggah file .xlsx atau .xls dengan format: Kolom A untuk Nama, Kolom B untuk Kode Guru.</p>
                            <div class="mt-4 flex flex-col sm:flex-row items-center gap-4">
                                <input type="file" name="file" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 flex-1"/>
                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-slate-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-600">
                                    Impor
                                </button>
                            </div>
                        </div>
                    </form>
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
                                <tr><td colspan="3" class="px-6 py-12 text-center text-slate-500">Belum ada data guru. Silakan tambahkan secara manual atau impor dari Excel.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if ($teachers->hasPages())
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 px-4 py-3">
                        {{ $teachers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
