<x-app-layout>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endpush

    <style>
        [x-cloak] {
            display: none !important;
        }

    </style>

    <div class="p-4 sm:p-6 lg:p-8">
        <div class="max-w-7xl mx-auto space-y-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-red-700">Portofolio Santri</h1>
                    <p class="mt-1 text-slate-600">Pilih santri untuk melihat portofolio lengkap.</p>
                </div>
                <div>
                    <a href="{{ route('pengajaran.kelas.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                        Data Kelas
                    </a>
                </div>
            </div>

            <!-- Form Pencarian -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4">
                <form action="{{ route('santri.portofolio.list') }}" method="GET">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-grow">
                            <label for="search" class="sr-only">Cari Santri</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6" placeholder="Cari santri berdasarkan nama atau NIS...">
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Cari
                            </button>
                            <a href="{{ route('santri.portofolio.list') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel Santri (Desktop) -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Santri</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelas</th>
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
                                            <div class="text-sm font-medium text-slate-900">{{ $santri->nama }}</div>
                                            <div class="text-sm text-slate-500">{{ $santri->nis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $santri->kelas->nama_kelas ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $santri->rayon }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-3">
                                        {{-- <a href="{{ route('santri.profil.show', $santri) }}" class="text-slate-600 hover:text-red-700 transition-colors">
                                        Profil
                                        </a> --}}
                                        <a href="{{ route('santri.profil.portofolio', $santri) }}" class="inline-flex items-center gap-1 rounded-md bg-red-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Portofolio
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">
                                    Tidak ada santri yang cocok dengan pencarian Anda.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden grid grid-cols-1 gap-4 p-4">
                    @forelse ($santris as $santri)
                    <div class="bg-white rounded-xl shadow-lg border border-slate-200 flex flex-col">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $santri->foto ? Storage::url($santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($santri->nama).'&background=FBBF24&color=fff&font-size=0.4' }}" alt="Avatar">
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-bold text-lg text-slate-800">{{ $santri->nama }}</h3>
                                    <p class="text-xs text-slate-500">NIS: {{ $santri->nis }}</p>
                                </div>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-2 text-sm">
                                <div class="bg-slate-100 p-2 rounded-lg text-center">
                                    <div class="text-xs text-slate-500">Kelas</div>
                                    <div class="font-medium text-slate-800">{{ $santri->kelas->nama_kelas ?? 'N/A' }}</div>
                                </div>
                                <div class="bg-slate-100 p-2 rounded-lg text-center">
                                    <div class="text-xs text-slate-500">Rayon</div>
                                    <div class="font-medium text-slate-800">{{ $santri->rayon }}</div>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end space-x-3">
                                {{-- <a href="{{ route('santri.profil.show', $santri) }}" class="inline-flex items-center justify-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Profil
                                </a>--}}
                                <a href="{{ route('santri.profil.portofolio', $santri) }}" class="inline-flex items-center justify-center gap-1 rounded-md bg-red-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Portofolio
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-1 py-12 text-center text-slate-500">
                        Tidak ada santri yang cocok dengan pencarian Anda.
                    </div>
                    @endforelse
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
