<x-app-layout>
    {{-- Alpine.js dibutuhkan untuk notifikasi dan pop-up kustom --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endpush
    <style>
        [x-cloak] {
            display: none !important;
        }

    </style>

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-6 sm:space-y-8" x-data="{
                    showDeleteConfirm: false,
                    showImportConfirm: false,
                    actionForm: null,
                    confirmDelete(form) {
                        this.actionForm = form;
                        this.showDeleteConfirm = true;
                    },
                    confirmImport(form) {
                        this.actionForm = form;
                        this.showImportConfirm = true;
                    },
                    submitAction() {
                        if (this.actionForm) {
                            this.actionForm.submit();
                        }
                        this.showDeleteConfirm = false;
                        this.showImportConfirm = false;
                        this.actionForm = null;
                    },
                    cancelAction() {
                        this.showDeleteConfirm = false;
                        this.showImportConfirm = false;
                        this.actionForm = null;
                    }
                 }">

                {{-- Pop-up Konfirmasi Hapus --}}
                <div x-show="showDeleteConfirm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 max-w-sm w-full mx-4">
                        <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Hapus</h3>
                        <p class="mt-2 text-sm text-slate-600">Apakah Anda yakin ingin menghapus data guru ini? Tindakan ini tidak dapat dibatalkan.</p>
                        <div class="mt-6 flex justify-end gap-3">
                            <button @click="cancelAction()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-md hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                                Batal
                            </button>
                            <button @click="submitAction()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Pop-up Konfirmasi Impor --}}
                <div x-show="showImportConfirm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 max-w-sm w-full mx-4">
                        <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Impor</h3>
                        <p class="mt-2 text-sm text-slate-600">Apakah Anda yakin ingin mengimpor data guru dari file ini? Pastikan file sesuai dengan format yang ditentukan.</p>
                        <div class="mt-6 flex justify-end gap-3">
                            <button @click="cancelAction()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-md hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                                Batal
                            </button>
                            <button @click="submitAction()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Impor
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Header Halaman --}}
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Manajemen Guru</h1>
                        <p class="mt-1 text-slate-600">Kelola semua data guru untuk keperluan penjadwalan.</p>
                    </div>
                    <a href="{{ route('admin.teachers.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        <span>Tambah Guru</span>
                    </a>
                </div>

                {{-- Notifikasi Sukses atau Error --}}
                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif
                @if (session('error'))
                    <x-alert type="error" :message="session('error')" />
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

                {{-- Kartu Statistik dan Form Pencarian/Impor --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Kartu Jumlah Guru --}}
                    <div class="bg-white p-5 rounded-2xl shadow-lg border border-slate-200 flex items-start gap-4 md:col-span-1 hover:shadow-xl transition-all duration-300">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-500 mb-1">Total Guru</p>
                            <p class="text-3xl font-bold text-slate-800 leading-tight">{{ number_format($totalTeachers) }}</p>
                            <p class="text-xs text-slate-400 mt-1">Staf pengajar aktif</p>
                        </div>
                    </div>
                    {{-- Form Pencarian & Impor --}}
                    <div class="bg-white p-5 rounded-2xl shadow-lg border border-slate-200 md:col-span-2 space-y-4">
                        <form action="{{ route('admin.teachers.index') }}" method="GET">
                            <label for="search" class="block text-sm font-medium text-gray-700">Cari Guru</label>
                            <div class="mt-1 flex flex-col sm:flex-row gap-2">
                                <div class="flex-grow relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-md border-gray-300 pl-10 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Ketik nama atau kode guru...">
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Cari</button>
                                    <a href="{{ route('admin.teachers.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Reset</a>
                                </div>
                            </div>
                        </form>
                        <div class="border-t border-slate-200 pt-4">
                            <form action="{{ route('admin.teachers.import') }}" method="POST" enctype="multipart/form-data" x-ref="importForm">
                                @csrf
                                <label for="file" class="block text-sm font-medium text-gray-700">Impor dari Excel</label>
                                <p class="text-sm text-slate-500 mt-1">Unggah file .xlsx atau .xls dengan format: Kolom A untuk Nama, Kolom B untuk Kode Guru.</p>
                                <div class="mt-1 flex flex-col sm:flex-row items-center gap-3">
                                    <input type="file" name="file" id="file" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 flex-1" />
                                    <button type="button" @click="confirmImport($refs.importForm)" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-slate-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-600">Impor</button>
                                </div>
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
                                    <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="inline" x-ref="deleteForm-{{ $teacher->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" @click="confirmDelete($refs['deleteForm-{{ $teacher->id }}'])" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
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
                    {{ $teachers->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
