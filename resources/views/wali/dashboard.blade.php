<x-app-layout>
    <div class="min-h-screen" style="background: linear-gradient(130deg, #fcfcfc 0%, #f5f7fa 50%, #f0f4f8 100%);">
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="max-w-4xl mx-auto space-y-8">

                <!-- Header Modern -->
                <div class="text-center sm:text-left">
                    <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-red-600 to-red-800 bg-clip-text text-transparent">
                        Portal Wali Santri
                    </h1>
                    <p class="mt-2 text-slate-600">
                        Selamat datang, <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span>.
                        Berikut ringkasan perkembangan ananda.
                    </p>
                </div>

                <!-- Profil Santri Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-all hover:shadow-md">
                    <div class="p-5 sm:p-6">
                        <div class="flex flex-col sm:flex-row items-center gap-5 sm:gap-6">
                            <!-- Avatar -->
                            <div class="relative flex-shrink-0">
                                <img class="h-24 w-24 rounded-full object-cover ring-2 ring-red-100 shadow-sm" src="{{ $santri->foto ? Storage::url($santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($santri->nama).'&background=FBBF24&color=fff&size=128' }}" alt="Foto {{ $santri->nama }}">
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 rounded-full border-2 border-white"></div>
                            </div>

                            <!-- Info Santri -->
                            <div class="flex-grow w-full text-center sm:text-left space-y-3">
                                <h2 class="text-xl font-bold text-slate-900">{{ $santri->nama }}</h2>

                                <div class="space-y-2 text-sm text-slate-600">
                                    <div class="flex flex-wrap justify-center sm:justify-start gap-x-4 gap-y-1">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span class="font-medium">NIS:</span> {{ $santri->nis }}
                                        </div>
                                        <div class="hidden sm:block w-px h-4 bg-slate-300"></div>
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                            </svg>
                                            <span class="font-medium">Kelas:</span> {{ $santri->kelas->nama_kelas ?? 'N/A' }}
                                        </div>
                                        <div class="hidden sm:block w-px h-4 bg-slate-300"></div>
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="font-medium">Rayon:</span> {{ $santri->rayon }}
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <a href="{{ route('santri.profil.show', $santri) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-red-600 hover:text-red-800 transition-colors group">
                                        Lihat Profil Lengkap
                                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Izin & Pelanggaran -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Riwayat Perizinan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-all hover:shadow-md">
                        <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-5 py-4 border-b border-slate-200">
                            <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Riwayat Perizinan Terbaru
                            </h3>
                        </div>
                        <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto custom-scrollbar">
                            @forelse ($santri->perizinans->take(5) as $izin)
                            <div class="p-4 hover:bg-slate-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $izin->jenis_izin }}</p>
                                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                                            {{ Str::limit($izin->keterangan, 60) }}
                                        </p>
                                    </div>
                                    <span @class([ 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium whitespace-nowrap' , 'bg-green-100 text-green-800'=> $izin->status === 'Disetujui',
                                        'bg-yellow-100 text-yellow-800' => $izin->status === 'Diproses',
                                        'bg-red-100 text-red-800' => $izin->status === 'Ditolak',
                                        'bg-blue-100 text-blue-800' => $izin->status === 'Telah Kembali',
                                        'bg-slate-100 text-slate-800' => !in_array($izin->status, ['Disetujui', 'Diproses', 'Ditolak', 'Telah Kembali']),
                                        ])>
                                        {{ $izin->status }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 mt-2">
                                    {{ $izin->tanggal_mulai?->format('d M Y') ?? '–' }}
                                    @if($izin->tanggal_kembali) s.d {{ $izin->tanggal_kembali->format('d M Y') }} @endif
                                </p>
                            </div>
                            @empty
                            <div class="p-8 text-center text-slate-500">
                                <svg class="mx-auto h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                                <p class="mt-2 text-sm">Belum ada riwayat perizinan.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Riwayat Pelanggaran -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-all hover:shadow-md">
                        <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-5 py-4 border-b border-slate-200">
                            <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                Riwayat Pelanggaran Terbaru
                            </h3>
                        </div>
                        <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto custom-scrollbar">
                            @forelse ($santri->pelanggarans->take(5) as $pelanggaran)
                            <div class="p-4 hover:bg-slate-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $pelanggaran->jenis_pelanggaran }}</p>
                                        <p class="text-xs text-slate-500 mt-1">
                                            {{ $pelanggaran->tanggal_kejadian->format('d M Y') }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-bold text-red-700">
                                        {{ $pelanggaran->poin }} Poin
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="p-8 text-center text-slate-500">
                                <svg class="mx-auto h-10 w-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-2 text-sm">Alhamdulillah, tidak ada riwayat pelanggaran.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f9fafb;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

    </style>
</x-app-layout>
