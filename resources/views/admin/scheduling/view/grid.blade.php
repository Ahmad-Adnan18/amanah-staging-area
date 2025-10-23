<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

            <!-- Header Section dengan Action Buttons -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 mb-8">
                <div class="flex flex-col lg:flex-row justify-between lg:items-center gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-red-100 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Jadwal Pelajaran (Admin)</h1>
                                <p class="mt-1 text-sm text-slate-600">Tampilan global jadwal pelajaran - Sistem Hybrid</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.scheduling.manual.grid') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Manual
                        </a>
                        <a href="{{ route('admin.schedule.swap.show') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:bg-amber-700 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Tukar Jadwal
                        </a>
                        <a href="{{ route('admin.generator.show') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Generator
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success Notification -->
            @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-2xl shadow-sm mb-6 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            @endif

            @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl shadow-sm mb-6 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            @endif

            <!-- Schedule Sections -->
            <div class="space-y-8">
                @foreach ($days as $dayKey => $dayName)
                <section class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

                    <!-- Day Header -->
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-red-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-slate-800">{{ $dayName }}</h2>
                        </div>
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden lg:block">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 whitespace-nowrap w-48 sticky left-0 bg-slate-50 z-20 border-r border-slate-200">
                                            <div class="flex items-center gap-2">
                                                <div class="bg-white p-1.5 rounded-lg border border-slate-200">
                                                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                </div>
                                                <span>Kelas</span>
                                            </div>
                                        </th>
                                        @foreach ($timeSlots as $timeSlot)
                                        <th class="px-4 py-4 text-center text-sm font-semibold text-slate-700 whitespace-nowrap bg-slate-50/80">
                                            <div class="flex flex-col items-center">
                                                <span class="text-xs text-slate-500 mb-1">Jam</span>
                                                <span class="text-sm font-semibold">{{ $timeSlot }}</span>
                                            </div>
                                        </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @forelse ($classes as $class)
                                    <tr class="hover:bg-slate-50/50 transition-colors duration-200 group">
                                        <td class="px-6 py-4 text-sm font-semibold text-slate-900 bg-white whitespace-nowrap sticky left-0 z-10 border-r border-slate-200 group-hover:bg-slate-50/50">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-sm font-semibold border border-red-200 min-w-[80px] text-center">
                                                    {{ $class->nama_kelas }}
                                                </div>
                                            </div>
                                        </td>
                                        @foreach ($timeSlots as $timeSlot)
                                        <td class="px-3 py-3 text-sm border-l border-slate-100 text-center align-top min-h-[120px] group-hover:bg-slate-50/30 transition-colors duration-200">

                                            @if ($grid[$class->id][$dayKey][$timeSlot])
                                            @php $schedule = $grid[$class->id][$dayKey][$timeSlot]; @endphp
                                            <div class="p-3 bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl text-left h-full flex flex-col justify-between shadow-sm hover:shadow-md transition-all duration-200 relative group/card">

                                                <!-- Schedule Content -->
                                                <div class="space-y-2">
                                                    <div class="font-semibold text-slate-900 text-sm leading-tight">
                                                        {{ $schedule->subject->nama_pelajaran ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-slate-700 text-sm flex items-center gap-1">
                                                        <svg class="w-3 h-3 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                        <span class="truncate">{{ $schedule->teacher->name ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between mt-2 pt-2 border-t border-red-200/50">
                                                    <div class="text-slate-500 text-xs flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                        </svg>
                                                        {{ $schedule->room->name ?? 'N/A' }}
                                                    </div>
                                                </div>

                                                <!-- Action Menu -->
                                                <div class="absolute top-2 right-2 opacity-0 group-hover/card:opacity-100 transition-opacity duration-200">
                                                    <div class="relative" x-data="{ open: false }">
                                                        <button @click="open = !open" class="p-1.5 rounded-lg bg-white/80 backdrop-blur-sm shadow-sm border border-slate-200 hover:bg-white hover:shadow-md transition-all duration-200">
                                                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                            </svg>
                                                        </button>

                                                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @click.away="open = false" class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-slate-200 z-10 overflow-hidden">
                                                            <div class="py-1">
                                                                <a href="{{ route('admin.scheduling.manual.edit', $schedule) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                    </svg>
                                                                    Edit Jadwal
                                                                </a>
                                                                <form action="{{ route('admin.scheduling.manual.destroy', $schedule) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')" class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm text-red-700 hover:bg-red-50 transition-colors duration-200">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                        </svg>
                                                                        Hapus Jadwal
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @else
                                            <div class="h-full flex items-center justify-center relative group/empty">
                                                <div class="text-slate-400 text-sm italic opacity-100 group-hover/empty:opacity-300 transition-opacity duration-200">Kosong</div>

                                            </div>
                                            @endif

                                        </td>
                                        @endforeach
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ count($timeSlots) + 1 }}" class="px-6 py-12 text-center text-slate-500">
                                            <div class="flex flex-col items-center space-y-4">
                                                <div class="bg-slate-100 p-4 rounded-2xl">
                                                    <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-base font-medium text-slate-600">Tidak ada kelas aktif untuk ditampilkan.</p>
                                                    <p class="text-sm text-slate-500 mt-1">Silakan tambah kelas terlebih dahulu.</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Accordion View -->
                    <div class="lg:hidden">
                        <div class="p-4 space-y-4">
                            @forelse ($classes as $class)
                            <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
                                <!-- Class Header -->
                                <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-4 py-3 border-b border-slate-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2">
                                            <div class="bg-red-100 text-red-700 px-2.5 py-1 rounded-lg text-sm font-semibold border border-red-200">
                                                {{ $class->nama_kelas }}
                                            </div>
                                        </h3>
                                        <span class="text-xs text-slate-500 bg-white px-2 py-1 rounded-lg border border-slate-200">
                                            {{ count(array_filter($grid[$class->id][$dayKey] ?? [])) }}/{{ count($timeSlots) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Class Schedule -->
                                <div class="p-4 space-y-3">
                                    @foreach ($timeSlots as $timeSlot)
                                    @if ($grid[$class->id][$dayKey][$timeSlot])
                                    @php $schedule = $grid[$class->id][$dayKey][$timeSlot]; @endphp
                                    <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-xl p-4 relative">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="text-sm font-semibold text-slate-700 bg-white px-2 py-1 rounded-lg border border-slate-200">
                                                Jam {{ $timeSlot }}
                                            </div>
                                            <div class="text-xs text-slate-500 bg-white px-2 py-1 rounded-lg border border-slate-200 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                {{ $schedule->room->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="text-sm font-semibold text-slate-900">
                                                {{ $schedule->subject->nama_pelajaran ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-slate-700 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span>{{ $schedule->teacher->name ?? 'N/A' }}</span>
                                            </div>
                                        </div>

                                        <!-- Mobile Actions -->
                                        <div class="flex justify-end gap-2 mt-3 pt-3 border-t border-red-200/50">
                                            <a href="{{ route('admin.scheduling.manual.edit', $schedule) }}" class="inline-flex items-center gap-1 text-xs bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.scheduling.manual.destroy', $schedule) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Hapus jadwal ini?')" class="inline-flex items-center gap-1 text-xs bg-red-100 text-red-700 px-3 py-1.5 rounded-lg hover:bg-red-200 transition-colors duration-200">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @else
                                    <div class="p-4 text-center text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-300 hover:border-red-300 hover:bg-red-50 transition-all duration-200">
                                        <div class="text-sm mb-2">Jam {{ $timeSlot }} - Kosong</div>
                                        <a href="{{ route('admin.scheduling.manual.create') }}?kelas_id={{ $class->id }}&day_of_week={{ $dayKey }}&time_slot={{ $timeSlot }}" class="inline-flex items-center gap-1 text-sm text-red-600 hover:text-red-800 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Tambah Jadwal
                                        </a>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-12 text-slate-500 bg-white rounded-xl border border-slate-200">
                                <div class="flex flex-col items-center space-y-4">
                                    <div class="bg-slate-100 p-4 rounded-2xl">
                                        <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-base font-medium text-slate-600">Tidak ada kelas aktif untuk ditampilkan.</p>
                                        <p class="text-sm text-slate-500 mt-1">Silakan tambah kelas terlebih dahulu.</p>
                                    </div>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </section>
                @endforeach
            </div>
        </div>
    </div>

    <!-- AlpineJS untuk dropdown menu -->
    <script src="//unpkg.com/alpinejs" defer></script>
</x-app-layout>
