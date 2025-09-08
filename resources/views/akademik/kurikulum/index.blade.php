<x-app-layout>
    {{-- [TAMBAHAN] Link ke CSS untuk Tom-Select, agar pilihan lebih modern --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Kurikulum Terpadu</h1>
                <p class="mt-1 text-slate-600">Atur kurikulum per kelas atau kelola template untuk diterapkan ke banyak kelas.</p>
            </div>

            @if (session('success') || session('error'))
                @php
                    $type = session('success') ? 'success' : 'error';
                    $message = session('success') ?? session('error');
                @endphp
                <div class="relative rounded-lg border-l-4 p-4 {{ $type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700' }}" role="alert">
                    <p class="font-bold">{{ $type === 'success' ? 'Berhasil!' : 'Terjadi Kesalahan!' }}</p>
                    <p class="text-sm">{{ $message }}</p>
                </div>
            @endif

            <div x-data="{ activeTab: 'per_kelas' }">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                        <button @click="activeTab = 'per_kelas'" :class="{ 'border-red-500 text-red-600': activeTab === 'per_kelas', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'per_kelas' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Atur Kurikulum per Kelas</button>
                        <button @click="activeTab = 'template'" :class="{ 'border-red-500 text-red-600': activeTab === 'template', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'template' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Manajemen Template</button>
                    </nav>
                </div>

                <div class="mt-6">
                    <!-- Tab untuk mengatur kurikulum per kelas -->
                    <div x-show="activeTab === 'per_kelas'" x-cloak>
                        <div x-data="{
                                selectedKelas: '',
                                kurikulum: [],
                                isLoading: false,
                                fetchKurikulum() {
                                    if (!this.selectedKelas) {
                                        this.kurikulum = [];
                                        return;
                                    }
                                    this.isLoading = true;
                                    fetch(`/akademik/kurikulum/${this.selectedKelas}/mapel-json`)
                                        .then(res => res.json())
                                        .then(data => {
                                            this.kurikulum = data;
                                            this.isLoading = false;
                                        });
                                }
                            }" class="bg-white rounded-2xl shadow-lg border border-slate-200">
                            
                            <div class="p-6">
                                <label for="kelas_id_selector" class="block text-sm font-medium text-gray-700">Pilih Kelas untuk Diatur</label>
                                <select id="kelas_id_selector" x-model="selectedKelas" @change="fetchKurikulum()" class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="isLoading" class="p-6 text-center text-slate-500">
                                <p>Memuat data...</p>
                            </div>

                            <form x-show="selectedKelas && !isLoading" action="{{ route('akademik.kurikulum.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="kelas_id" x-model="selectedKelas">
                                
                                <div class="divide-y divide-slate-200">
                                    <div class="px-6 py-3 bg-slate-50 grid grid-cols-2 gap-4">
                                        <div class="text-left text-xs font-semibold text-slate-500 uppercase">Mata Pelajaran</div>
                                        <div class="text-left text-xs font-semibold text-slate-500 uppercase">Guru Spesifik (Opsional)</div>
                                    </div>
                                    <template x-if="kurikulum.length === 0">
                                        <p class="p-6 text-center text-slate-500">Kurikulum untuk kelas ini belum diatur. Terapkan template terlebih dahulu.</p>
                                    </template>
                                    <template x-for="mapel in kurikulum" :key="mapel.id">
                                        <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                            <label class="flex items-center space-x-3">
                                                <input type="checkbox" :name="'kurikulum[' + mapel.id + '][assigned]'" x-model="mapel.assigned" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                <span x-text="mapel.nama_pelajaran"></span>
                                            </label>
                                            <div x-show="mapel.assigned">
                                                <select :name="'kurikulum[' + mapel.id + '][teacher_id]'" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                                                    <option value="">-- Pilih Otomatis --</option>
                                                    <template x-for="teacher in mapel.teachers" :key="teacher.id">
                                                        <option :value="teacher.id" :selected="teacher.id == mapel.assigned_teacher_id" x-text="teacher.name"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                
                                <div x-show="kurikulum.length > 0" class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                        Simpan Kurikulum
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div x-show="activeTab === 'template'" x-cloak>
                         <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div>
                                <form action="{{ route('akademik.kurikulum.apply') }}" method="POST">
                                    @csrf
                                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 h-full flex flex-col">
                                        <div class="p-6">
                                            <h3 class="text-lg font-bold text-gray-900">Terapkan Template ke Kelas</h3>
                                            <div class="mt-4 space-y-4">
                                                <div>
                                                    <label for="template_id" class="block text-sm font-medium text-gray-700">1. Pilih Template Kurikulum</label>
                                                    <select name="template_id" id="template_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                                        <option value="">-- Pilih Template --</option>
                                                        @foreach ($templates as $template)
                                                            <option value="{{ $template->id }}">{{ $template->nama_template }} ({{ $template->mata_pelajarans_count }} Mapel)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="kelas-ids-selector" class="block text-sm font-medium text-gray-700">2. Pilih Satu atau Lebih Kelas</label>
                                                    <select name="kelas_ids[]" id="kelas-ids-selector" multiple placeholder="Cari dan pilih kelas..." autocomplete="off" class="mt-1">
                                                        @foreach ($kelasList as $kelas)
                                                        <option value="{{ $kelas->id }}">
                                                            {{ $kelas->nama_kelas }}
                                                            @if($kelas->kurikulumTemplate)
                                                                (Template: {{ $kelas->kurikulumTemplate->nama_template }})
                                                            @endif
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow"></div>
                                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                                Terapkan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>  
                            <!-- Kolom Manajemen Template -->
                            <div>
                                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                                    <form action="{{ route('akademik.kurikulum.template.store') }}" method="POST" class="p-6 border-b border-slate-200 bg-slate-50">
                                        @csrf
                                        <label for="nama_template" class="block text-sm font-medium text-gray-900">Buat Template Baru</label>
                                        <div class="mt-1 flex gap-3">
                                            <input id="nama_template" name="nama_template" type="text" class="block w-full flex-grow rounded-md border-gray-300" placeholder="Contoh: Kurikulum Tingkat 1" required />
                                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Buat</button>
                                        </div>
                                    </form>
                                    <ul class="divide-y divide-slate-200">
                                        @forelse ($templates as $template)
                                            <li class="px-6 py-4 flex justify-between items-center gap-3">
                                                <div>
                                                    <p class="font-semibold text-slate-800">{{ $template->nama_template }}</p>
                                                    <p class="text-sm text-slate-500">{{ $template->mata_pelajarans_count }} Mapel</p>
                                                </div>
                                                <div class="flex items-center space-x-4">
                                                    <a href="{{ route('akademik.kurikulum.template.edit', $template) }}" class="font-medium text-slate-600 hover:text-red-700">Atur Mapel</a>
                                                    <form action="{{ route('akademik.kurikulum.template.destroy', $template) }}" method="POST" onsubmit="return confirm('Yakin?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                                                    </form>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="px-6 py-12 text-center text-slate-500">Belum ada template.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- [TAMBAHAN] Script untuk menjalankan Tom-Select --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#kelas-ids-selector', {
                plugins: ['remove_button'],
            });
        });
    </script>
</x-app-layout>