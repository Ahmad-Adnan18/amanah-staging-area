<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-full mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Tukar Jadwal Manual (Mode Tabel)</h1>
                    <p class="mt-1 text-slate-600">Pilih dua jadwal dari tabel di bawah untuk ditukar posisinya.</p>
                </div>
                
                <div x-data="{
                        source: {{ $sourceSchedule ? json_encode(['id' => $sourceSchedule->id, 'text' => "{$sourceSchedule->subject->nama_pelajaran} ({$sourceSchedule->teacher->name})", 'classId' => $sourceSchedule->kelas_id, 'className' => $sourceSchedule->kelas->nama_kelas]) : 'null' }},
                        target: {{ $targetSchedule ? json_encode(['id' => $targetSchedule->id, 'text' => "{$targetSchedule->subject->nama_pelajaran} ({$targetSchedule->teacher->name})", 'classId' => $targetSchedule->kelas_id, 'className' => $targetSchedule->kelas->nama_kelas]) : 'null' }},
                        errorMessage: '',
                        select(scheduleId, scheduleText, classId, className) {
                            this.errorMessage = '';
                            
                            if (this.source && this.source.id === scheduleId) {
                                this.source = null;
                            } else if (this.target && this.target.id === scheduleId) {
                                this.target = null;
                            } else if (!this.source) {
                                this.source = { id: scheduleId, text: scheduleText, classId: classId, className: className };
                            } else if (!this.target) {
                                // Validasi: harus kelas yang sama
                                if (this.source.classId !== classId) {
                                    this.errorMessage = 'Tidak bisa menukar jadwal antar kelas berbeda! Jadwal sumber: ' + this.source.className + ', Anda memilih: ' + className;
                                    return;
                                }
                                this.target = { id: scheduleId, text: scheduleText, classId: classId, className: className };
                            } else {
                                this.source = { id: scheduleId, text: scheduleText, classId: classId, className: className };
                                this.target = null;
                            }
                        }
                    }"  
                    class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Kolom Tabel Jadwal -->
                    <div class="lg:col-span-2 space-y-8" x-data="{ activeTab: 1 }">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                                @foreach ($days as $dayKey => $dayName)
                                <button @click="activeTab = {{ $dayKey }}" :class="{ 'border-red-500 text-red-600': activeTab === {{ $dayKey }}, 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== {{ $dayKey }} }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">{{ $dayName }}</button>
                                @endforeach
                            </nav>
                        </div>

                        @foreach ($days as $dayKey => $dayName)
                        <div x-show="activeTab === {{ $dayKey }}" class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                             <div class="overflow-x-auto">
                                <table class="min-w-full table-fixed">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-2 py-3 text-left text-sm font-semibold text-slate-900 w-28">Kelas</th>
                                            @foreach ($timeSlots as $timeSlot)
                                                <th class="px-2 py-3 text-center text-sm font-semibold text-slate-500">{{ $timeSlot }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @foreach ($classes as $class)
                                            <tr>
                                                <td class="px-3 py-3 text-sm font-medium text-slate-900 bg-slate-50">{{ $class->nama_kelas }}</td>
                                                @foreach ($timeSlots as $timeSlot)
                                                    {{-- [PERBAIKAN] Menghapus whitespace-nowrap dan menyesuaikan padding --}}
                                                    <td class="p-1 text-sm text-slate-500 border-l border-slate-200 text-center align-top">
                                                        @if ($schedule = $grid[$dayKey][$class->id][$timeSlot])
                                                            @php 
                                                                $scheduleText = "{$schedule->subject->nama_pelajaran} ({$schedule->teacher->name})";
                                                            @endphp
                                                            <div @click="select({{ $schedule->id }}, '{{ addslashes($scheduleText) }}', {{ $class->id }}, '{{ addslashes($class->nama_kelas) }}')" 
                                                                 class="p-2 rounded-lg text-left h-full min-h-[6rem] flex flex-col justify-between text-xs cursor-pointer transition-all border-2"
                                                                 :class="{
                                                                    'border-green-500 bg-green-50 ring-2 ring-green-200': source && source.id === {{ $schedule->id }},
                                                                    'border-blue-500 bg-blue-50 ring-2 ring-blue-200': target && target.id === {{ $schedule->id }},
                                                                    'border-transparent hover:bg-slate-50': (!source || source.id !== {{ $schedule->id }}) && (!target || target.id !== {{ $schedule->id }})
                                                                 }">
                                                                <div>
                                                                    <div class="font-semibold text-slate-900 whitespace-normal">{{ $schedule->subject->nama_pelajaran ?? 'N/A' }}</div>
                                                                    <div class="mt-1 whitespace-normal">{{ $schedule->teacher->name ?? 'N/A' }}</div>
                                                                </div>
                                                                {{--<div class="text-slate-400 italic mt-2 text-right">{{ $schedule->room->name ?? 'N/A' }}</div>--}}
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Kolom Seleksi & Aksi -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 sticky top-8">
                             <form action="{{ route('admin.schedule.swap.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="source_schedule_id" :value="source ? source.id : ''">
                                <input type="hidden" name="target_schedule_id" :value="target ? target.id : ''">

                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900">Detail Pertukaran</h3>
                                    
                                    <!-- Error Message -->
                                    <div x-show="errorMessage" x-cloak class="mt-4 p-3 bg-red-50 text-red-800 border border-red-200 rounded-lg text-sm">
                                        <span x-text="errorMessage"></span>
                                    </div>
                                    
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Pelajaran Sumber</label>
                                            <div class="mt-1 p-3 rounded-lg bg-slate-100 border border-slate-200 min-h-[4rem]">
                                                <div x-text="source ? source.text : 'Pilih dari tabel di kiri'"></div>
                                                <div x-show="source" class="text-xs text-slate-500 mt-1" x-text="source ? 'Kelas: ' + source.className : ''"></div>
                                            </div>
                                        </div>
                                         <div>
                                            <label class="block text-sm font-medium text-gray-700">Pelajaran Target</label>
                                            <div class="mt-1 p-3 rounded-lg bg-slate-100 border border-slate-200 min-h-[4rem]">
                                                <div x-text="target ? target.text : 'Pilih dari tabel di kiri'"></div>
                                                <div x-show="target" class="text-xs text-slate-500 mt-1" x-text="target ? 'Kelas: ' + target.className : ''"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Info validasi -->
                                    <div class="mt-4 p-3 bg-blue-50 text-blue-800 border border-blue-200 rounded-lg text-xs">
                                        ℹ️ Pertukaran jadwal hanya bisa dilakukan dalam <strong>kelas yang sama</strong>.
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-4">
                                    <a href="{{ route('admin.schedule.view.grid') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Kembali</a>
                                    <button type="submit" name="check_swap" value="1" 
                                            class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                            :disabled="!source || !target">
                                        Periksa
                                    </button>
                                </div>
                            </form>

                            @if ($validationResult)
                            <div class="p-6 border-t border-slate-200">
                                <h3 class="text-lg font-bold text-gray-900">Hasil Pengecekan</h3>
                                <div class="mt-4 text-sm space-y-4">
                                    @if ($validationResult['isValid'])
                                        <div class="p-3 bg-green-50 text-green-800 border border-green-200 rounded-lg">✅ Pertukaran ini VALID.</div>
                                        <form action="{{ route('admin.schedule.swap.process') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="source_schedule_id" value="{{ $sourceSchedule->id }}">
                                            <input type="hidden" name="target_schedule_id" value="{{ $targetSchedule->id }}">
                                            <div class="flex justify-end mt-4">
                                                <button type="submit" name="confirm_swap" value="1" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                                    Konfirmasi Tukar Jadwal
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <div class="p-3 bg-red-50 text-red-800 border border-red-200 rounded-lg">
                                            <p class="font-bold">❌ Pertukaran ini TIDAK VALID:</p>
                                            <ul class="list-disc pl-5 mt-1">
                                                @foreach($validationResult['errors'] as $error)<li>{{ $error }}</li>@endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

