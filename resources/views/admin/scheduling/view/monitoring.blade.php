<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Jumlah Jam Ngajar Guru') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ search: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- STATS CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Total Guru -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500 truncate">Total Guru</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalTeachers }}</div>
                </div>

                <!-- Total Jam -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500 truncate">Total Jam Terjadwal</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalHours }} <span class="text-sm font-normal text-gray-500">Jam</span></div>
                </div>

                <!-- Rata-rata -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-sm font-medium text-gray-500 truncate">Rata-rata Jam/Guru</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $averageHours }} <span class="text-sm font-normal text-gray-500">Jam</span></div>
                </div>

                <!-- Jam Tertinggi -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-orange-500">
                    <div class="text-sm font-medium text-gray-500 truncate">Beban Tertinggi</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $maxHours }} <span class="text-sm font-normal text-gray-500">Jam</span></div>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <!-- TOOLBAR -->
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div class="w-full md:w-1/3 relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input x-model="search" type="text" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari nama guru atau kode...">
                        </div>
                        <div class="flex gap-2">
                             <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200">
                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Print
                            </button>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nama Guru</th>
                                    <th scope="col" class="px-6 py-3">Total Jam</th>
                                    <th scope="col" class="px-6 py-3">Distribusi Beban</th>
                                    <th scope="col" class="px-6 py-3">Mata Pelajaran & Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teachers as $teacher)
                                <tr class="bg-white border-b hover:bg-gray-50 transition-colors" x-show="!search || '{{ strtolower($teacher->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($teacher->teacher_code) }}'.includes(search.toLowerCase())">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                                {{ substr($teacher->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-base font-semibold">{{ $teacher->name }}</div>
                                                <div class="font-normal text-gray-500 text-xs">{{ $teacher->teacher_code ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-lg font-bold text-gray-900">{{ $teacher->schedules_count }}</div>
                                        <span class="text-xs text-gray-500">Jam Pelajaran</span>
                                    </td>
                                    <td class="px-6 py-4 w-1/4">
                                        @php
                                            $percentage = $maxHours > 0 ? ($teacher->schedules_count / $maxHours) * 100 : 0;
                                            $colorClass = 'bg-blue-600';
                                            if($teacher->schedules_count > 24) $colorClass = 'bg-red-500'; // High load
                                            elseif($teacher->schedules_count < 12) $colorClass = 'bg-yellow-400'; // Low load
                                            else $colorClass = 'bg-green-500'; // Normal load
                                        @endphp
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                            <div class="{{ $colorClass }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500 flex justify-between">
                                            <span>0</span>
                                            <span>{{ number_format($percentage, 0) }}% dari max</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @php
                                                // Group subjects to show concise info
                                                $subjects = $teacher->schedules->groupBy('subject.nama_pelajaran');
                                            @endphp
                                            
                                            @if($subjects->isEmpty())
                                                <span class="text-gray-400 italic text-xs">Belum ada jadwal</span>
                                            @else
                                                @foreach($subjects as $subjectName => $schedules)
                                                    <div class="inline-flex items-center px-2 py-1 mr-1 text-xs font-medium text-blue-800 bg-blue-100 rounded" title="Kelas: {{ $schedules->pluck('kelas.nama_kelas')->unique()->join(', ') }}">
                                                        {{ $subjectName }}
                                                        <span class="ml-1 px-1 bg-white rounded-md text-[10px] text-blue-600 border border-blue-200">
                                                            {{ $schedules->count() }} Jam
                                                        </span>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-xs text-gray-500 text-center">
                        Menampilkan semua guru terdaftar.
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
