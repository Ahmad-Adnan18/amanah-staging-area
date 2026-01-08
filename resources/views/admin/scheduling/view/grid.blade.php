<x-app-layout>
    <div class="bg-slate-50 min-h-screen" x-data="{ activeTab: {{ array_key_first($days) }} }">
        <div class="max-w-7xl px-3 sm:px-4 lg:px-8 py-4 md:py-8 mx-auto">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900 group flex items-center gap-3">
                        <div class="p-2 bg-red-600 rounded-xl shadow-lg shadow-red-200 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        Jadwal Pelajaran
                    </h1>
                    <p class="mt-2 text-slate-500 font-medium">Monitoring jadwal pelajaran santri secara real-time.</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.scheduling.manual.grid') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl font-semibold text-sm text-slate-700 hover:bg-slate-50 hover:text-red-600 hover:border-red-200 focus:ring-2 focus:ring-red-100 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Mode Edit
                    </a>
                    <a href="{{ route('admin.schedule.swap.show') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl font-semibold text-sm text-slate-700 hover:bg-slate-50 hover:text-amber-600 hover:border-amber-200 focus:ring-2 focus:ring-amber-100 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        Tukar Jadwal
                    </a>
                    <a href="{{ route('admin.generator.show') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-red-700 focus:ring-4 focus:ring-red-100 transition-all duration-200 shadow-lg shadow-red-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        Generator Jadwal
                    </a>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="mb-6 overflow-x-auto pb-2">
                <nav class="flex space-x-2 md:space-x-4 min-w-max" aria-label="Tabs">
                    @foreach ($days as $dayKey => $dayName)
                    <button 
                        @click="activeTab = {{ $dayKey }}" 
                        :class="activeTab === {{ $dayKey }} 
                            ? 'bg-white text-red-600 shadow-md ring-1 ring-red-100' 
                            : 'bg-slate-100/50 text-slate-500 hover:bg-white hover:text-slate-700 hover:shadow-sm'"
                        class="px-5 py-3 rounded-xl font-semibold text-sm md:text-base transition-all duration-200 flex items-center gap-2.5 outline-none focus:ring-2 focus:ring-red-500/20">
                        <span class="w-2 h-2 rounded-full" :class="activeTab === {{ $dayKey }} ? 'bg-red-500' : 'bg-slate-300'"></span>
                        {{ $dayName }}
                    </button>
                    @endforeach
                </nav>
            </div>

            <!-- Content Area -->
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200 relative">
                
                @foreach ($days as $dayKey => $dayName)
                <div x-show="activeTab === {{ $dayKey }}" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="min-h-[500px]">
                    
                    <!-- Table Header Gradient -->
                    <div class="h-2 bg-gradient-to-r from-red-500 via-orange-500 to-amber-500"></div>

                    <!-- Scrollable Table Container -->
                    <div class="overflow-x-scroll w-full pb-8 mb-4 relative">
                        <table class="min-w-max text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200">
                                    {{-- Performance: Removed backdrop-blur and complex shadow --}}
                                    <th class="sticky left-0 z-20 bg-slate-50 p-4 text-sm font-bold text-slate-700 min-w-[140px] border-r border-slate-200 shadow-sm">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            KELAS
                                        </div>
                                    </th>
                                    @foreach ($timeSlots as $timeSlot)
                                    <th class="p-4 text-sm font-bold text-slate-600 min-w-[180px] text-center border-r border-slate-100 last:border-0 bg-slate-50/50">
                                        <div class="inline-flex flex-col items-center">
                                            <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-0.5">Jam Ke</span>
                                            <span class="bg-white px-2 py-0.5 rounded-md shadow-sm border border-slate-200 text-slate-800">{{ $timeSlot }}</span>
                                        </div>
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($classes as $class)
                                <tr class="hover:bg-slate-50/60 transition-colors duration-150 group">
                                    <!-- Sticky Class Column -->
                                    {{-- Performance: Simplyfied Shadow --}}
                                    <td class="sticky left-0 z-10 bg-white group-hover:bg-slate-50 transition-colors duration-150 p-4 border-r border-slate-200 shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-50 to-red-100 text-red-700 flex items-center justify-center font-bold text-sm border border-red-200/50">
                                                {{ $class->nama_kelas }}
                                            </div>
                                            <div class="hidden sm:block">
                                                <div class="text-xs font-medium text-slate-500">Wali Kelas</div>
                                                <div class="text-sm font-semibold text-slate-800 truncate max-w-[100px]">{{ $class->wali_kelas->name ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Schedule Cells -->
                                    @foreach ($timeSlots as $timeSlot)
                                    <td class="p-3 border-r border-slate-100 last:border-0 align-top h-[140px]">
                                        @if ($grid[$class->id][$dayKey][$timeSlot])
                                            @php $schedule = $grid[$class->id][$dayKey][$timeSlot]; @endphp
                                            {{-- Performance: Removed transition-all, ring, and hover:shadow-md. Added will-change-transform if needed but keeping it simple first. --}}
                                            <div class="h-full w-full bg-white rounded-xl border border-slate-200 p-3 hover:border-red-300 transition-colors duration-150 flex flex-col justify-between group/card relative overflow-hidden">
                                                
                                                <!-- Decorative Top Bar -->
                                                <div class="absolute top-0 left-0 w-full h-1 bg-red-500 opacity-0 group-hover/card:opacity-100 transition-opacity duration-200"></div>

                                                <div>
                                                    <div class="font-bold text-slate-800 text-sm leading-tight mb-1 line-clamp-2" title="{{ $schedule->subject->nama_pelajaran ?? 'N/A' }}">
                                                        {{ $schedule->subject->nama_pelajaran ?? 'Subject N/A' }}
                                                    </div>
                                                    
                                                    <div class="flex items-start gap-1.5 mt-2">
                                                        <div class="mt-0.5 min-w-[16px]">
                                                            <div class="w-4 h-4 rounded-full bg-slate-100 flex items-center justify-center">
                                                                <svg class="w-2.5 h-2.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <span class="text-xs text-slate-600 font-medium leading-tight line-clamp-2">
                                                            {{ $schedule->teacher->name ?? 'Teacher N/A' }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="mt-3 pt-2 border-t border-slate-100 flex justify-between items-center">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-50 text-slate-600 text-[10px] font-bold uppercase tracking-wide border border-slate-200">
                                                        {{ $schedule->room->name ?? 'Any' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="h-full w-full rounded-xl border border-dashed border-slate-200 bg-slate-50/50 flex flex-col items-center justify-center gap-2 group/empty transition-colors duration-150 hover:bg-slate-50">
                                                <div class="w-1 h-8 rounded-full bg-slate-200/60 group-hover/empty:bg-slate-300"></div>
                                            </div>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ count($timeSlots) + 1 }}" class="p-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-slate-900">Belum ada kelas</h3>
                                            <p class="text-slate-500 max-w-sm mx-auto mt-2">Silakan tambahkan data kelas terlebih dahulu di menu Akademik.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach

            </div>
            
            <!-- Footer Hints -->
            <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-400 px-2">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 bg-white border border-slate-200 rounded shadow-sm"></div>
                        <span>Jadwal Terisi</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 bg-slate-50 border border-dashed border-slate-300 rounded"></div>
                        <span>Kosong</span>
                    </div>
                </div>
                <div>
                   Menampilkan {{ count($classes) }} Kelas
                </div>
            </div>

        </div>
    </div>
    
    <!-- AlpineJS Dependencies -->
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <style>
        /* Ensure sticky columns have high z-index and background */
        thead th.sticky {
            z-index: 30 !important;
        }
        tbody td.sticky {
            z-index: 20 !important;
        }
    </style>
</x-app-layout>
