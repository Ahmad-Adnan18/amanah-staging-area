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
        @media print {
            .bg-slate-50 { background-color: white !important; }
            .shadow-lg { box-shadow: none !important; }
            .border { border: 1px solid #e2e8f0 !important; }
            .rounded-2xl { border-radius: 0 !important; }
            .gap-6 { gap: 1rem !important; }
            .mt-6, .mt-8 { margin-top: 1.5rem !important; }
            .mb-6, .mb-8 { margin-bottom: 1.5rem !important; }
            .p-4, .p-6 { padding: 1rem !important; }
            .hidden-print { display: none !important; }
            table { border-collapse: collapse !important; }
            th, td { border: 1px solid #e2e8f0 !important; }
        }
    </style>

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-4 sm:py-8 px-4 sm:px-6 lg:px-8" x-data="scheduleViewer">

            {{-- Header --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6 mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Jadwal Mengajar Saya</h1>
                        <p class="mt-1 text-slate-600 text-sm sm:text-base">Jadwal lengkap {{ $teacher->name }}</p>
                        <p class="text-sm text-slate-500 mt-1">Periode: {{ now()->translatedFormat('F Y') }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-md text-sm font-semibold hover:bg-gray-700 transition-colors hidden-print">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Dashboard
                        </a>
                        <a href="{{ url('/jadwal/print/guru/' . $teacher->id) }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-700 text-white rounded-md text-sm font-semibold hover:bg-red-600 shadow-sm transition-colors hidden-print">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.552c.377.046.752.097 1.128.152A2.25 2.25 0 0118 8.678v4.588A2.25 2.25 0 0115.75 15.5h-3.48a3.748 3.748 0 01-1.048.06c-.34.023-.681.042-1.022.06h-3.48A2.25 2.25 0 012 13.266V8.678c0-.986.62-1.84 1.52-2.174a41.34 41.34 0 011.128-.152V2.75zM6.5 2.5a.25.25 0 00-.25.25v3.5c0 .138.112.25.25.25h6.5a.25.25 0 00.25-.25v-3.5a.25.25 0 00-.25-.25h-6.5zM3.5 8.678v4.588c0 .138.112.25.25.25h2.25v-2.5a.75.75 0 01.75-.75h6.5a.75.75 0 01.75.75v2.5h2.25a.25.25 0 00.25-.25V8.678a.75.75 0 00-.507-.704 41.52 41.52 0 00-1.216-.173.75.75 0 00-.727.69v.252a.75.75 0 01-.75-.75h-6.5a.75.75 0 01-.75-.75v-.252a.75.75 0 00-.727-.69 41.52 41.52 0 00-1.216.173A.75.75 0 003.5 8.678z" clip-rule="evenodd" />
                            </svg>
                            Cetak
                        </a>
                    </div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold" style="font-family: 'Times New Roman', serif;">جَدْوَلُ التَدْرِيْس</h2>
                <h3 class="text-xl font-semibold">Jadwal Mengajar: {{ $teacher->name }}</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-12 mb-6 sm:mb-8">
                @foreach($days as $dayKey => $dayName)
                <div class="p-4">
                    <h4 class="text-center font-bold text-lg mb-4">{{ strtoupper($dayName) }}</h4>
                    <div class="space-y-3">
                        @foreach($timeSlots as $slot)
                        <div class="flex items-center text-sm">
                            <span class="w-6 font-mono">{{ $slot }}.</span>
                            <div class="flex-1 border-b border-dotted border-slate-400 pb-1">
                                @if(isset($scheduleData[$dayKey][$slot]))
                                <span class="flex justify-between">
                                    <span>{{ $scheduleData[$dayKey][$slot]['subject'] }}</span>
                                    <span class="font-semibold">{{ $scheduleData[$dayKey][$slot]['class'] }}</span>
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Rekapitulasi Jam Mengajar --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6 mb-6 sm:mb-8">
                <h5 class="font-bold mb-3 text-center text-sm">REKAPITULASI JAM MENGAJAR</h5>
                {{-- Tampilan Tabel untuk DESKTOP --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full border-collapse text-xs">
                        <thead>
                            <tr>
                                <th class="border border-slate-400 px-2 py-1">HARI</th>
                                @foreach($days as $dayKey => $dayName)
                                <th class="border border-slate-400 px-2 py-1">{{ strtoupper($dayName) }}</th>
                                @endforeach
                                <th class="border border-slate-400 px-2 py-1">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-semibold border border-slate-400 px-2 py-1 text-center">JUMLAH JAM</td>
                                @foreach($jamPerHari as $hari => $jam)
                                <td class="border border-slate-400 px-2 py-1 text-center">{{ $jam }}</td>
                                @endforeach
                                <td class="font-bold border border-slate-400 px-2 py-1 text-center">{{ $totalJam }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- Tampilan Daftar untuk MOBILE --}}
                <div class="block sm:hidden border border-slate-200 rounded-lg p-3 space-y-2 text-sm">
                    @foreach($jamPerHari as $hari => $jam)
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">{{ $hari }}:</span>
                        <span class="font-semibold">{{ $jam }} Jam</span>
                    </div>
                    @endforeach
                    <div class="flex justify-between items-center border-t border-slate-200 pt-2 mt-2">
                        <span class="font-bold">Total:</span>
                        <span class="font-bold text-red-600">{{ $totalJam }} Jam</span>
                    </div>
                </div>
            </div>

            {{-- Detail Jadwal per Hari --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold" style="font-family: 'Times New Roman', serif;">جَدْوَلُ التَدْرِيْس</h2>
                    <h3 class="text-xl font-semibold">Detail Jadwal Mengajar: {{ $teacher->name }}</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($days as $dayKey => $dayName)
                    <div class="bg-white p-4 border border-slate-200 rounded-lg shadow-sm">
                        <h4 class="text-center font-bold text-lg mb-4 pb-2 border-b border-slate-200">{{ strtoupper($dayName) }}</h4>
                        <div class="space-y-3">
                            @php
                                $daySchedules = $schedules->where('day_of_week', $dayKey)->sortBy('time_slot');
                            @endphp
                            @if($daySchedules->count() > 0)
                                @foreach($daySchedules as $schedule)
                                <div class="flex items-center text-sm">
                                    <span class="w-8 flex-shrink-0 font-mono text-slate-500">{{ $schedule->time_slot }}.</span>
                                    <div class="flex-1 border-b border-dotted border-slate-400 pb-1">
                                        <div class="flex justify-between items-start gap-x-2">
                                            <span class="font-bold text-slate-800">{{ $schedule->subject->nama_pelajaran }}</span>
                                            <div class="text-right text-xs text-slate-500 flex-shrink-0">
                                                <div>Ruang: {{ $schedule->room->name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center text-slate-400 text-sm py-4">
                                    Tidak ada jadwal mengajar
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('scheduleViewer', () => ({
                // Minimal Alpine.js setup for consistency, though not strictly needed here
                init() {
                    // Placeholder for any future interactivity
                }
            }));
        });
    </script>
</x-app-layout>