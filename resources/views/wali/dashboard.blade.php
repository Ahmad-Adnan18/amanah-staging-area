<x-app-layout>
    {{-- Latar belakang halaman --}}
    <div class="bg-slate-50 min-h-screen">
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="max-w-4xl mx-auto space-y-6 sm:space-y-8">

                <!-- Header -->
                <div>
                    {{-- [MOBILE] Ukuran teks dioptimalkan untuk layar kecil --}}
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-red-700">Portal Wali Santri</h1>
                    <p class="mt-1 text-slate-600 text-sm sm:text-base">Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>. Berikut adalah ringkasan perkembangan ananda.</p>
                </div>

                <!-- Profil Santri -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6">
                    {{-- [MOBILE] Layout diubah jadi vertikal (flex-col) di mobile, dan horizontal (sm:flex-row) di desktop --}}
                    <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 text-center sm:text-left">
                        <div class="flex-shrink-0">
                            <img class="h-24 w-24 rounded-full object-cover ring-4 ring-red-100" src="{{ $santri->foto ? Storage::url($santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($santri->nama).'&background=FBBF24&color=fff&size=128' }}" alt="Foto Santri">
                        </div>
                        <div class="w-full">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $santri->nama }}</h2>
                            
                            {{-- [MOBILE] Info detail dibuat menjadi daftar vertikal di mobile agar tidak berantakan --}}
                            <div class="mt-2 text-slate-600 space-y-1 sm:space-y-0 sm:flex sm:items-center sm:gap-x-4 text-sm">
                                <p class="flex items-center justify-center sm:justify-start gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A6.483 6.483 0 0010 16.5a6.483 6.483 0 004.793-2.11A5.99 5.99 0 0010 12z" clip-rule="evenodd" /></svg>
                                    <span class="font-semibold">NIS:</span> {{ $santri->nis }}
                                </p>
                                <span class="hidden sm:inline text-slate-300">|</span>
                                <p class="flex items-center justify-center sm:justify-start gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                                    <span class="font-semibold">Kelas:</span> {{ $santri->kelas->nama_kelas ?? 'N/A' }}
                                </p>
                                <span class="hidden sm:inline text-slate-300">|</span>
                                <p class="flex items-center justify-center sm:justify-start gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" /></svg>
                                    <span class="font-semibold">Rayon:</span> {{ $santri->rayon }}
                                </p>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('santri.profil.show', $santri) }}" class="text-sm font-semibold text-red-600 hover:text-red-800 transition-colors">Lihat Profil, Rapor & Catatan &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Izin & Pelanggaran -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                    <!-- Riwayat Perizinan Terbaru -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                        <div class="p-4 sm:p-6 border-b border-slate-200">
                            <h3 class="text-lg font-medium text-gray-900">Riwayat Perizinan Terbaru</h3>
                        </div>
                        <ul class="divide-y divide-slate-200">
                            @forelse ($santri->perizinans->take(5) as $izin)
                                <li class="p-4 sm:p-4">
                                    <div class="flex items-center justify-between">
                                        <p class="font-semibold text-slate-800">{{ $izin->jenis_izin }}</p>
                                        {{-- [BARU] Status badge berwarna --}}
                                        <span @class([
                                            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                            'bg-green-100 text-green-800' => $izin->status === 'Disetujui',
                                            'bg-yellow-100 text-yellow-800' => $izin->status === 'Diproses',
                                            'bg-red-100 text-red-800' => $izin->status === 'Ditolak',
                                            'bg-blue-100 text-blue-800' => $izin->status === 'Telah Kembali',
                                            'bg-slate-100 text-slate-800' => !in_array($izin->status, ['Disetujui', 'Diproses', 'Ditolak', 'Telah Kembali']),
                                        ])>{{ $izin->status }}</span>
                                    </div>
                                    {{-- [BARU] Detail tanggal dan keterangan --}}
                                    <p class="text-sm text-slate-500 mt-1">
                                        {{ $izin->tanggal_mulai->format('d M Y') }} s/d {{ $izin->tanggal_kembali->format('d M Y') }}
                                    </p>
                                    <p class="text-xs text-slate-400 mt-2">
                                        Catatan: {{ Str::limit($izin->keterangan, 50) }}
                                    </p>
                                </li>
                            @empty
                                <li class="p-6 text-center text-slate-500 text-sm">
                                    <svg class="mx-auto h-8 w-8 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                                    <span class="mt-2 block">Belum ada riwayat perizinan.</span>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    <!-- Riwayat Pelanggaran Terbaru -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                        <div class="p-4 sm:p-6 border-b border-slate-200">
                            <h3 class="text-lg font-medium text-gray-900">Riwayat Pelanggaran Terbaru</h3>
                        </div>
                        <ul class="divide-y divide-slate-200">
                            @forelse ($santri->pelanggarans->take(5) as $pelanggaran)
                                <li class="p-4 sm:p-4">
                                     <div class="flex items-center justify-between">
                                        <p class="font-semibold text-slate-800">{{ $pelanggaran->jenis_pelanggaran }}</p>
                                        {{-- [BARU] Poin pelanggaran --}}
                                        <span class="font-bold text-red-600 text-sm">{{ $pelanggaran->poin }} Poin</span>
                                    </div>
                                    <p class="text-sm text-slate-500 mt-1">
                                        Tanggal: {{ $pelanggaran->tanggal_kejadian->format('d M Y') }}
                                    </p>
                                </li>
                            @empty
                                 <li class="p-6 text-center text-slate-500 text-sm">
                                    <svg class="mx-auto h-8 w-8 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="mt-2 block">Alhamdulillah, tidak ada riwayat pelanggaran.</span>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
