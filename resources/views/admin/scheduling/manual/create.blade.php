{{-- File: resources/views/admin/scheduling/manual/create.blade.php --}}
<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Tambah Jadwal Manual</h1>
                        <p class="mt-1 text-slate-600">Tambahkan jadwal pelajaran secara manual ke sistem</p>
                    </div>
                    <a href="{{ route('admin.schedule.view.grid') }}" 
                       class="inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500">
                        Kembali ke Grid
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <form action="{{ route('admin.scheduling.manual.store') }}" method="POST">
                    @csrf
                    
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-gray-900">Detail Jadwal</h3>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Kelas -->
                        <div>
                            <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                            <select name="kelas_id" id="kelas_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                <option value="">Pilih Kelas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('kelas_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mata Pelajaran -->
                        <div>
                            <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" id="mata_pelajaran_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('mata_pelajaran_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->nama_pelajaran }} ({{ $subject->tingkatan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('mata_pelajaran_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Guru -->
                        <div>
                            <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Guru</label>
                            <select name="teacher_id" id="teacher_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                <option value="">Pilih Guru</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }} ({{ $teacher->teacher_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ruangan -->
                        <div>
                            <label for="room_id" class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                            <select name="room_id" id="room_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                <option value="">Pilih Ruangan</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }} ({{ $room->type }})
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hari dan Jam -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                                <select name="day_of_week" id="day_of_week" required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                    <option value="">Pilih Hari</option>
                                    @foreach($days as $key => $day)
                                        <option value="{{ $key }}" {{ old('day_of_week') == $key ? 'selected' : '' }}>
                                            {{ $day }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('day_of_week')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="time_slot" class="block text-sm font-medium text-gray-700 mb-2">Jam Ke</label>
                                <select name="time_slot" id="time_slot" required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                    <option value="">Pilih Jam</option>
                                    @foreach($timeSlots as $slot)
                                        <option value="{{ $slot }}" {{ old('time_slot') == $slot ? 'selected' : '' }}>
                                            Jam ke-{{ $slot }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('time_slot')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-4">
                        <a href="{{ route('admin.schedule.view.grid') }}" 
                           class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                            Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="mt-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">Terjadi kesalahan:</span>
                    </div>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>