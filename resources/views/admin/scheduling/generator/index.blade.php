<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

            <!-- Panel Utama -->
            <div x-data="{ generating: false, clearExisting: true }" class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <div class="p-6 border-b border-slate-200">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Generator Jadwal Otomatis</h1>
                    <p class="mt-1 text-slate-600">Sistem akan menyusun jadwal secara otomatis dengan algoritma optimal.</p>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-slate-800">Checklist Sebelum Generate:</h3>
                    <ul class="mt-2 space-y-2 text-sm text-slate-600 list-disc list-inside">
                        <li>Pastikan semua data <span class="font-semibold">Guru, Mata Pelajaran, Kelas,</span> dan <span class="font-semibold">Ruangan</span> sudah lengkap.</li>
                        <li>Pastikan <span class="font-semibold">Ketersediaan Guru</span> sudah diisi untuk menghindari konflik.</li>
                        <li>Pastikan setiap mata pelajaran sudah memiliki <span class="font-semibold">Guru yang ditugaskan</span>.</li>
                    </ul>

                    <!-- Opsi Sederhana -->
                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" x-model="clearExisting" class="h-5 w-5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="ml-3 text-sm font-medium text-gray-900">Hapus jadwal lama dan buat dari awal</span>
                        </label>
                        <p class="text-xs text-slate-500 ml-8 mt-1">Direkomendasikan agar hasil lebih optimal.</p>
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                        <form action="{{ route('admin.scheduling.generator.hybrid') }}" method="POST" @submit="generating = true">
                            @csrf
                            <input type="hidden" name="clear_existing" :value="clearExisting ? 1 : 0">
                            <input type="hidden" name="strategy" value="incremental">
                            <button type="submit" :disabled="generating" class="inline-flex items-center justify-center rounded-lg bg-red-700 px-8 py-3 text-base font-semibold text-white shadow-sm hover:bg-red-600 transition-all duration-200 disabled:bg-slate-400 disabled:cursor-not-allowed">

                                <!-- State saat tidak loading -->
                                <span x-show="!generating" class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" /></svg>
                                    Generate Jadwal
                                </span>

                                <!-- State saat loading -->
                                <span x-show="generating" x-cloak class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses... (bisa sampai 30 detik)
                                </span>
                            </button>
                        </form>
                        <p class="mt-4 text-xs text-slate-500">Algoritma: Per-Guru + Soft Constraints untuk hasil optimal.</p>
                    </div>
                </div>
            </div>

            <!-- Hasil -->
            <div class="mt-8 space-y-6">

                <!-- Notifikasi -->
                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif
                @if (session('warning'))
                    <x-alert type="warning" :message="session('warning')" />
                @endif
                @if (session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif

                <!-- Capacity Warnings -->
                @if (session('capacity_warnings') && count(session('capacity_warnings')) > 0)
                <div class="bg-orange-50 rounded-2xl shadow-lg border border-orange-200">
                    <div class="p-4 border-b border-orange-200">
                        <h3 class="font-semibold text-orange-800">⚠️ Masalah Kapasitas Terdeteksi</h3>
                        <p class="text-sm text-orange-600">Beberapa guru/kelas melebihi kapasitas maksimal.</p>
                    </div>
                    <div class="p-4">
                        <ul class="space-y-1 text-sm list-disc list-inside text-orange-800">
                            @foreach (session('capacity_warnings') as $warning)
                            <li>{{ $warning }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Forced Placements -->
                @if (session('forced_placements') && count(session('forced_placements')) > 0)
                <div class="bg-blue-50 rounded-2xl shadow-lg border border-blue-200">
                    <div class="p-4 border-b border-blue-200">
                        <h3 class="font-semibold text-blue-800">🔧 Penempatan Dipaksakan ({{ count(session('forced_placements')) }})</h3>
                        <p class="text-sm text-blue-600">Jadwal ini ditempatkan di slot yang guru tandai "tidak tersedia".</p>
                    </div>
                    <div class="p-4 max-h-48 overflow-y-auto">
                        <ul class="space-y-1 text-sm list-disc list-inside text-blue-800">
                            @foreach (session('forced_placements') as $fp)
                            <li>{{ $fp }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Gagal Ditempatkan -->
                @if (session('unplaced_subjects') && count(session('unplaced_subjects')) > 0)
                <div class="bg-yellow-50 rounded-2xl shadow-lg border border-yellow-200">
                    <div class="p-4 border-b border-yellow-200">
                        <h3 class="font-semibold text-yellow-800">Gagal Ditempatkan ({{ count(session('unplaced_subjects')) }})</h3>
                    </div>
                    <div class="p-4 max-h-64 overflow-y-auto">
                        <ul class="space-y-1 text-sm list-disc list-inside text-yellow-800">
                            @foreach (session('unplaced_subjects') as $subject)
                            <li>{{ $subject }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Log Proses -->
                @if (session('log'))
                <details class="bg-slate-800 text-white rounded-2xl shadow-lg border border-slate-700">
                    <summary class="p-4 cursor-pointer font-semibold text-slate-200">Log Proses Generator (klik untuk buka)</summary>
                    <div class="p-4 border-t border-slate-600">
                        <div class="font-mono text-xs overflow-x-auto max-h-64 bg-slate-900 p-4 rounded-lg text-slate-300">
                            @foreach (session('log') as $line)
                            <div class="flex"><span class="text-slate-500 mr-2">></span><span>{{ $line }}</span></div>
                            @endforeach
                        </div>
                    </div>
                </details>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
