<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-full mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 mb-8">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Jadwal Pelajaran (Admin)</h1>
                        <p class="mt-1 text-slate-600">Tampilan global jadwal pelajaran yang berhasil dibuat oleh sistem.</p>
                    </div>
                </div>
            </div>

            {{-- [PENAMBAHAN] Panel Notifikasi untuk menampilkan pesan sukses --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-2xl shadow-sm mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-12">
                @foreach ($days as $dayKey => $dayName)
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <div class="bg-slate-100 border-b border-slate-200 px-6 py-4">
                            <h2 class="text-xl font-bold text-slate-800">{{ $dayName }}</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 w-36">Kelas</th>
                                        @foreach ($timeSlots as $timeSlot)
                                            <th class="px-4 py-3 text-center text-sm font-semibold text-slate-500">{{ $timeSlot }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @forelse ($classes as $class)
                                        <tr>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-slate-900 bg-slate-50">
                                                {{ $class->nama_kelas }}
                                            </td>
                                            @foreach ($timeSlots as $timeSlot)
                                                <td class="whitespace-nowrap px-2 py-2 text-sm text-slate-500 border-l border-slate-200 text-center align-top h-24">
                                                    {{-- [PERUBAHAN] Mengembalikan detail guru --}}
                                                    @if ($grid[$class->id][$dayKey][$timeSlot])
                                                        @php $schedule = $grid[$class->id][$dayKey][$timeSlot]; @endphp
                                                        <div class="p-2 bg-red-50 border border-red-200 rounded-lg text-left h-full flex flex-col justify-between text-xs">
                                                            <div>
                                                                <div class="font-semibold text-slate-900">{{ $schedule->subject->nama_pelajaran ?? 'N/A' }}</div>
                                                                <div class="mt-1">{{ $schedule->teacher->name ?? 'N/A' }}</div>
                                                            </div>
                                                           <div class="text-slate-400 italic mt-2 text-right">{{ $schedule->room->name ?? 'N/A' }}</div>
                                                        </div>
                                                    @else
                                                        {{-- Slot Kosong --}}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($timeSlots) + 1 }}" class="px-6 py-12 text-center text-slate-500">
                                                Tidak ada kelas aktif untuk ditampilkan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

