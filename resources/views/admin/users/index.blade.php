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

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Akun Pengguna</h1>
                            <p class="mt-1 text-slate-600">Kelola semua akun yang terdaftar di sistem.</p>
                        </div>
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                            <span>Tambah Akun</span>
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif

                <!-- Custom Delete Confirmation Pop-up -->
                <div x-show="showDeleteConfirm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 max-w-sm w-full mx-4">
                        <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Hapus</h3>
                        <p class="mt-2 text-sm text-slate-600">Apakah Anda yakin ingin menghapus akun pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>
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

                <!-- Mobile View -->
                <div class="space-y-4 md:hidden">
                    @forelse ($users as $user)
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4">
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex-1">
                                <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                <p class="text-sm text-slate-500 mt-1">{{ $user->email }}</p>
                                <div class="mt-3">
                                    @php
                                    $roleClass = match($user->role) {
                                    'admin' => 'bg-red-50 text-red-700 ring-red-600/20',
                                    'wali_santri' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                    'pengajaran' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    'keasramaan' => 'bg-green-50 text-green-700 ring-green-600/20',
                                    default => 'bg-slate-50 text-slate-600 ring-slate-500/20',
                                    };
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $roleClass }}">
                                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end space-y-2 flex-shrink-0">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-medium text-slate-600 hover:text-red-700 px-2 py-1">Edit</a>
                                @if(Auth::id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" x-ref="deleteForm-{{ $user->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" @click="confirmDelete($refs['deleteForm-{{ $user->id }}'])" class="text-sm font-medium text-red-600 hover:text-red-900 px-2 py-1">Hapus</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center text-slate-500">
                        <p>Tidak ada data user.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Desktop View -->
                <div class="hidden md:block bg-white rounded-2xl shadow-lg border border-slate-200 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Nama</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Email</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Role</th>
                                <th class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($users as $user)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-slate-500">
                                    @php
                                    $roleClass = match($user->role) {
                                    'admin' => 'bg-red-50 text-red-700 ring-red-600/20',
                                    'wali_santri' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                    'pengajaran' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    'keasramaan' => 'bg-green-50 text-green-700 ring-green-600/20',
                                    default => 'bg-slate-50 text-slate-600 ring-slate-500/20',
                                    };
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $roleClass }}">
                                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-4">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="font-medium text-slate-600 hover:text-red-700">Edit</a>
                                    @if(Auth::id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" x-ref="deleteForm-{{ $user->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" @click="confirmDelete($refs['deleteForm-{{ $user->id }}'])" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">Tidak ada data user.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
