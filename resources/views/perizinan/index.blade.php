<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8" x-data="{
                    selectedIds: [],
                    get allIdsOnPage() { return {{ json_encode($perizinans->pluck('id')) }}; },
                    get areAllSelected() { return this.allIdsOnPage.length > 0 && this.selectedIds.length === this.allIdsOnPage.length; },
                    toggleSelectAll() {
                        if (this.areAllSelected) {
                            this.selectedIds = [];
                        } else {
                            this.selectedIds = this.allIdsOnPage.slice();
                        }
                    }
                 }">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Daftar Santri Izin Aktif</h1>
                    <p class="mt-1 text-slate-600">Berikut adalah daftar santri yang sedang tidak berada di pondok atau tidak mengikuti KBM.</p>
                </div>
                <a href="{{ route('admin.santri-management.index') }}" class="flex-shrink-0 w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                    <span>Buat Izin Baru</span>
                </a>

                @can('delete', App\Models\Perizinan::newModelInstance())
                <div x-show="selectedIds.length > 0" class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 flex items-center justify-between" x-cloak>
                    <p class="text-sm font-medium"><span x-text="selectedIds.length"></span> data dipilih</p>
                    <form action="{{ route('perizinan.bulkDestroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data yang dipilih?')">
                        @csrf
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800">Hapus yang Dipilih</button>
                    </form>
                </div>
                @endcan

                <div class="md:hidden space-y-4">
                    @can('delete', App\Models\Perizinan::newModelInstance())
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 flex items-center gap-4">
                        <input type="checkbox" @click="toggleSelectAll()" :checked="areAllSelected" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                        <label @click="toggleSelectAll()" class="text-sm font-medium text-slate-700 cursor-pointer select-none">Pilih Semua</label>
                    </div>
                    @endcan

                    @forelse ($perizinans as $izin)
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4">
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex items-center gap-3 flex-1">
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $izin->santri->foto ? Storage::url($izin->santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($izin->santri->nama).'&background=FBBF24&color=fff&font-size=0.4' }}" alt="Avatar">
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $izin->santri->nama }}</p>
                                    <p class="text-xs text-slate-500">{{ $izin->santri->nis }} / {{ $izin->santri->kelas->nama_kelas ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @can('delete', $izin)
                            <input type="checkbox" x-model="selectedIds" value="{{ $izin->id }}" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 mt-1">
                            @endcan
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-200 space-y-3">
                            <div>
                                <p class="text-xs text-slate-500">Jenis Izin</p>
                                <p class="text-sm font-medium text-slate-800">{{ $izin->jenis_izin }}</p>
                                <p class="text-sm text-slate-600">{{ $izin->keterangan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Tanggal</p>
                                <p class="text-sm text-slate-600">Mulai: {{ $izin->tanggal_mulai->format('d M Y') }}</p>
                                @if($izin->tanggal_akhir)
                                <p class="text-sm text-slate-600">Kembali: {{ $izin->tanggal_akhir->format('d M Y') }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Dicatat Oleh</p>
                                <p class="text-sm text-slate-600">{{ $izin->pembuat->name }} ({{ ucwords($izin->kategori) }})</p>
                            </div>
                        </div>
                        @can('delete', $izin)
                        <div class="mt-4 pt-4 border-t border-slate-200 text-right">
                            <form action="{{ route('perizinan.destroy', $izin) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </div>
                        @endcan
                    </div>
                    @empty
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center text-slate-500">
                        <p>Tidak ada santri yang sedang izin saat ini.</p>
                    </div>
                    @endforelse
                </div>

                <div class="hidden md:block bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    @can('delete', App\Models\Perizinan::newModelInstance())
                                    <th class="pl-4 pr-3 py-3.5">
                                        <input type="checkbox" @click="toggleSelectAll()" :checked="areAllSelected" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                    </th>
                                    @endcan
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Santri</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Jenis Izin</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                    <th scope="col" class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($perizinans as $izin)
                                <tr class="hover:bg-slate-50">
                                    @can('delete', $izin)
                                    <td class="pl-4 pr-3 py-4">
                                        <input type="checkbox" x-model="selectedIds" value="{{ $izin->id }}" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                    </td>
                                    @endcan
                                    {{-- Sisa dari tabel tetap sama --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $izin->santri->foto ? Storage::url($izin->santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($izin->santri->nama).'&background=FBBF24&color=fff&font-size=0.4' }}" alt="Avatar">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-slate-900">{{ $izin->santri->nama }}</div>
                                                <div class="text-sm text-slate-500">{{ $izin->santri->nis }} / {{ $izin->santri->kelas->nama_kelas ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-900">{{ $izin->jenis_izin }}</div>
                                        <div class="text-xs text-slate-500 truncate max-w-xs">{{ $izin->keterangan }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        Mulai: {{ $izin->tanggal_mulai->format('d M Y') }} <br>
                                        @if($izin->tanggal_akhir)
                                        Kembali: {{ $izin->tanggal_akhir->format('d M Y') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $izin->pembuat->name }} ({{ ucwords($izin->kategori) }})</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @can('delete', $izin)
                                        <form action="{{ route('perizinan.destroy', $izin) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">Tidak ada santri yang sedang izin saat ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($perizinans->hasPages())
                <div class="p-4 bg-white rounded-2xl shadow-lg border border-slate-200">
                    {{ $perizinans->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
