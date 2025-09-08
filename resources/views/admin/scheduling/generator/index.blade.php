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
                                    <button type="submit" 
                                            :disabled="generating"
                                            class="inline-flex items-center justify-center rounded-lg bg-red-700 px-8 py-3 text-base font-semibold text-white shadow-sm hover:bg-red-600 transition-all duration-200 disabled:bg-slate-400 disabled:cursor-not-allowed">
                                        
                                        <!-- State saat tidak loading -->
                                        <span x-show="!generating" class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" /></svg>
                                            Mulai Generate Jadwal
                                        </span>

                                        <!-- State saat loading (generating = true) -->
                                        <span x-show="generating" x-cloak>
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            Memproses...
                                        </span>
                                    </button>
                                </form>
                                <p class="mt-4 text-xs text-slate-500">Proses ini mungkin memakan waktu beberapa menit. Jangan tutup halaman ini.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Samping: Status & Hasil -->
                <div class="space-y-6">

                    <!-- Panel Notifikasi (Desain Diperbarui) -->
                    @if (session('success'))
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm">
                            <div class="flex">
                                <div class="flex-shrink-0"><svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg></div>
                                <div class="ml-3"><p class="text-sm text-green-700">{{ session('success') }}</p></div>
                            </div>
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg shadow-sm">
                           <div class="flex">
                                <div class="flex-shrink-0"><svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.636-1.21 2.27-1.21 2.906 0l4.25 8.117a1.75 1.75 0 01-1.453 2.534H5.453a1.75 1.75 0 01-1.453-2.534l4.25-8.117zM9 9a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm1 4a1 1 0 10-2 0v.01a1 1 0 102 0V13z" clip-rule="evenodd" /></svg></div>
                                <div class="ml-3"><p class="text-sm text-yellow-700">{{ session('warning') }}</p></div>
                            </div>
                        </div>
                    @endif
                     @if (session('error'))
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm">
                           <div class="flex">
                                <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg></div>
                                <div class="ml-3"><p class="text-sm text-red-700">{{ session('error') }}</p></div>
                            </div>
                        </div>
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
