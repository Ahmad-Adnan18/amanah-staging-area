<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Kurikulum</h1>
                        <p class="mt-1 text-slate-600">Kelola template kurikulum dan terapkan paket mata pelajaran ke kelas.</p>
                    </div>
                </div>

                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Kolom Kiri: Manajemen Template -->
                    <div class="lg:col-span-1 space-y-8">
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                            <form action="{{ route('akademik.kurikulum.template.store') }}" method="POST">
                                @csrf
                                <div class="p-6">
                                    <h2 class="text-xl font-bold text-gray-900">Template Kurikulum</h2>
                                    <p class="mt-1 text-slate-600">Buat atau kelola paket mata pelajaran.</p>
                                </div>
                                <div class="p-6 border-t border-slate-200 space-y-4">
                                    <div>
                                        <label for="nama_template" class="block text-sm font-medium text-gray-700">Nama Template Baru</label>
                                        <input type="text" name="nama_template" id="nama_template" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Contoh: Kurikulum SMP">
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Buat Template</button>
                                </div>
                            </form>
                            <div class="border-t border-slate-200">
                                <ul class="divide-y divide-slate-200">
                                    @forelse($templates as $template)
                                        <li class="p-4 flex justify-between items-center hover:bg-slate-50">
                                            <div>
                                                <p class="font-semibold text-slate-800">{{ $template->nama_template }}</p>
                                                <p class="text-xs text-slate-500">{{ $template->mata_pelajarans_count }} Mata Pelajaran</p>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <a href="{{ route('akademik.kurikulum.template.edit', $template) }}" class="text-sm font-medium text-slate-600 hover:text-red-700">Atur</a>
                                                <form action="{{ route('akademik.kurikulum.template.destroy', $template) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus template ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Hapus</button>
                                                </form>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="p-6 text-center text-slate-500">Belum ada template.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Penerapan Template -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                            <form action="{{ route('akademik.kurikulum.apply') }}" method="POST">
                                @csrf
                                <div class="p-6">
                                    <h2 class="text-xl font-bold text-gray-900">Penerapan Template ke Kelas</h2>
                                    <p class="mt-1 text-slate-600">Pilih template, lalu pilih kelas mana saja yang akan menggunakan paket mata pelajaran dari template tersebut.</p>
                                </div>
                                <div class="p-6 border-t border-slate-200 space-y-6">
                                    <div>
                                        <label for="template_id" class="block text-sm font-medium text-gray-700">Pilih Template Kurikulum</label>
                                        <select name="template_id" id="template_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                            <option value="">-- Pilih Template --</option>
                                            @foreach($templates as $template)
                                                <option value="{{ $template->id }}">{{ $template->nama_template }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Pilih Kelas Tujuan</label>
                                        <div class="mt-2 h-64 border border-gray-300 rounded-md overflow-y-auto p-4 space-y-2">
                                            @foreach($kelasList as $kelas)
                                                <div class="flex items-center">
                                                    <input id="kelas_{{ $kelas->id }}" name="kelas_ids[]" type="checkbox" value="{{ $kelas->id }}" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                    <label for="kelas_{{ $kelas->id }}" class="ml-3 block text-sm text-gray-900">{{ $kelas->nama_kelas }} <span class="text-slate-500">({{ $kelas->kurikulumTemplate->nama_template ?? 'Belum ada template' }})</span></label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">Terapkan ke Kelas</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
