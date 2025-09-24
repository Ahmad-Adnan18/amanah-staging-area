<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Ketersediaan Guru</h1>
                        <p class="mt-1 text-slate-600">Atur jadwal dan lihat ringkasan ketersediaan mengajar untuk setiap guru.</p>
                    </div>
                </div>

                <!-- Fitur Pencarian -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4">
                    <form action="{{ route('admin.teacher-availability.index') }}" method="GET">
                        <label for="search" class="sr-only">Cari Guru</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-md border-gray-300 pl-10 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Ketik nama guru untuk mencari...">
                        </div>
                    </form>
                </div>


                <!-- Daftar Guru -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <ul role="list" class="divide-y divide-slate-200">
                        @forelse ($teachers as $teacher)
                        <li class="hover:bg-slate-50 transition-colors duration-200">
                            <a href="{{ route('admin.teacher-availability.edit', $teacher) }}" class="flex items-center justify-between p-4 sm:p-6">
                                <div class="flex items-center gap-4">
                                    <img class="h-10 w-10 sm:h-12 sm:w-12 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=FBBF24&color=78350F" alt="Avatar">
                                    <div>
                                        <p class="text-sm sm:text-base font-semibold text-gray-900 group-hover:text-red-700">{{ $teacher->name }}</p>

                                        @if($teacher->unavailabilities_count > 0)
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-100 text-green-800">
                                            Sudah Diatur
                                        </span>
                                        <p class="text-xs text-slate-500 mt-1">
                                            {{ $teacher->unavailabilities_count }} Sesi Diblokir
                                        </p>
                                        @else
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                                            Belum Diatur
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="hidden sm:block text-right">
                                        <p class="text-sm font-medium text-slate-900">{{ $teacher->unavailabilities_count ?? 0 }} Sesi Diblokir</p>
                                        <p class="text-xs text-slate-500">Tidak Bersedia Mengajar</p>
                                    </div>
                                    <svg class="h-5 w-5 text-slate-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </a>
                        </li>
                        @empty
                        <li class="px-6 py-12 text-center text-slate-500">
                            Tidak ada data guru yang cocok dengan pencarian.
                        </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Pagination -->
                @if ($teachers->hasPages())
                <div class="p-4 bg-white rounded-2xl shadow-lg border border-slate-200">
                    {{ $teachers->links() }}
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
