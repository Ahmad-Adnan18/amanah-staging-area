<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="max-w-7xl mx-auto space-y-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-red-700">Daftar Santri Kelas: {{ $kelas->nama_kelas }}</h1>
                    <p class="mt-1 text-slate-600">Kelola semua santri di kelas ini.</p>
                </div>

                @can('create', App\Models\Santri::class)
                <a href="{{ route('pengajaran.santris.create', $kelas) }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                    <span>Tambah Santri ke Kelas Ini</span>
                </a>
                @endcan
            </div>

            <!-- Form Pencarian Santri -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4">
                <form action="{{ route('pengajaran.santris.index', $kelas) }}" method="GET">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-grow">
                            <label for="search" class="sr-only">Cari Santri</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6" placeholder="Cari santri di kelas ini...">
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Cari</button>
                            <a href="{{ route('pengajaran.santris.index', $kelas) }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel Santri -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Santri</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Rayon</th>
                                <th scope="col" class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($santris as $santri)
                            <tr class="hover:bg-slate-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $santri->foto ? Storage::url($santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($santri->nama).'&background=FBBF24&color=fff&font-size=0.4' }}" alt="Avatar">
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('santri.profil.show', $santri) }}" class="text-sm font-medium text-slate-900 hover:text-red-700">
                                                {{ $santri->nama }}
                                            </a>
                                            <div class="text-sm text-slate-500">{{ $santri->nis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $santri->rayon }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-4">
                                        @can('update', $santri)
                                        <a href="{{ route('pengajaran.santris.edit', $santri) }}" class="text-slate-600 hover:text-red-700 transition-colors">Edit</a>
                                        @endcan
                                        @can('delete', $santri)
                                        <form action="{{ route('pengajaran.santris.destroy', $santri) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">Hapus</button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-sm text-slate-500">
                                    Tidak ada santri di kelas ini atau tidak ada yang cocok dengan pencarian Anda.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($santris->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50">
                    {{ $santris->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
