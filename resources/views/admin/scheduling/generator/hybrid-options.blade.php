<div class="bg-white rounded-2xl shadow-lg border border-slate-200 mt-6">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-gray-900">Opsi Generator Hybrid</h3>
        <p class="mt-1 text-slate-600">Pilih strategi untuk penjadwalan tambahan.</p>
    </div>
    <div class="p-6">
        <form action="{{ route('admin.scheduling.generator.hybrid') }}" method="POST" id="hybridForm">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="flex items-center">
                        <input type="radio" name="clear_existing" value="0" checked class="mr-3">
                        <span class="text-sm font-medium text-gray-900">Tambahkan ke jadwal existing</span>
                    </label>
                    <p class="text-sm text-gray-500 ml-6">Jadwal baru akan ditambahkan tanpa menghapus yang sudah ada</p>
                </div>
                
                <div>
                    <label class="flex items-center">
                        <input type="radio" name="clear_existing" value="1" class="mr-3">
                        <span class="text-sm font-medium text-gray-900">Hapus semua jadwal dan buat baru</span>
                    </label>
                    <p class="text-sm text-gray-500 ml-6">Semua jadwal akan dihapus dan dibuat dari awal</p>
                </div>
                
                <div class="pt-4 border-t border-slate-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Strategi Penempatan:</label>
                    <select name="strategy" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                        <option value="incremental">Incremental - Isi slot kosong saja</option>
                        <option value="fill_gaps">Fill Gaps - Utamakan mengisi kekosongan</option>
                        <option value="replace_conflicts">Replace Conflicts - Ganti jadwal yang bermasalah</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                <button type="submit" 
                        class="inline-flex items-center justify-center rounded-lg bg-green-600 px-8 py-3 text-base font-semibold text-white shadow-sm hover:bg-green-500 transition-all duration-200">
                    Jalankan Generator Hybrid
                </button>
            </div>
        </form>
    </div>
</div>