<x-app-layout>
    {{-- Alpine.js untuk membuat halaman lebih interaktif --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            {{-- Membungkus semua konten dalam komponen Alpine.js --}}
            <div x-data="kurikulumTemplateManager()">

                <div class="space-y-8">
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Atur Mata Pelajaran untuk Template</h1>
                        <p class="mt-1 text-slate-600">
                            Template: <span class="font-semibold text-red-700">{{ $template->nama_template }}</span>
                        </p>
                        <p class="mt-1 text-sm text-slate-500">Centang semua mata pelajaran yang termasuk dalam kurikulum ini.</p>
                        
                        <div class="mt-4 flex items-center flex-wrap gap-2">
                            <span class="text-sm font-medium text-gray-700 mr-2">Filter:</span>
                            <button @click="activeFilter = 'all'" 
                                    :class="activeFilter === 'all' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'"
                                    class="px-3 py-1 rounded-full text-xs font-medium transition-colors">
                                Semua
                            </button>
                            {{-- [PENYESUAIAN] Looping filter dari data dinamis --}}
                            @foreach($tingkatans as $tingkat)
                                <button @click="activeFilter = '{{ $tingkat }}'"
                                        :class="activeFilter === '{{ $tingkat }}' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'"
                                        class="px-3 py-1 rounded-full text-xs font-medium transition-colors">
                                    {{ $tingkat == 'Umum' ? 'Umum' : 'Tingkat ' . $tingkat }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <form action="{{ route('akademik.kurikulum.template.update', $template) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                            <div class="p-6 min-h-[300px] max-h-[65vh] overflow-y-auto space-y-6">
                                @forelse ($groupedMapel as $tingkatan => $mataPelajarans)
                                    <div x-show="activeFilter === 'all' || activeFilter === '{{ $tingkatan }}'" x-transition>
                                        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-3">
                                            <h3 class="text-lg font-semibold text-slate-800">
                                                {{ $tingkatan == 'Umum' ? 'Mata Pelajaran Umum' : 'Tingkat ' . $tingkatan }}
                                                <span class="text-sm font-normal text-slate-500" x-text="`(${getSelectionCount('{{ $tingkatan }}')}/${getMapelCount('{{ $tingkatan }}')} terpilih)`"></span>
                                            </h3>
                                            <div class="flex items-center space-x-3 text-xs font-medium mt-2 sm:mt-0">
                                                <button type="button" @click="toggleAll('{{ $tingkatan }}', true)" class="text-red-600 hover:text-red-800">Pilih Semua</button>
                                                <span class="text-slate-300">|</span>
                                                <button type="button" @click="toggleAll('{{ $tingkatan }}', false)" class="text-slate-500 hover:text-slate-700">Lepas Semua</button>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                            @foreach ($mataPelajarans as $mapel)
                                                <label class="flex items-center p-3 rounded-lg hover:bg-slate-50 cursor-pointer border border-slate-200 has-[:checked]:bg-red-50 has-[:checked]:border-red-300 transition-colors duration-200">
                                                    <input type="checkbox" 
                                                           name="mata_pelajaran_ids[]" 
                                                           value="{{ $mapel->id }}"
                                                           x-model="selectedMapelIds"
                                                           data-tingkatan="{{ $tingkatan }}"
                                                           class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                    <span class="ml-3 text-sm font-medium text-slate-800">{{ $mapel->nama_pelajaran }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full text-center text-slate-500 py-12">
                                        <p class="font-semibold">Tidak ada data mata pelajaran.</p>
                                        <p class="text-sm mt-1">Silakan tambahkan di menu "Manajemen Mata Pelajaran" terlebih dahulu.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Tombol simpan yang "melayang" --}}
                        <div class="sticky bottom-0 z-10 py-4 mt-8">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-slate-200 px-6 py-4 flex justify-end gap-4">
                                    <a href="{{ route('akademik.kurikulum.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                        Kembali
                                    </a>
                                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                        Simpan Template
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function kurikulumTemplateManager() {
            return {
                activeFilter: 'all',
                selectedMapelIds: @json(array_map('strval', $assignedMapelIds)), // Convert IDs to string for reliable comparison in JS
                mapelCounts: @json($groupedMapel->map->count()),

                getMapelCount(tingkatan) {
                    return this.mapelCounts[tingkatan] || 0;
                },

                getSelectionCount(tingkatan) {
                    const mapelElements = document.querySelectorAll(`input[data-tingkatan="${tingkatan}"]`);
                    let count = 0;
                    mapelElements.forEach(el => {
                        // Ensure comparison is consistent (string vs string)
                        if (this.selectedMapelIds.includes(String(el.value))) {
                            count++;
                        }
                    });
                    return count;
                },

                toggleAll(tingkatan, select) {
                    const mapelElements = document.querySelectorAll(`input[data-tingkatan="${tingkatan}"]`);
                    mapelElements.forEach(el => {
                        const mapelId = String(el.value); // Ensure it's a string
                        const index = this.selectedMapelIds.indexOf(mapelId);

                        if (select && index === -1) {
                            this.selectedMapelIds.push(mapelId);
                        } else if (!select && index !== -1) {
                            this.selectedMapelIds.splice(index, 1);
                        }
                    });
                }
            }
        }
    </script>
</x-app-layout>
