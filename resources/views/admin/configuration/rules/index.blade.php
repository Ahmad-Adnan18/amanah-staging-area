<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Aturan Penjadwalan</h1>
                        <p class="mt-1 text-slate-600 text-sm sm:text-base">Atur waktu terlarang secara global dan tentukan prioritas jam untuk setiap kategori mata pelajaran.</p>
                    </div>
                </div>

                <!-- [MODIFIKASI] Panel Notifikasi -->
                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif
                @if (session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif


                <form action="{{ route('admin.rules.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <!-- Panel Waktu Terblokir -->
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                            <div class="p-4 sm:p-6 border-b border-slate-200">
                                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Waktu Terblokir (Global)</h2>
                                <p class="mt-1 text-slate-600 text-sm sm:text-base">Tandai jam di mana <span class="font-semibold">tidak boleh ada kegiatan belajar mengajar</span> untuk semua kelas (contoh: upacara, istirahat).</p>
                            </div>
                            <div class="p-4 sm:p-6 overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 border border-slate-200">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-3 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-slate-500 uppercase">Hari</th>
                                            @foreach ($timeSlots as $slot)
                                                <th class="px-3 py-2 sm:px-4 sm:py-3 text-center text-xs sm:text-sm font-semibold text-slate-500 uppercase">Jam Ke-{{ $slot }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @foreach ($days as $dayId => $dayName)
                                            <tr>
                                                <td class="px-3 py-2 sm:px-4 sm:py-3 font-medium text-slate-800 text-sm">{{ $dayName }}</td>
                                                @foreach ($timeSlots as $slot)
                                                    <td class="px-3 py-2 sm:px-4 sm:py-3 text-center">
                                                        <input type="checkbox" name="blocked_times[{{ $dayId }}][{{ $slot }}]" 
                                                               value="1" 
                                                               @checked(isset($blockedTimes[$dayId . '-' . $slot]))
                                                               class="h-4 w-4 sm:h-5 sm:w-5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Panel Prioritas Jam -->
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                             <div class="p-4 sm:p-6 border-b border-slate-200">
                                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Prioritas Jam per Kategori Mapel</h2>
                                <p class="mt-1 text-slate-600 text-sm sm:text-base">Tandai jam-jam yang <span class="font-semibold">diizinkan</span> untuk setiap kategori mata pelajaran. Jika tidak ada yang ditandai untuk satu kategori, maka kategori tersebut bisa dijadwalkan di jam mana pun.</p>
                            </div>
                            <div class="p-4 sm:p-6 space-y-6">
                                @forelse ($subjectCategories as $category)
                                    <div>
                                        <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2 sm:mb-3">{{ $category }}</h3>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-slate-200 border border-slate-200">
                                                <thead class="bg-slate-50">
                                                    <tr>
                                                        <th class="px-3 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-slate-500 uppercase">Hari</th>
                                                        @foreach ($timeSlots as $slot)
                                                            <th class="px-3 py-2 sm:px-4 sm:py-3 text-center text-xs sm:text-sm font-semibold text-slate-500 uppercase">Jam Ke-{{ $slot }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-200">
                                                    @foreach ($days as $dayId => $dayName)
                                                        <tr>
                                                            <td class="px-3 py-2 sm:px-4 sm:py-3 font-medium text-slate-800 text-sm">{{ $dayName }}</td>
                                                            @foreach ($timeSlots as $slot)
                                                                <td class="px-3 py-2 sm:px-4 sm:py-3 text-center">
                                                                    <input type="checkbox" name="priorities[{{ $category }}][{{ $dayId }}][{{ $slot }}]" 
                                                                           value="1" 
                                                                           @checked(isset($hourPriorities[$category . '-' . $dayId . '-' . $slot]))
                                                                           class="h-4 w-4 sm:h-5 sm:w-5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-slate-500 text-sm">Belum ada kategori mata pelajaran yang dibuat. Silakan tambahkan kategori pada menu Manajemen Mata Pelajaran terlebih dahulu.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 flex justify-end">
                                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                    Simpan Semua Aturan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

