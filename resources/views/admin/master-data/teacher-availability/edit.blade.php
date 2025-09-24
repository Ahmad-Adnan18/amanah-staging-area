<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">
                <form action="{{ route('admin.teacher-availability.update', $teacher) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Header Halaman -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                            <div>
                                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Atur Ketersediaan</h1>
                                <p class="mt-1 text-slate-600">Tandai (centang) jam di mana <span class="font-bold">{{ $teacher->name }}</span> <span class="font-bold text-red-600">TIDAK BISA</span> mengajar.</p>
                            </div>
                            <div class="flex gap-4">
                                <a href="{{ route('admin.teacher-availability.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 flex-shrink-0">
                                    Kembali
                                </a>
                                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 flex-shrink-0">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Grid Ketersediaan -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-center">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase">JAM KE</th>
                                    @foreach ($days as $dayName)
                                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase">{{ $dayName }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @foreach ($timeSlots as $slot)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-700">{{ $slot }}</td>
                                    @foreach ($days as $dayIndex => $dayName)
                                        <td class="px-4 py-3">
                                            <input type="checkbox" name="unavailable_slots[]" 
                                                   value="{{ $dayIndex }}-{{ $slot }}"
                                                   class="h-6 w-6 rounded border-gray-300 text-red-600 focus:ring-red-500"
                                                   @if(isset($unavailableSlots[$dayIndex . '-' . $slot])) checked @endif>
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
