{{-- File: resources/views/admin/scheduling/manual/edit.blade.php --}}
<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Jadwal Manual</h1>
                        <p class="mt-1 text-slate-600">Edit jadwal pelajaran yang sudah ada</p>
                    </div>
                    <a href="{{ route('admin.schedule.view.grid') }}" 
                       class="inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-500">
                        Kembali ke Grid
                    </a>
                </div>
            </div>

            <!-- PERUBAHAN PENTING: Form Delete dipindahkan ke LUAR Form Edit -->
            <!-- Delete Form Standalone -->
            <div class="mb-6">
                <form action="{{ route('admin.scheduling.manual.destroy', $schedule) }}" method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Jadwal Ini
                    </button>
                </form>
            </div>

            <!-- Form Edit -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <form action="{{ route('admin.scheduling.manual.update', $schedule) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-gray-900">Detail Jadwal</h3>
                        <p class="mt-1 text-sm text-slate-600">
                            Mengedit jadwal: <strong>{{ $schedule->subject->nama_pelajaran }}</strong> - 
                            <strong>{{ $schedule->teacher->name }}</strong> - 
                            <strong>{{ $schedule->kelas->nama_kelas }}</strong>
                        </p>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Kelas -->
                        <div>
                            <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                            <select name="kelas_id" id="kelas_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md tom-select-kelas">
                                <option value="">Pilih Kelas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('kelas_id', $schedule->kelas_id) == $class->id ? 'selected' : '' }}>
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
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md tom-select-subject">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('mata_pelajaran_id', $schedule->mata_pelajaran_id) == $subject->id ? 'selected' : '' }}>
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
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md tom-select-teacher">
                                <option value="">Pilih Guru</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $schedule->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }} ({{ $teacher->teacher_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Informasi Ruangan (diambil dari kelas) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                            <div id="room-info" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 rounded-md bg-green-100 text-green-800">
                                @if($schedule->kelas->room)
                                    {{ $schedule->kelas->room->name }} ({{ $schedule->kelas->room->type }})
                                @else
                                    Tidak ada ruangan ditentukan untuk kelas ini
                                @endif
                            </div>
                            <input type="hidden" name="room_id" id="room_id" value="{{ $schedule->kelas->room_id ?? '' }}">
                        </div>

                        <!-- Hari dan Jam -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                                <select name="day_of_week" id="day_of_week" required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                    <option value="">Pilih Hari</option>
                                    @foreach($days as $key => $day)
                                        <option value="{{ $key }}" {{ old('day_of_week', $schedule->day_of_week) == $key ? 'selected' : '' }}>
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
                                        <option value="{{ $slot }}" {{ old('time_slot', $schedule->time_slot) == $slot ? 'selected' : '' }}>
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

                    <!-- Form Actions - HANYA untuk Edit -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-4">
                        <a href="{{ route('admin.schedule.view.grid') }}" 
                           class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Jadwal
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

            <!-- Success Message -->
            @if (session('success'))
                <div class="mt-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-2xl shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Tom Select CSS -->
            <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize Tom Select
                    new TomSelect('#kelas_id', {
                        plugins: ['dropdown_input'],
                        create: false,
                        allowEmptyOption: true,
                        sortField: 'text',
                    });
                    
                    new TomSelect('#mata_pelajaran_id', {
                        plugins: ['dropdown_input'],
                        create: false,
                        allowEmptyOption: true,
                        sortField: 'text',
                    });
                    
                    new TomSelect('#teacher_id', {
                        plugins: ['dropdown_input'],
                        create: false,
                        allowEmptyOption: true,
                        sortField: 'text',
                    });
                });
            </script>
        </div>
    </div>
</x-app-layout>