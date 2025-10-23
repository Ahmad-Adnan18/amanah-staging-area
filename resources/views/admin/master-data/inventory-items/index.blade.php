<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Inventaris Ruangan: {{ $room->name }}</h1>
                            <p class="mt-1 text-slate-600">Daftar inventaris untuk ruangan ini.</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('admin.rooms.index') }}"
                               class="inline-flex items-center justify-center rounded-md bg-slate-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-colors w-full sm:w-auto">
                                Kembali
                            </a>
                            <a href="{{ route('admin.rooms.inventory.create', $room) }}"
                               class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors w-full sm:w-auto">
                                Tambah Inventaris Baru
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Daftar Inventaris -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama Item</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jumlah</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Deskripsi</th>
                                <th scope="col" class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($inventoryItems as $item)
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $item->name }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $item->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-right space-x-4">
                                    <a href="{{ route('admin.rooms.inventory.edit', [$room, $item]) }}"
                                       class="font-medium text-slate-600 hover:text-red-700 transition-colors">Edit</a>
                                    <form action="{{ route('admin.rooms.inventory.destroy', [$room, $item]) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="font-medium text-red-600 hover:text-red-900 transition-colors">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada inventaris yang ditambahkan untuk ruangan ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>