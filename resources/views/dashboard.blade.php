<x-app-layout>
    @php
        // Logika untuk sapaan dinamis berdasarkan waktu
        date_default_timezone_set('Asia/Jakarta');
        $hour = date('H');
        if ($hour < 11) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour < 15) {
            $greeting = 'Selamat Siang';
        } elseif ($hour < 18) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }
        $today = \Carbon\Carbon::now()->translatedFormat('l, j F Y');
    @endphp

    {{-- Container utama dengan background dan padding --}}
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $greeting }}, {{ Auth::user()->name }}!</h1>
                            <p class="mt-1 text-slate-600">Ini ringkasan aktivitas pondok untuk hari ini, {{ $today }}.</p>
                        </div>
                    </div>
                </div>
                
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                         class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-2xl shadow-sm flex justify-between items-center" role="alert">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                        <button @click="show = false" class="text-green-600 hover:text-green-800">&times;</button>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php
                        $cardBaseClass = 'bg-white p-6 rounded-2xl shadow-lg border border-slate-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-red-300 group';
                        $iconContainerClass = 'bg-slate-100 p-3 rounded-full flex-shrink-0 transition-colors duration-300 group-hover:bg-red-100';
                        $iconClass = 'h-6 w-6 text-slate-600 transition-colors duration-300 group-hover:text-red-600';
                    @endphp
                    
                    <a href="{{ route('admin.santri-management.index') }}" class="{{ $cardBaseClass }}">
                        <div class="flex items-start gap-4">
                            <div class="{{ $iconContainerClass }}"><svg class="{{ $iconClass }}" xmlns="http://www.w.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.5-2.962a3.75 3.75 0 015.968 0 3.75 3.75 0 01-5.968 0zM21 12.75A9 9 0 113 12.75v6.75a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 19.5v-6.75z" /></svg></div>
                            <div>
                                <p class="text-sm text-slate-600">Santri Aktif</p>
                                <p class="text-2xl font-bold text-slate-900">{{ $totalSantri }}</p>
                                <div class="mt-2 flex items-center text-xs text-slate-500 divide-x divide-slate-300">
                                    <span class="inline-flex items-center gap-1 pr-2">
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM5.133 6.352a.75.75 0 01.936-1.204l.793.616a.75.75 0 01-.936 1.204l-.793-.616zM13.93 5.148a.75.75 0 01.936 1.204l-.793.616a.75.75 0 01-.936-1.204l.793-.616zM10 12.5a4.5 4.5 0 100-9 4.5 4.5 0 000 9zM11.5 12.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM3 16.25a.75.75 0 01.75-.75h12.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z" clip-rule="evenodd" /></svg>
                                        <span>Putra: {{ $totalSantriPutra ?? 0 }}</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1 pl-2">
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM5.133 6.352a.75.75 0 01.936-1.204l.793.616a.75.75 0 01-.936 1.204l-.793-.616zM13.93 5.148a.75.75 0 01.936 1.204l-.793.616a.75.75 0 01-.936-1.204l.793-.616zM10 12.5a4.5 4.5 0 100-9 4.5 4.5 0 000 9zM11.5 12.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM10 14a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 14z" clip-rule="evenodd" /></svg>
                                        <span>Putri: {{ $totalSantriPutri ?? 0 }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('perizinan.index', ['status' => 'aktif']) }}" class="{{ $cardBaseClass }}">
                        <div class="flex items-start gap-4">
                            <div class="{{ $iconContainerClass }}"><svg class="{{ $iconClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                            <div>
                                <p class="text-sm text-slate-600">Izin Aktif</p>
                                <p class="text-2xl font-bold text-slate-900">{{ $totalIzinAktif }}</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('perizinan.index', ['tanggal_akhir' => now()->format('Y-m-d')]) }}" class="{{ $cardBaseClass }}">
                        <div class="flex items-start gap-4">
                            <div class="{{ $iconContainerClass }}"><svg class="{{ $iconClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div>
                            <div>
                                <p class="text-sm text-slate-600">Jadwal Pulang Hari Ini</p>
                                <p class="text-2xl font-bold text-slate-900">{{ $santriPulangHariIni }}</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('perizinan.index', ['status' => 'terlambat']) }}" class="bg-amber-50 border-amber-300 p-6 rounded-2xl shadow-lg border transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-amber-400 group">
                        <div class="flex items-start gap-4">
                             <div class="bg-amber-100 p-3 rounded-full flex-shrink-0"><svg class="h-6 w-6 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg></div>
                            <div>
                                <p class="text-sm text-amber-600">Terlambat Kembali</p>
                                <p class="text-2xl font-bold text-amber-800">{{ $jumlahTerlambat }}</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Pintasan Cepat</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                        @php
                            $shortcutClass = "bg-white p-4 rounded-2xl shadow-lg border border-slate-200 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-red-300 group flex flex-col items-center justify-center";
                        @endphp
                        @if(in_array(Auth::user()->role, ['admin', 'pengajaran']))
                        <a href="{{ route('admin.santri-management.index') }}" class="{{ $shortcutClass }}">
                            <div class="bg-slate-100 p-3 rounded-full transition-colors duration-300 group-hover:bg-red-100"><svg class="h-6 w-6 text-slate-600 transition-colors duration-300 group-hover:text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.67c.12-.318.239-.636.354-.961" /></svg></div>
                            <p class="mt-2 text-sm font-semibold text-slate-700">Manajemen Santri</p>
                        </a>
                         @endif
                        @if(in_array(Auth::user()->role, ['admin', 'pengajaran']))
                        <a href="{{ route('akademik.nilai.index') }}" class="{{ $shortcutClass }}">
                            <div class="bg-slate-100 p-3 rounded-full transition-colors duration-300 group-hover:bg-red-100"><svg class="h-6 w-6 text-slate-600 transition-colors duration-300 group-hover:text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg></div>
                            <p class="mt-2 text-sm font-semibold text-slate-700">Input Nilai</p>
                        </a>
                        @endif
                        <a href="{{ route('pengajaran.kelas.index') }}" class="{{ $shortcutClass }}">
                            <div class="bg-slate-100 p-3 rounded-full transition-colors duration-300 group-hover:bg-red-100"><svg class="h-6 w-6 text-slate-600 transition-colors duration-300 group-hover:text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h12A2.25 2.25 0 0020.25 14.25V3M3.75 21h16.5M12 3v18" /></svg></div>
                            <p class="mt-2 text-sm font-semibold text-slate-700">Manajemen Kelas</p>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    <div class="lg:col-span-1 bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900">Distribusi Izin</h3>
                        <p class="mt-1 text-sm text-slate-600">Berdasarkan jenis izin yang aktif.</p>
                        <div class="mt-6 h-64"><canvas id="izinChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-2 space-y-8">
                        
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                            <div class="p-6 border-b border-slate-200"><h3 class="text-lg font-medium text-gray-900">Santri Izin Aktif Terbaru</h3></div>
                            <div class="md:hidden">
                                <div class="divide-y divide-slate-200">
                                @forelse ($perizinanAktif as $izin)
                                    <a href="{{ route('santri.profil.show', $izin->santri) }}" class="block p-4 hover:bg-slate-50">
                                        <div class="flex justify-between items-start gap-4">
                                            <div class="flex-1">
                                                <p class="font-semibold text-slate-900">{{ $izin->santri->nama }}</p>
                                                <p class="text-sm text-slate-500">{{ $izin->santri->kelas->nama_kelas ?? 'N/A' }}</p>
                                            </div>
                                            <div class="flex-shrink-0 text-right">
                                                <p class="text-sm font-medium text-slate-700">{{ $izin->jenis_izin }}</p>
                                                <p class="text-xs text-slate-500">Kembali: {{ $izin->tanggal_akhir ? $izin->tanggal_akhir->format('d M Y') : '-' }}</p>
                                            </div>
                                        </div>
                                        <p class="text-sm text-slate-600 mt-2 pt-2 border-t border-slate-200">{{ $izin->keterangan }}</p>
                                    </a>
                                @empty
                                    <p class="p-12 text-center text-sm text-slate-500">Tidak ada santri yang sedang izin.</p>
                                @endforelse
                                </div>
                            </div>
                            <div class="hidden md:block overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Santri</th>
                                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Jenis Izin & Keterangan</th>
                                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Kembali</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @forelse ($perizinanAktif as $izin)
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-6 py-4">
                                                    <a href="{{ route('santri.profil.show', $izin->santri) }}" class="group">
                                                        <div class="text-sm font-medium text-slate-900 group-hover:text-red-600">{{ $izin->santri->nama }}</div>
                                                        <div class="text-sm text-slate-500">{{ $izin->santri->kelas->nama_kelas ?? 'N/A' }}</div>
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <p class="text-sm font-medium text-slate-900">{{ $izin->jenis_izin }}</p>
                                                    <p class="text-sm text-slate-500">{{ $izin->keterangan }}</p>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-slate-500">{{ $izin->tanggal_akhir ? $izin->tanggal_akhir->format('d M Y') : '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="px-6 py-12 text-center text-sm text-slate-500">Tidak ada santri yang sedang izin.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                             <div class="p-6 border-b border-slate-200"><h3 class="text-lg font-medium text-gray-900">Pelanggaran Terbaru</h3></div>
                             <div class="divide-y divide-slate-200">
                                @forelse ($pelanggaranTerbaru as $pelanggaran)
                                    <a href="{{ route('santri.profil.show', $pelanggaran->santri) }}" class="block p-4 hover:bg-slate-50">
                                        <div class="flex justify-between items-center gap-4">
                                            <div>
                                                <p class="font-medium text-slate-800">{{ $pelanggaran->santri->nama }}</p>
                                                <p class="text-sm text-slate-500">{{ $pelanggaran->jenis_pelanggaran }}</p>
                                            </div>
                                            <p class="text-xs text-slate-400 flex-shrink-0">{{ $pelanggaran->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <p class="p-6 text-center text-slate-500">Tidak ada catatan pelanggaran baru.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData ?? []);
            if (chartData.length > 0) {
                const ctx = document.getElementById('izinChart');
                if (ctx) {
                    new Chart(ctx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: @json($chartLabels ?? []),
                            datasets: [{
                                label: 'Jumlah Izin', data: chartData,
                                backgroundColor: ['#DC2626', '#F59E0B', '#10B981', '#3B82F6', '#6B7280', '#8B5CF6'],
                                borderColor: '#FFFFFF', borderWidth: 2, hoverOffset: 8
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { position: 'bottom', labels: { padding: 15, boxWidth: 12, font: { size: 12 }, color: '#475569' } } },
                            cutout: '65%'
                        }
                    });
                }
            }
        });
    </script>
    @endpush
</x-app-layout>