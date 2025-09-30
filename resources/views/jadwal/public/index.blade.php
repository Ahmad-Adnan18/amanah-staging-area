<x-app-layout>
    {{-- Dependensi untuk CSS & JS --}}
    @push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-4 sm:py-8 px-4 sm:px-6 lg:px-8" x-data="scheduleViewer">

            {{-- Header Halaman --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6 mb-6 sm:mb-8">
                {{-- [MOBILE] Ukuran teks diperkecil di layar kecil --}}
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Lihat Jadwal Pelajaran</h1>
                <p class="mt-1 text-slate-600 text-sm sm:text-base">Pilih untuk menampilkan jadwal berdasarkan kelas atau guru.</p>
            </div>

            {{-- Menu Tab dan Pilihan --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <div class="border-b border-gray-200">
                    {{-- [MOBILE] Navigasi dibuat bisa di-scroll horizontal jika tidak muat --}}
                    <nav class="-mb-px flex space-x-6 sm:space-x-8 px-4 sm:px-6 overflow-x-auto" aria-label="Tabs">
                        <button @click="setActiveTab('kelas')" :class="{ 'border-red-500 text-red-600': activeTab === 'kelas', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'kelas' }" class="flex-shrink-0 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Jadwal per Kelas</button>
                        <button @click="setActiveTab('guru')" :class="{ 'border-red-500 text-red-600': activeTab === 'guru', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'guru' }" class="flex-shrink-0 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Jadwal per Guru</button>
                    </nav>
                </div>
                <div class="p-4 sm:p-6">
                    <div x-show="activeTab === 'kelas'" x-cloak>
                        <label for="kelas_select" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                        <select x-ref="kelasSelect" id="kelas_select" placeholder="Cari dan pilih kelas..."></select>
                    </div>
                    <div x-show="activeTab === 'guru'" x-cloak>
                        <label for="guru_select" class="block text-sm font-medium text-gray-700 mb-1">Pilih Guru</label>
                        <select x-ref="guruSelect" id="guru_select" placeholder="Cari dan pilih guru..."></select>
                    </div>
                </div>
            </div>

            {{-- Panel Instruksi Awal --}}
            <div x-show="!selectedItem" class="mt-6 sm:mt-8 text-center bg-white rounded-2xl shadow-lg border border-slate-200 p-8 sm:p-12" x-cloak>
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Pilih Jadwal untuk Ditampilkan</h3>
                <p class="mt-1 text-sm text-gray-500">Silakan pilih kelas atau guru dari menu di atas untuk melihat detail jadwal pelajaran.</p>
            </div>

            {{-- Area Konten Jadwal --}}
            <div x-show="selectedItem" class="mt-6 sm:mt-8 bg-white rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6" x-cloak>

                {{-- [MOBILE] Layout diubah jadi flex-col di mobile, dan sm:flex-row di desktop --}}
                <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-bold text-slate-800 text-center sm:text-left" x-text="scheduleTitle"></h3>
                    
                    {{-- [MOBILE] Tombol-tombol dibuat vertikal dan full-width di mobile --}}
                    <div class="w-full sm:w-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <a :href="printUrl" target="_blank" class="inline-flex items-center gap-2 justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.552c.377.046.752.097 1.128.152A2.25 2.25 0 0118 8.678v4.588A2.25 2.25 0 0115.75 15.5h-3.48a3.748 3.748 0 01-1.048.06c-.34.023-.681.042-1.022.06h-3.48A2.25 2.25 0 012 13.266V8.678c0-.986.62-1.84 1.52-2.174a41.34 41.34 0 011.128-.152V2.75zM6.5 2.5a.25.25 0 00-.25.25v3.5c0 .138.112.25.25.25h6.5a.25.25 0 00.25-.25v-3.5a.25.25 0 00-.25-.25h-6.5zM3.5 8.678v4.588c0 .138.112.25.25.25h2.25v-2.5a.75.75 0 01.75-.75h6.5a.75.75 0 01.75.75v2.5h2.25a.25.25 0 00.25-.25V8.678a.75.75 0 00-.507-.704 41.52 41.52 0 00-1.216-.173.75.75 0 00-.727.69v.252a.75.75 0 01-.75-.75h-6.5a.75.75 0 01-.75-.75v-.252a.75.75 0 00-.727-.69 41.52 41.52 0 00-1.216.173A.75.75 0 003.5 8.678z" clip-rule="evenodd" /></svg>
                            <span>Cetak</span>
                        </a>
                    </div>
                </div>


                {{-- Tampilan KARTU khusus untuk GURU --}}
                <div x-show="viewMode === 'card' && activeTab === 'guru'" x-cloak>
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold" style="font-family: 'Times New Roman', serif;">جَدْوَلُ التَدْرِيْس</h2>
                        <h3 class="text-xl font-semibold" x-text="scheduleTitle.replace('Jadwal Mengajar:', 'Al Ustadz/ah')"></h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-12">
                        @foreach ($days as $dayKey => $dayName)
                        <div class="p-4">
                            <h4 class="text-center font-bold text-lg mb-4">{{ strtoupper($dayName) }}</h4>
                            <div class="space-y-3">
                                @foreach ($timeSlots as $timeSlot)
                                <div class="flex items-center text-sm">
                                    <span class="w-6 font-mono">{{ $timeSlot }}.</span>
                                    <div class="flex-1 border-b border-dotted border-slate-400 pb-1">
                                        <template x-if="scheduleToShow[{{ $dayKey }}] && scheduleToShow[{{ $dayKey }}][{{ $timeSlot }}]">
                                            <span class="flex justify-between">
                                                <span x-text="scheduleToShow[{{ $dayKey }}][{{ $timeSlot }}].subject"></span>
                                                <span class="font-semibold" x-text="scheduleToShow[{{ $dayKey }}][{{ $timeSlot }}].class"></span>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        <h5 class="font-bold mb-3 text-center text-sm">REKAPITULASI JAM MENGAJAR</h5>
                        
                        {{-- Tampilan Tabel untuk DESKTOP --}}
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="w-full border-collapse text-xs">
                                <thead>
                                    <tr>
                                        <th class="border border-slate-400 px-2 py-1">HARI</th>
                                        <th class="border border-slate-400 px-2 py-1">SABTU</th>
                                        <th class="border border-slate-400 px-2 py-1">AHAD</th>
                                        <th class="border border-slate-400 px-2 py-1">SENIN</th>
                                        <th class="border border-slate-400 px-2 py-1">SELASA</th>
                                        <th class="border border-slate-400 px-2 py-1">RABU</th>
                                        <th class="border border-slate-400 px-2 py-1">KAMIS</th>
                                        <th class="border border-slate-400 px-2 py-1">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="font-semibold border border-slate-400 px-2 py-1 text-center">JUMLAH JAM</td>
                                        <td x-text="teachingHoursSummary.sabtu" class="border border-slate-400 px-2 py-1 text-center"></td>
                                        <td x-text="teachingHoursSummary.ahad" class="border border-slate-400 px-2 py-1 text-center"></td>
                                        <td x-text="teachingHoursSummary.senin" class="border border-slate-400 px-2 py-1 text-center"></td>
                                        <td x-text="teachingHoursSummary.selasa" class="border border-slate-400 px-2 py-1 text-center"></td>
                                        <td x-text="teachingHoursSummary.rabu" class="border border-slate-400 px-2 py-1 text-center"></td>
                                        <td x-text="teachingHoursSummary.kamis" class="border border-slate-400 px-2 py-1 text-center"></td>
                                        <td x-text="teachingHoursSummary.total" class="font-bold border border-slate-400 px-2 py-1 text-center"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Tampilan Daftar untuk MOBILE --}}
                        <div class="block sm:hidden border border-slate-200 rounded-lg p-3 space-y-2 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600">Sabtu:</span>
                                <span class="font-semibold" x-text="teachingHoursSummary.sabtu + ' Jam'"></span>
                            </div>
                             <div class="flex justify-between items-center">
                                <span class="text-slate-600">Ahad:</span>
                                <span class="font-semibold" x-text="teachingHoursSummary.ahad + ' Jam'"></span>
                            </div>
                             <div class="flex justify-between items-center">
                                <span class="text-slate-600">Senin:</span>
                                <span class="font-semibold" x-text="teachingHoursSummary.senin + ' Jam'"></span>
                            </div>
                             <div class="flex justify-between items-center">
                                <span class="text-slate-600">Selasa:</span>
                                <span class="font-semibold" x-text="teachingHoursSummary.selasa + ' Jam'"></span>
                            </div>
                             <div class="flex justify-between items-center">
                                <span class="text-slate-600">Rabu:</span>
                                <span class="font-semibold" x-text="teachingHoursSummary.rabu + ' Jam'"></span>
                            </div>
                             <div class="flex justify-between items-center">
                                <span class="text-slate-600">Kamis:</span>
                                <span class="font-semibold" x-text="teachingHoursSummary.kamis + ' Jam'"></span>
                            </div>
                            <div class="flex justify-between items-center border-t border-slate-200 pt-2 mt-2">
                                <span class="font-bold">Total:</span>
                                <span class="font-bold text-red-600" x-text="teachingHoursSummary.total + ' Jam'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tampilan KARTU khusus untuk KELAS --}}
                <div x-show="viewMode === 'card' && activeTab === 'kelas'" x-cloak>
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold" style="font-family: 'Times New Roman', serif;">جَدْوَلُ الدِّرَاسَة</h2>
                        <h3 class="text-xl font-semibold" x-text="scheduleTitle"></h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($days as $dayKey => $dayName)
                        <div class="bg-white p-4 border border-slate-200 rounded-lg shadow-sm">
                            <h4 class="text-center font-bold text-lg mb-4 pb-2 border-b border-slate-200">{{ strtoupper($dayName) }}</h4>
                            <div class="space-y-3">
                                @foreach ($timeSlots as $timeSlot)
                                <div class="flex items-center text-sm">
                                    <span class="w-8 flex-shrink-0 font-mono text-slate-500">{{ $timeSlot }}.</span>
                                    <div class="flex-1 border-b border-dotted border-slate-400 pb-1">
                                        <template x-if="scheduleToShow[{{ $dayKey }}] && scheduleToShow[{{ $dayKey }}][{{ $timeSlot }}]">
                                            <div class="flex justify-between items-start gap-x-2">
                                                <span class="font-bold text-slate-800" x-text="scheduleToShow[{{ $dayKey }}][{{ $timeSlot }}].subject || ''"></span>
                                                <div class="text-right text-xs text-slate-500 flex-shrink-0">
                                                    <div x-text="'Ruang: ' + (scheduleToShow[{{ $dayKey }}][{{ $timeSlot }}].room || '-')"></div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('scheduleViewer', () => ({
                activeTab: 'kelas',
                viewMode: 'card',
                selectedClass: '',
                selectedTeacher: '',
                schedules: @json($scheduleData),
                classes: @json($classes),
                teachers: @json($teachers),
                tomSelectInstances: {},

                init() {
                    this.tomSelectInstances.kelas = new TomSelect(this.$refs.kelasSelect, {
                        options: this.classes.map(c => ({ value: c.id, text: c.nama_kelas })),
                        onChange: (value) => { this.selectedClass = value; }
                    });
                    this.tomSelectInstances.guru = new TomSelect(this.$refs.guruSelect, {
                        options: this.teachers.map(t => ({ value: t.id, text: t.name })),
                        onChange: (value) => { this.selectedTeacher = value; },
                        maxOptions: null
                    });

                    this.$watch('selectedClass', (val) => {
                        if (val) {
                            this.selectedTeacher = '';
                            if (this.tomSelectInstances.guru) this.tomSelectInstances.guru.clear();
                        }
                    });
                    this.$watch('selectedTeacher', (val) => {
                        if (val) {
                            this.selectedClass = '';
                            if (this.tomSelectInstances.kelas) this.tomSelectInstances.kelas.clear();
                        }
                    });
                },

                setActiveTab(tab) {
                    this.activeTab = tab;
                    this.selectedClass = '';
                    this.selectedTeacher = '';
                    if (this.tomSelectInstances.kelas) this.tomSelectInstances.kelas.clear();
                    if (this.tomSelectInstances.guru) this.tomSelectInstances.guru.clear();
                },

                get selectedItem() {
                    return this.selectedClass || this.selectedTeacher;
                },

                get printUrl() {
                    if (!this.selectedItem) {
                        return '#';
                    }
                    const type = this.activeTab === 'kelas' ? 'kelas' : 'guru';
                    const id = this.selectedItem;
                    return `{{ url('/jadwal/print') }}/${type}/${id}`;
                },

                get scheduleTitle() {
                    if (this.activeTab === 'kelas' && this.selectedClass) {
                        const c = this.classes.find(cls => cls.id == this.selectedClass);
                        return `Jadwal Kelas ${c ? c.nama_kelas : ''}`;
                    }
                    if (this.activeTab === 'guru' && this.selectedTeacher) {
                        const t = this.teachers.find(tch => tch.id == this.selectedTeacher);
                        return `Jadwal Mengajar: ${t ? t.name : ''}`;
                    }
                    return 'Jadwal Pelajaran';
                },
                get scheduleToShow() {
                    if (this.activeTab === 'kelas' && this.selectedClass) {
                        return this.schedules.byClass[this.selectedClass] || {};
                    }
                    if (this.activeTab === 'guru' && this.selectedTeacher) {
                        return this.schedules.byTeacher[this.selectedTeacher] || {};
                    }
                    return {};
                },
                get teachingHoursSummary() {
                    const teacherId = this.selectedTeacher;
                    if (teacherId && this.schedules.teachingHours && this.schedules.teachingHours[teacherId]) {
                        return this.schedules.teachingHours[teacherId];
                    }
                    return { sabtu: 0, ahad: 0, senin: 0, selasa: 0, rabu: 0, kamis: 0, total: 0 };
                }
            }));
        });
    </script>
</x-app-layout>

