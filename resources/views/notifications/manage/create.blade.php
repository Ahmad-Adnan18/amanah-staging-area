<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8 pb-24 sm:pb-8">
            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900 mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                    Buat Notifikasi Baru
                </h1>
                <p class="mt-2 text-slate-600 text-sm sm:text-base">Isi form di bawah untuk membuat notifikasi baru</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.notifications.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                @csrf

                {{-- Title --}}
                <div class="mb-5">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                        placeholder="Masukkan judul notifikasi">
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Message --}}
                <div class="mb-5">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan <span class="text-red-500">*</span></label>
                    <textarea name="message" id="message" rows="5" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                        placeholder="Masukkan isi pesan notifikasi">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div class="mb-5">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe <span class="text-red-500">*</span></label>
                    <select name="type" id="type" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Target Roles --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Role</label>
                    <p class="text-xs text-slate-500 mb-3">Kosongkan untuk mengirim ke semua role (kecuali Wali Santri)</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach($targetRoles as $value => $label)
                        <label class="flex items-center gap-2 p-3 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="checkbox" name="target_roles[]" value="{{ $value }}" 
                                class="w-4 h-4 text-red-600 border-slate-300 rounded focus:ring-red-500"
                                {{ in_array($value, old('target_roles', [])) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('target_roles')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Publishing Options --}}
                <div class="mb-5 p-4 bg-slate-50 rounded-xl">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="publish_now" value="1" checked
                            class="w-4 h-4 text-red-600 border-slate-300 rounded focus:ring-red-500"
                            onchange="toggleScheduleFields(this)">
                        <span class="text-sm font-medium text-gray-700">Publish Sekarang</span>
                    </label>
                    
                    <div id="schedule-fields" class="hidden mt-4 space-y-4">
                        <div>
                            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Jadwal Publish</label>
                            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                        </div>
                    </div>
                </div>

                {{-- Expiry --}}
                <div class="mb-6">
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kadaluarsa <span class="text-slate-400">(Opsional)</span></label>
                    <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at') }}"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                    <p class="text-xs text-slate-500 mt-1">Kosongkan jika notifikasi tidak memiliki batas waktu</p>
                    @error('expires_at')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.notifications.index') }}" class="px-5 py-2.5 text-slate-700 font-medium hover:bg-slate-100 rounded-xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-colors shadow-sm">
                        Simpan Notifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleScheduleFields(checkbox) {
            const fields = document.getElementById('schedule-fields');
            if (checkbox.checked) {
                fields.classList.add('hidden');
            } else {
                fields.classList.remove('hidden');
            }
        }
    </script>
    @endpush
</x-app-layout>
