<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

            <!-- [PERUBAHAN] Menggunakan layout Grid untuk memisahkan Kontrol dan Hasil -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <!-- Kolom Utama: Panel Kontrol -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Panel Aksi Utama -->
                    <div x-data="{ generating: false }" class="bg-white rounded-2xl shadow-lg border border-slate-200">
                        <div class="p-6 border-b border-slate-200">
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Pusat Kontrol Generator</h1>
                            <p class="mt-1 text-slate-600">Sistem akan menyusun jadwal secara otomatis berdasarkan semua aturan yang telah ditetapkan.</p>
                        </div>
                        <div class="p-6">
                            <h3 class="font-semibold text-slate-800">Checklist Sebelum Generate:</h3>
                            <ul class="mt-2 space-y-2 text-sm text-slate-600 list-disc list-inside">
                                <li>Pastikan semua data <span class="font-semibold">Guru, Mata Pelajaran, Kelas,</span> dan <span class="font-semibold">Ruangan</span> sudah lengkap.</li>
                                <li>Pastikan <span class="font-semibold">Aturan Penjadwalan</span> (misal: jam wajib) sudah diatur dengan benar.</li>
                                <li>Pastikan <span class="font-semibold">Ketersediaan Guru</span> sudah diisi untuk menghindari konflik.</li>
                            </ul>
                            <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                                <form action="{{ route('admin.generator.generate') }}" method="POST" @submit="generating = true">
                                    @csrf
                                    <button type="submit" :disabled="generating" class="inline-flex items-center justify-center rounded-lg bg-red-700 px-8 py-3 text-base font-semibold text-white shadow-sm hover:bg-red-600 transition-all duration-200 disabled:bg-slate-400 disabled:cursor-not-allowed">

                                        <!-- State saat tidak loading -->
                                        <span x-show="!generating" class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" /></svg>
                                            Mulai Generate Jadwal
                                        </span>

                                        <!-- State saat loading (generating = true) -->
                                        <span x-show="generating" x-cloak>
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Memproses...
                                        </span>
                                    </button>
                                </form>
                                <p class="mt-4 text-xs text-slate-500">Proses ini mungkin memakan waktu beberapa menit. Jangan tutup halaman ini.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Opsi Hybrid -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 mt-6">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-gray-900">Opsi Generator Hybrid</h3>
                        <p class="mt-1 text-slate-600">Pilih strategi untuk penjadwalan tambahan.</p>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.scheduling.generator.hybrid') }}" method="POST" id="hybridForm">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="flex items-center">
                                        <input type="radio" name="clear_existing" value="0" checked class="mr-3">
                                        <span class="text-sm font-medium text-gray-900">Tambahkan ke jadwal existing</span>
                                    </label>
                                    <p class="text-sm text-gray-500 ml-6">Jadwal baru akan ditambahkan tanpa menghapus yang sudah ada</p>
                                </div>

                                <div>
                                    <label class="flex items-center">
                                        <input type="radio" name="clear_existing" value="1" class="mr-3">
                                        <span class="text-sm font-medium text-gray-900">Hapus semua jadwal dan buat baru</span>
                                    </label>
                                    <p class="text-sm text-gray-500 ml-6">Semua jadwal akan dihapus dan dibuat dari awal</p>
                                </div>

                                <div class="pt-4 border-t border-slate-200">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Strategi Penempatan:</label>
                                    <select name="strategy" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                        <option value="incremental">Incremental - Isi slot kosong saja</option>
                                        <option value="fill_gaps">Fill Gaps - Utamakan mengisi kekosongan</option>
                                        <option value="replace_conflicts">Replace Conflicts - Ganti jadwal yang bermasalah</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-8 py-3 text-base font-semibold text-white shadow-sm hover:bg-green-500 transition-all duration-200">
                                    Jalankan Generator Hybrid
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Kolom Samping: Status & Hasil -->
                <div class="space-y-6">

                    <!-- Panel Notifikasi (Desain Diperbarui) -->
                    @if (session('success'))
                        <x-alert type="success" :message="session('success')" />
                    @endif
                    @if (session('warning'))
                        <x-alert type="warning" :message="session('warning')" />
                    @endif
                    @if (session('error'))
                        <x-alert type="error" :message="session('error')" />
                    @endif

                    <!-- Panel untuk Mapel yang Gagal Ditempatkan -->
                    @if (session('unplaced_subjects'))
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                        <div class="p-4 border-b border-slate-200">
                            <h3 class="font-semibold text-slate-800">Gagal Ditempatkan</h3>
                        </div>
                        <div class="p-4">
                            <ul class="space-y-1 text-sm list-disc list-inside text-yellow-800">
                                @foreach (session('unplaced_subjects') as $subject)
                                <li>{{ $subject }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <!-- Panel untuk Log Debugging -->
                    @if (session('log'))
                    <div class="bg-slate-800 text-white rounded-2xl shadow-lg border border-slate-700">
                        <div class="p-4 border-b border-slate-600">
                            <h3 class="font-semibold text-slate-200">Log Proses Generator</h3>
                        </div>
                        <div class="p-4">
                            <div class="font-mono text-xs overflow-x-auto h-64 bg-slate-900 p-4 rounded-lg text-slate-300">
                                @foreach (session('log') as $line)
                                <div class="flex"><span class="text-slate-500 mr-2">></span><span>{{ $line }}</span></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
