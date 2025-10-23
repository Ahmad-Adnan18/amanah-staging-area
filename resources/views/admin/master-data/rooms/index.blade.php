<x-app-layout>
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

                {{-- Page Header --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex-1">
                            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                                Manajemen Ruangan
                            </h1>
                            <p class="mt-2 text-sm sm:text-base text-slate-600">
                                Kelola daftar ruangan untuk penjadwalan dengan mudah.
                            </p>
                        </div>
                        <a href="{{ route('admin.rooms.create') }}" class="inline-flex items-center justify-center rounded-lg bg-red-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors w-full sm:w-auto">
                            Tambah Ruangan Baru
                        </a>
                    </div>
                </div>

                {{-- Custom Delete Confirmation Pop-up --}}
                <div x-show="showDeleteConfirm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 max-w-sm w-full mx-4">
                        <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Hapus</h3>
                        <p class="mt-2 text-sm text-slate-600">Apakah Anda yakin ingin menghapus ruangan ini? Tindakan ini tidak dapat dibatalkan.</p>
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

                {{-- Room List --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    {{-- Table View for Desktop/Tablet (sm and above) --}}
                    <div class="hidden sm:block">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                            Nama Ruangan
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                            Tipe
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @forelse ($rooms as $room)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                            {{ $room->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full
                                                    {{ $room->type == 'Khusus' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $room->type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-3">
                                                <a href="{{ route('admin.rooms.inventory.index', $room) }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                                                    Inventaris
                                                </a>
                                                <a href="{{ route('admin.rooms.edit', $room) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-400 transition-colors">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline" x-ref="deleteForm-{{ $room->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" @click="confirmDelete($refs['deleteForm-{{ $room->id }}'])" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 rounded-md focus:outline-none focus:ring-2 focus:ring-red-400 transition-colors">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-slate-500">
                                            <div class="flex flex-col items-center space-y-3">
                                                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                                <p class="text-sm font-medium">Belum ada ruangan yang ditambahkan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Card View for Mobile (below sm) --}}
                    <div class="sm:hidden">
                        @if($rooms->isEmpty())
                        <div class="p-8 text-center text-slate-500">
                            <div class="flex flex-col items-center space-y-3">
                                <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-sm font-medium">Belum ada ruangan yang ditambahkan.</p>
                            </div>
                        </div>
                        @else
                        <div class="divide-y divide-slate-200">
                            @foreach ($rooms as $room)
                            <div class="p-4 hover:bg-slate-50 transition-colors">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1 pr-4">
                                        <h3 class="text-lg font-semibold text-gray-900 leading-tight">
                                            {{ $room->name }}
                                        </h3>
                                    </div>
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full
                                                {{ $room->type == 'Khusus' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $room->type }}
                                    </span>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('admin.rooms.inventory.index', $room) }}" class="text-center text-sm font-medium text-blue-600 hover:text-blue-800 py-2 transition-colors">
                                        Kelola Inventaris
                                    </a>
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.rooms.edit', $room) }}" class="inline-flex items-center justify-center flex-1 px-3 py-1.5 text-sm font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-400 transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline" x-ref="deleteForm-{{ $room->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" @click="confirmDelete($refs['deleteForm-{{ $room->id }}'])" class="inline-flex items-center justify-center flex-1 px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 rounded-md focus:outline-none focus:ring-2 focus:ring-red-400 transition-colors">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
