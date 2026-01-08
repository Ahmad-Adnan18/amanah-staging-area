<x-app-layout>
    <div class="min-h-screen bg-slate-50 font-sans" x-data="{ 
        showTingkatanModal: false,
        editingTingkatan: null, 
        newTingkatanName: '',
        openEdit(name) {
            this.editingTingkatan = name;
            this.newTingkatanName = name;
        },
        closeEdit() {
            this.editingTingkatan = null;
            this.newTingkatanName = '';
        }
    }">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                {{-- Header Section --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Mata Pelajaran</h1>
                        <p class="mt-1 text-slate-500">Kelola kurikulum, alokasi waktu, dan struktur pengajaran.</p>
                    </div>
                    <div class="flex items-center gap-3">
                         <button @click="showTingkatanModal = true"
                           class="inline-flex items-center justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
                            <svg class="-ml-0.5 mr-2 h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.593l6.202-2.071c.827-.279.933-1.36.593-2.607l-2.072-6.203a2.249 2.249 0 00-.591-2.607L9.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                              </svg>                              
                            Kelola Tingkatan
                        </button>

                         <a href="{{ route('pengajaran.mata-pelajaran.create') }}" 
                           class="inline-flex items-center justify-center rounded-lg bg-red-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700 transition-all">
                            <svg class="-ml-0.5 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                            </svg>
                            Tambah Mapel
                        </a>
                    </div>
                </div>

                {{-- Alert Notifications --}}
                @if (session('success'))
                    <div class="rounded-lg bg-green-50 p-4 border-l-4 border-green-500 shadow-sm animate-fade-in-down">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="rounded-lg bg-red-50 p-4 border-l-4 border-red-500 shadow-sm animate-fade-in-down">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Stats Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    {{-- Target Card --}}
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl p-5 text-white shadow-lg relative overflow-hidden group">
                        <div class="absolute top-0 right-0 -mt-2 -mr-2 w-20 h-20 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all duration-500"></div>
                        <div class="relative z-10">
                            <p class="text-slate-300 text-xs font-bold uppercase tracking-wider">Target Efektif</p>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="text-4xl font-bold tracking-tight">{{ $jamEfektif }}</span>
                                <span class="text-sm font-medium text-slate-300">JP / Minggu</span>
                            </div>
                            <div class="mt-4 flex items-center gap-2 text-xs text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <span>Acuan total jam pelajaran</span>
                            </div>
                        </div>
                    </div>

                    @foreach ($jpPerTingkat as $tingkat => $totalJp)
                        @php
                            $selisih = $totalJp - $jamEfektif;
                            $statusColor = $selisih === 0 ? 'emerald' : ($selisih > 0 ? 'rose' : 'amber');
                            $statusIcon = $selisih === 0 
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />' 
                                : ($selisih > 0 
                                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />' 
                                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />');
                            $statusText = $selisih === 0 ? 'Pas' : ($selisih > 0 ? '+'.$selisih.' JP' : $selisih.' JP');
                        @endphp
                        
                        <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300 relative overflow-hidden">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Tingkat {{ $tingkat }}</p>
                                    <div class="mt-2 flex items-baseline gap-2">
                                        <span class="text-3xl font-bold text-slate-800">{{ $totalJp }}</span>
                                        <span class="text-xs font-medium text-slate-400">JP</span>
                                    </div>
                                </div>
                                <div class="p-2 bg-{{ $statusColor }}-50 rounded-lg text-{{ $statusColor }}-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $statusIcon !!}
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 border border-{{ $statusColor }}-100">
                                    @if($selisih === 0)
                                        Sempurna
                                    @elseif($selisih > 0)
                                        Kelebihan {{ $selisih }} JP
                                    @else
                                        Kurang {{ abs($selisih) }} JP
                                    @endif
                                </div>
                            </div>
                            {{-- Decorator Bar --}}
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-{{ $statusColor }}-500 to-transparent opacity-20"></div>
                        </div>
                    @endforeach
                </div>

                {{-- Main Content Area --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    
                    {{-- Toolbar --}}
                    <div class="p-5 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        {{-- Search --}}
                        <div class="relative w-full sm:w-72 group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-red-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <form action="{{ route('pengajaran.mata-pelajaran.index') }}" method="GET">
                                @if(request('tingkatan'))
                                    <input type="hidden" name="tingkatan" value="{{ request('tingkatan') }}">
                                @endif
                                <input 
                                    type="text" 
                                    name="nama_pelajaran"
                                    value="{{ request('nama_pelajaran') }}"
                                    class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition-all shadow-sm"
                                    placeholder="Cari mata pelajaran..." 
                                >
                            </form>
                        </div>

                        {{-- Filters --}}
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0 no-scrollbar">
                            <a href="{{ route('pengajaran.mata-pelajaran.index', ['search' => request('search')]) }}" 
                               class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-colors border {{ !request('tingkatan') ? 'bg-red-700 text-white border-red-700' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }}">
                                Semua
                            </a>
                            @foreach($jpPerTingkat->keys()->sort() as $tingkat)
                                <a href="{{ route('pengajaran.mata-pelajaran.index', ['tingkatan' => $tingkat, 'search' => request('search')]) }}" 
                                   class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-colors border {{ request('tingkatan') == $tingkat ? 'bg-red-700 text-white border-red-700' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }}">
                                    {{ $tingkat }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Data Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tingkatan</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Durasi</th>
                                    <th scope="col" class="relative px-6 py-4"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($mataPelajarans as $mapel)
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-lg bg-red-100 text-red-700 font-bold text-lg">
                                                    {{ substr($mapel->nama_pelajaran, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-slate-900">{{ $mapel->nama_pelajaran }}</div>
                                                    <div class="text-xs text-slate-500">ID: {{ $mapel->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                                {{ $mapel->kategori }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-slate-900"> {{ $mapel->tingkatan }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <svg class="mr-1.5 h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-sm text-slate-700 font-medium">{{ $mapel->duration_jp }} JP</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('pengajaran.mata-pelajaran.edit', $mapel) }}" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg inline-flex items-center transition-colors">
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                                <p class="text-base font-medium text-slate-900">Tidak ada data ditemukan</p>
                                                <p class="text-sm text-slate-500 mt-1">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
                                                <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="mt-4 text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                                    Reset Filter
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($mataPelajarans->hasPages())
                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
                            {{ $mataPelajarans->withQueryString()->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- Modal Manajemen Tingkatan --}}
        <div x-show="showTingkatanModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div x-show="showTingkatanModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
          
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
              <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showTingkatanModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     @click.away="showTingkatanModal = false"
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                  
                  <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                      <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-50 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.593l6.202-2.071c.827-.279.933-1.36.593-2.607l-2.072-6.203a2.249 2.249 0 00-.591-2.607L9.568 3z" />
                        </svg>
                      </div>
                      <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-semibold leading-6 text-slate-900" id="modal-title">Kelola Tingkatan</h3>
                        <div class="mt-2">
                          <p class="text-sm text-slate-500 mb-4">Ganti nama tingkatan secara massal. Semua mata pelajaran yang terkait akan diperbarui.</p>
                          
                          {{-- Step 1: List View --}}
                          <div x-show="!editingTingkatan" class="space-y-2 max-h-60 overflow-y-auto">
                                @foreach($jpPerTingkat->keys()->sort() as $tingkat)
                                    <div class="flex items-center justify-between p-3 rounded-lg border border-slate-100 bg-slate-50 hover:bg-white hover:shadow-sm transition-all group">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                                {{ substr($tingkat, 0, 2) }}
                                            </div>
                                            <span class="font-medium text-slate-700">{{ $tingkat }}</span>
                                        </div>
                                        <button @click="openEdit('{{ $tingkat }}')" class="p-2 text-slate-400 hover:text-red-600 rounded-full hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                    </div>
                                @endforeach
                          </div>

                          {{-- Step 2: Edit Form --}}
                          <div x-show="editingTingkatan" class="bg-slate-50 rounded-lg p-4 border border-slate-200" style="display: none;">
                                <form action="{{ route('pengajaran.mata-pelajaran.update_tingkatan') }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="old_tingkatan" :value="editingTingkatan">
                                    
                                    <label class="block text-xs font-medium text-slate-500 uppercase">Mengubah Nama Tingkatan</label>
                                    <div class="mt-1 font-semibold text-slate-900 mb-4 text-lg" x-text="editingTingkatan"></div>

                                    <label for="new_tingkatan" class="block text-sm font-medium text-slate-700 mb-1">Menjadi</label>
                                    <input type="text" name="new_tingkatan" id="new_tingkatan" x-model="newTingkatanName" required
                                           class="block w-full rounded-md border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                           placeholder="Masukkan nama baru...">
                                    
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button type="button" @click="closeEdit()" class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">Batal</button>
                                        <button type="submit" class="inline-flex justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">Simpan</button>
                                    </div>
                                </form>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6" x-show="!editingTingkatan">
                    <button type="button" @click="showTingkatanModal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Tutup</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

    </div>
</x-app-layout>