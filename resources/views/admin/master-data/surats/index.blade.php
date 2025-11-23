<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Master Data Surat</h1>
                            <p class="mt-1 text-sm sm:text-base text-slate-600">Kelola daftar surat dan jumlah ayat untuk Al-Qur'an.</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <a href="{{ route('admin.master-data.surats.create') }}" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Surat Baru
                            </a>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif
                @if (session('error'))
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
                @endif

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">No. Surat</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Nama Surat</th>
                                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase">Jumlah Ayat</th>
                                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($surats as $surat)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $surat->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $surat->nama_surat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 text-center">{{ $surat->jumlah_ayat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center space-x-4">
                                        <a href="{{ route('admin.master-data.surats.edit', $surat) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>

                                        <form action="{{ route('admin.master-data.surats.destroy', $surat) }}" method="POST" class="inline" onsubmit="return confirm('Peringatan: Menghapus surat yang sudah dipakai di data setoran akan gagal. Yakin ingin menghapus surat ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-slate-500">
                                        Belum ada data surat. Silakan tambahkan data baru.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($surats->hasPages())
                    <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                        {{ $surats->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
