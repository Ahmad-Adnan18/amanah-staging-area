{{-- resources/views/admin/settings/index.blade.php --}}
<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Aplikasi</h1>
                            <p class="mt-1 text-slate-600">Kelola logo, tanda tangan, dan informasi pondok.</p>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" onclick="resetForm()" class="inline-flex items-center justify-center gap-2 rounded-md bg-slate-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" />
                                </svg>
                                <span>Reset</span>
                            </button>
                            <button type="submit" form="settingsForm" class="inline-flex items-center justify-center gap-2 rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notifikasi Sukses -->
                @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-2xl shadow-sm flex justify-between items-center" role="alert">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.236 4.53L7.53 10.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-600 hover:text-green-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                        </svg>
                    </button>
                </div>
                @endif

                <!-- Form Settings -->
                <form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Section: Logo -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <div class="border-b border-slate-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Logo Aplikasi</h2>
                            <p class="text-sm text-slate-600 mt-1">Kelola semua logo yang digunakan dalam dokumen</p>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Logo Jadwal Pelajaran -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Logo Jadwal Pelajaran
                                    </label>
                                    <div class="space-y-3">
                                        @php($logoJadwal = \App\Models\AppSetting::getValue('logo_jadwal_pelajaran'))
                                        <div id="preview-logo-jadwal" class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer" onclick="document.getElementById('logo_jadwal_pelajaran').click()">
                                            @if($logoJadwal)
                                            <img src="{{ Storage::url($logoJadwal) }}" class="w-32 h-32 object-contain mx-auto rounded-lg" alt="Logo Jadwal Pelajaran">
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk mengganti</p>
                                            @else
                                            <div class="w-32 h-32 mx-auto flex items-center justify-center bg-white rounded-lg border">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk upload logo</p>
                                            @endif
                                        </div>
                                        <input type="file" id="logo_jadwal_pelajaran" name="logo_jadwal_pelajaran" class="hidden" accept="image/*">
                                    </div>
                                    <p class="text-xs text-slate-500">Format: PNG, JPG. Ukuran disarankan: 200x200px</p>
                                </div>

                                <!-- Logo Jadwal Mengajar -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Logo Jadwal Mengajar
                                    </label>
                                    <div class="space-y-3">
                                        @php($logoMengajar = \App\Models\AppSetting::getValue('logo_jadwal_mengajar'))
                                        <div id="preview-logo-mengajar" class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer" onclick="document.getElementById('logo_jadwal_mengajar').click()">
                                            @if($logoMengajar)
                                            <img src="{{ Storage::url($logoMengajar) }}" class="w-32 h-32 object-contain mx-auto rounded-lg" alt="Logo Jadwal Mengajar">
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk mengganti</p>
                                            @else
                                            <div class="w-32 h-32 mx-auto flex items-center justify-center bg-white rounded-lg border">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk upload logo</p>
                                            @endif
                                        </div>
                                        <input type="file" id="logo_jadwal_mengajar" name="logo_jadwal_mengajar" class="hidden" accept="image/*">
                                    </div>
                                    <p class="text-xs text-slate-500">Format: PNG, JPG. Ukuran disarankan: 200x200px</p>
                                </div>

                                <!-- Logo Surat Izin -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Logo Surat Izin
                                    </label>
                                    <div class="space-y-3">
                                        @php($logoSuratIzin = \App\Models\AppSetting::getValue('logo_surat_izin'))
                                        <div id="preview-logo-surat" class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer" onclick="document.getElementById('logo_surat_izin').click()">
                                            @if($logoSuratIzin)
                                            <img src="{{ Storage::url($logoSuratIzin) }}" class="w-32 h-32 object-contain mx-auto rounded-lg" alt="Logo Surat Izin">
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk mengganti</p>
                                            @else
                                            <div class="w-32 h-32 mx-auto flex items-center justify-center bg-white rounded-lg border">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk upload logo</p>
                                            @endif
                                        </div>
                                        <input type="file" id="logo_surat_izin" name="logo_surat_izin" class="hidden" accept="image/*">
                                    </div>
                                    <p class="text-xs text-slate-500">Format: PNG, JPG. Ukuran disarankan: 200x200px</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Tanda Tangan Umum -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <div class="border-b border-slate-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Tanda Tangan Umum</h2>
                            <p class="text-sm text-slate-600 mt-1">Kelola tanda tangan dan informasi penandatangan default</p>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Logo Tanda Tangan Umum -->
                                <div class="space-y-4">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Logo Tanda Tangan Umum
                                    </label>
                                    <div class="space-y-3">
                                        @php($logoTtd = \App\Models\AppSetting::getValue('logo_tanda_tangan'))
                                        <div id="preview-ttd" class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer" onclick="document.getElementById('logo_tanda_tangan').click()">
                                            @if($logoTtd)
                                            <img src="{{ Storage::url($logoTtd) }}" class="w-48 h-24 object-contain mx-auto rounded-lg" alt="Logo Tanda Tangan Umum">
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk mengganti</p>
                                            @else
                                            <div class="w-48 h-24 mx-auto flex items-center justify-center bg-white rounded-lg border">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk upload tanda tangan</p>
                                            @endif
                                        </div>
                                        <input type="file" id="logo_tanda_tangan" name="logo_tanda_tangan" class="hidden" accept="image/*">
                                    </div>
                                    <p class="text-xs text-slate-500">Format: JPG, PNG. Ukuran disarankan: 300x120px</p>
                                </div>

                                <!-- Informasi Penandatangan Umum -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Penandatangan Umum
                                        </label>
                                        <input type="text" name="nama_penandatangan" value="{{ \App\Models\AppSetting::getValue('nama_penandatangan') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" placeholder="Masukkan nama penandatangan">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Jabatan Penandatangan Umum
                                        </label>
                                        <input type="text" name="jabatan_penandatangan" value="{{ \App\Models\AppSetting::getValue('jabatan_penandatangan') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" placeholder="Masukkan jabatan penandatangan">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Baru: Tanda Tangan per Jenis Dokumen -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <div class="border-b border-slate-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Tanda Tangan per Jenis Dokumen</h2>
                            <p class="text-sm text-slate-600 mt-1">Kelola tanda tangan khusus untuk setiap jenis PDF</p>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Tanda Tangan untuk Jadwal -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Logo Tanda Tangan Jadwal
                                    </label>
                                    <div class="space-y-3">
                                        @php($logoTtdJadwal = \App\Models\AppSetting::getValue('logo_tanda_tangan_jadwal'))
                                        <div id="preview-ttd-jadwal" class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer" onclick="document.getElementById('logo_tanda_tangan_jadwal').click()">
                                            @if($logoTtdJadwal)
                                            <img src="{{ Storage::url($logoTtdJadwal) }}" class="w-48 h-24 object-contain mx-auto rounded-lg" alt="Logo Tanda Tangan Jadwal">
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk mengganti</p>
                                            @else
                                            <div class="w-48 h-24 mx-auto flex items-center justify-center bg-white rounded-lg border">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk upload</p>
                                            @endif
                                        </div>
                                        <input type="file" id="logo_tanda_tangan_jadwal" name="logo_tanda_tangan_jadwal" class="hidden" accept="image/*">
                                    </div>
                                    <p class="text-xs text-slate-500">Format: JPG, PNG. Ukuran disarankan: 300x120px</p>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Penandatangan Jadwal
                                        </label>
                                        <input type="text" name="nama_penandatangan_jadwal" value="{{ \App\Models\AppSetting::getValue('nama_penandatangan_jadwal') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" placeholder="Masukkan nama penandatangan">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Jabatan Penandatangan Jadwal
                                        </label>
                                        <input type="text" name="jabatan_penandatangan_jadwal" value="{{ \App\Models\AppSetting::getValue('jabatan_penandatangan_jadwal') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" placeholder="Masukkan jabatan penandatangan">
                                    </div>
                                </div>

                                <!-- Tanda Tangan untuk Guru Libur -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Logo Tanda Tangan Guru Libur
                                    </label>
                                    <div class="space-y-3">
                                        @php($logoTtdGuruLibur = \App\Models\AppSetting::getValue('logo_tanda_tangan_guru_libur'))
                                        <div id="preview-ttd-guru-libur" class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer" onclick="document.getElementById('logo_tanda_tangan_guru_libur').click()">
                                            @if($logoTtdGuruLibur)
                                            <img src="{{ Storage::url($logoTtdGuruLibur) }}" class="w-48 h-24 object-contain mx-auto rounded-lg" alt="Logo Tanda Tangan Guru Libur">
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk mengganti</p>
                                            @else
                                            <div class="w-48 h-24 mx-auto flex items-center justify-center bg-white rounded-lg border">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk upload</p>
                                            @endif
                                        </div>
                                        <input type="file" id="logo_tanda_tangan_guru_libur" name="logo_tanda_tangan_guru_libur" class="hidden" accept="image/*">
                                    </div>
                                    <p class="text-xs text-slate-500">Format: JPG, PNG. Ukuran disarankan: 300x120px</p>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Penandatangan Guru Libur
                                        </label>
                                        <input type="text" name="nama_penandatangan_guru_libur" value="{{ \App\Models\AppSetting::getValue('nama_penandatangan_guru_libur') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" placeholder="Masukkan nama penandatangan">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Jabatan Penandatangan Guru Libur
                                        </label>
                                        <input type="text" name="jabatan_penandatangan_guru_libur" value="{{ \App\Models\AppSetting::getValue('jabatan_penandatangan_guru_libur') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" placeholder="Masukkan jabatan penandatangan">
                                    </div>
                                </div>

                                <!-- Tanda Tangan untuk Surat Izin -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Logo Tanda Tangan Surat Izin
                                    </label>
                                    <div class="space-y-3">
                                        @php($logoTtdSuratIzin = \App\Models\AppSetting::getValue('logo_tanda_tangan_surat_izin'))
                                        <div id="preview-ttd-surat-izin" class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer" onclick="document.getElementById('logo_tanda_tangan_surat_izin').click()">
                                            @if($logoTtdSuratIzin)
                                            <img src="{{ Storage::url($logoTtdSuratIzin) }}" class="w-48 h-24 object-contain mx-auto rounded-lg" alt="Logo Tanda Tangan Surat Izin">
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk mengganti</p>
                                            @else
                                            <div class="w-48 h-24 mx-auto flex items-center justify-center bg-white rounded-lg border">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-2">Klik untuk upload</p>
                                            @endif
                                        </div>
                                        <input type="file" id="logo_tanda_tangan_surat_izin" name="logo_tanda_tangan_surat_izin" class="hidden" accept="image/*">
                                    </div>
                                    <p class="text-xs text-slate-500">Format: JPG, PNG. Ukuran disarankan: 300x120px</p>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Penandatangan Surat Izin
                                        </label>
                                        <input type="text" name="nama_penandatangan_surat_izin" value="{{ \App\Models\AppSetting::getValue('nama_penandatangan_surat_izin') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" placeholder="Masukkan nama penandatangan">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Jabatan Penandatangan Surat Izin
                                        </label>
                                        <input type="text" name="jabatan_penandatangan_surat_izin" value="{{ \App\Models\AppSetting::getValue('jabatan_penandatangan_surat_izin') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" placeholder="Masukkan jabatan penandatangan">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Informasi Pondok -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <div class="border-b border-slate-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Informasi Pondok</h2>
                            <p class="text-sm text-slate-600 mt-1">Kelola informasi identitas pondok pesantren</p>
                        </div>

                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Pondok
                                    </label>
                                    <input type="text" name="nama_pondok" value="{{ \App\Models\AppSetting::getValue('nama_pondok') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" placeholder="Masukkan nama pondok pesantren">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Alamat Pondok
                                    </label>
                                    <textarea name="alamat_pondok" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" placeholder="Masukkan alamat lengkap pondok">{{ \App\Models\AppSetting::getValue('alamat_pondok') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Telepon Pondok
                                    </label>
                                    <input type="text" name="telepon_pondok" value="{{ \App\Models\AppSetting::getValue('telepon_pondok') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" placeholder="Masukkan nomor telepon pondok">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewImage(inputId) {
            console.log('previewImage called for:', inputId);
            const input = document.getElementById(inputId);
            console.log('Input element:', input);
            if (!input || !input.files || !input.files[0]) {
                console.log('No file selected or input not found');
                return;
            }

            const file = input.files[0];
            console.log('Selected file:', file);
            if (!file.type.match('image.*')) {
                alert('Hanya file gambar yang diizinkan!');
                input.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB!');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                console.log('FileReader result:', e.target.result);
                const previewMap = {
                    'logo_jadwal_pelajaran': 'preview-logo-jadwal',
                    'logo_jadwal_mengajar': 'preview-logo-mengajar',
                    'logo_surat_izin': 'preview-logo-surat',
                    'logo_tanda_tangan': 'preview-ttd',
                    'logo_tanda_tangan_jadwal': 'preview-ttd-jadwal',
                    'logo_tanda_tangan_guru_libur': 'preview-ttd-guru-libur',
                    'logo_tanda_tangan_surat_izin': 'preview-ttd-surat-izin'
                };
                const container = document.getElementById(previewMap[inputId]);
                console.log('Preview container:', container);
                if (!container) {
                    console.log('Container not found for:', inputId);
                    return;
                }

                let cls = 'w-32 h-32 object-contain mx-auto rounded-lg';
                if (inputId.startsWith('logo_tanda_tangan')) cls = 'w-48 h-24 object-contain mx-auto rounded-lg';

                container.innerHTML = `<img src="${e.target.result}" class="${cls}" alt="Preview"><p class="text-xs text-slate-500 mt-2">Klik untuk mengganti</p>`;
                container.onclick = () => input.click();
            };
            reader.readAsDataURL(file);
        }

        // Pasang listener ke input secara langsung
        document.addEventListener('change', function(e) {
            const validInputs = [
                'logo_jadwal_pelajaran',
                'logo_jadwal_mengajar',
                'logo_surat_izin',
                'logo_tanda_tangan',
                'logo_tanda_tangan_jadwal',
                'logo_tanda_tangan_guru_libur',
                'logo_tanda_tangan_surat_izin'
            ];
            if (validInputs.includes(e.target.id)) {
                previewImage(e.target.id);
            }
        });
    </script>
    @endpush
</x-app-layout>