<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Data Master Kelas</h1>
                            <p class="mt-1 text-slate-600">Kelola semua kelas yang ada di pondok.</p>
                        </div>
                        @can('create', App\Models\Kelas::class)
                        <a href="{{ route('pengajaran.kelas.create') }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                            <span>Tambah Kelas</span>
                        </a>
                        @endcan
                    </div>
                </div>

                @if (session('success') || session('error'))
                    @php
                        $type = session('success') ? 'success' : 'error';
                        $message = session('success') ?? session('error');
                        $typeClasses = [
                            'success' => 'bg-green-100 border-green-400 text-green-700',
                            'error' => 'bg-red-100 border-red-400 text-red-700',
                        ];
                        $iconPaths = [
                            'success' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                            'error' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
                        ];
                        $hoverClasses = [
                            'success' => 'hover:bg-green-200',
                            'error' => 'hover:bg-red-200',
                        ];
                    @endphp
                    <div id="notification-panel" class="relative rounded-lg border-l-4 p-4 {{ $typeClasses[$type] }}" role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPaths[$type] }}" /></svg>
                            </div>
                            <div class="ml-3">
                                <p class="font-bold">{{ $type === 'success' ? 'Berhasil!' : 'Terjadi Kesalahan!' }}</p>
                                <p class="text-sm">{{ $message }}</p>
                            </div>
                            <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 {{ $hoverClasses[$type] }} inline-flex h-8 w-8" onclick="document.getElementById('notification-panel').style.display='none'" aria-label="Close">
                                <span class="sr-only">Dismiss</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Form Pencarian & Aksi Massal -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4">
                    <form action="{{ route('pengajaran.kelas.index') }}" method="GET" class="mb-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-grow relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg></div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-md border-gray-300 pl-10 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari santri berdasarkan nama atau NIS...">
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Cari</button>
                                <a href="{{ route('pengajaran.kelas.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Reset</a>
                            </div>
                        </div>
                    </form>
                    @can('create', App\Models\Kelas::class)
                    <div class="border-t border-slate-200 pt-4 flex flex-col sm:flex-row items-center gap-3">
                        <p class="text-sm text-slate-600 flex-shrink-0">Aksi Massal Kode Wali:</p>
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('pengajaran.kelas.generate_all_wali_codes') }}" method="POST" onsubmit="return confirm('Anda yakin ingin membuat kode untuk semua santri yang belum memilikinya? Proses ini tidak bisa dibatalkan.')">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    Generate Semua Kode
                                </button>
                            </form>
                            <a href="{{ route('pengajaran.kelas.export_wali_codes') }}" class="inline-flex items-center justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                                Export Excel
                            </a>
                        </div>
                    </div>
                    @endcan
                </div>

                <!-- Hasil Pencarian Santri -->
                @if(request('search'))
                    @if($hasilPencarianSantri->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                        <div class="p-4 border-b border-slate-200"><h3 class="font-semibold text-gray-800">Hasil Pencarian untuk "{{ request('search') }}"</h3></div>
                        <div class="divide-y divide-slate-200">
                            @foreach ($hasilPencarianSantri as $santri)
                                <div class="p-4 flex justify-between items-center hover:bg-slate-50">
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $santri->nama }}</p>
                                        <p class="text-sm text-slate-500">NIS: {{ $santri->nis ?? '-' }} | Kelas: {{ $santri->kelas->nama_kelas ?? 'N/A' }}</p>
                                    </div>
                                    <a href="{{ route('santri.profil.show', $santri) }}" class="text-sm font-medium text-red-600 hover:text-red-800">Lihat Profil &rarr;</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="border-l-4 border-yellow-400 bg-yellow-50 p-4 rounded-r-lg"><p class="text-sm text-yellow-700">Santri dengan nama atau NIS "{{ request('search') }}" tidak ditemukan.</p></div>
                    @endif
                @endif

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Nama Kelas</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Penanggung Jawab</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Ruangan Induk</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Status Jadwal</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Jumlah Santri</th>
                                    <th class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($kelas_list as $kelas)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 font-medium text-slate-900 align-top">
                                        {{ $kelas->nama_kelas }}
                                        <div class="text-xs text-slate-500 font-normal">Tingkatan: {{ $kelas->tingkatan ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 align-top">
                                        @if($kelas->penanggungJawab->isNotEmpty())
                                            <ul class="text-xs space-y-1">
                                            @foreach($kelas->penanggungJawab as $pj)
                                                <li>
                                                    <span class="font-semibold text-slate-600">{{ $pj->jabatan->nama_jabatan ?? 'N/A' }}:</span>
                                                    <span>{{ $pj->user->name ?? 'N/A' }}</span>
                                                </li>
                                            @endforeach
                                            </ul>
                                        @else
                                            <span class="text-slate-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 align-top">{{ $kelas->room->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 align-top">
                                        @if($kelas->is_active_for_scheduling)
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Aktif</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 align-top">{{ $kelas->santris_count }} santri</td>
                                    <td class="px-6 py-4 text-right space-x-4 align-top">
                                        <a href="{{ route('pengajaran.santris.index', $kelas) }}" class="font-medium text-red-600 hover:text-red-800">Lihat Santri</a>
                                        @can('update', $kelas)
                                        <a href="{{ route('pengajaran.kelas.edit', $kelas) }}" class="font-medium text-slate-600 hover:text-slate-900">Edit</a>
                                        @endcan
                                        @can('delete', $kelas)
                                        <form action="{{ route('pengajaran.kelas.destroy', $kelas) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus? Semua santri di kelas ini juga akan terhapus.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada data kelas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="md:hidden p-4 space-y-4">
                        @forelse ($kelas_list as $kelas)
                        <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $kelas->nama_kelas }}</p>
                                    <p class="text-sm text-slate-500">{{ $kelas->santris_count }} santri</p>
                                </div>
                                @can('update', $kelas)
                                <a href="{{ route('pengajaran.kelas.edit', $kelas) }}" class="text-slate-500 hover:text-slate-800"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></a>
                                @endcan
                            </div>
                            <div class="mt-2 text-sm space-y-2">
                                <p class="text-slate-600"><strong>Tingkatan:</strong> {{ $kelas->tingkatan ?? 'N/A' }}</p>
                                <p class="text-slate-600"><strong>Ruangan:</strong> {{ $kelas->room->name ?? 'N/A' }}</p>
                                <div class="text-slate-600">
                                    <p class="font-bold">Penanggung Jawab:</p>
                                    @if($kelas->penanggungJawab->isNotEmpty())
                                        <ul class="list-disc list-inside ml-2">
                                        @foreach($kelas->penanggungJawab as $pj)
                                            <li>{{ $pj->jabatan->nama_jabatan ?? 'N/A' }}: {{ $pj->user->name ?? 'N/A' }}</li>
                                        @endforeach
                                        </ul>
                                    @else
                                        -
                                    @endif
                                </div>
                                <p class="text-slate-600 flex items-center gap-2"><strong>Status Jadwal:</strong> 
                                    @if($kelas->is_active_for_scheduling)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">Nonaktif</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-slate-200 flex justify-between items-center">
                                <a href="{{ route('pengajaran.santris.index', $kelas) }}" class="text-sm font-medium text-red-600 hover:text-red-800">Lihat Daftar Santri &rarr;</a>
                                @can('delete', $kelas)
                                <form action="{{ route('pengajaran.kelas.destroy', $kelas) }}" method="POST" onsubmit="return confirm('Yakin hapus? Semua santri di kelas ini juga akan terhapus.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg></button>
                                </form>
                                @endcan
                            </div>
                        </div>
                        @empty
                        <div class="py-12 text-center text-slate-500">Belum ada data kelas.</div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-8">
                    {{ $kelas_list->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
