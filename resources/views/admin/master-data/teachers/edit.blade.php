    <x-app-layout>
        <div class="bg-slate-50 min-h-screen">
            <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="space-y-8">

                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Data Guru</h1>
                        <p class="mt-1 text-slate-600">Perbarui informasi untuk guru: <span class="font-semibold text-red-700">{{ $teacher->name }}</span></p>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="p-6 space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $teacher->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>
                                <div>
                                    <label for="teacher_code" class="block text-sm font-medium text-gray-700">Kode Guru (Opsional)</label>
                                    <input type="text" name="teacher_code" id="teacher_code" value="{{ old('teacher_code', $teacher->teacher_code) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <x-input-error class="mt-2" :messages="$errors->get('teacher_code')" />
                                </div>

                                <!-- [TAMBAHAN] Dropdown untuk menghubungkan ke Akun User -->
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700">Hubungkan ke Akun Login (Opsional)</label>
                                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        <option value="">-- Tidak Dihubungkan --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" @selected(old('user_id', $teacher->user_id) == $user->id)>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-slate-500 mt-1">Pilih akun jika guru ini perlu login dan melihat dashboard pribadinya.</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                                </div>

                            </div>
                            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-4">
                                <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</a>
                                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
    

