<div class="border border-slate-200 rounded-lg p-4 bg-white shadow-sm hover:shadow-md transition-all duration-200">
    {{-- Header: Nama Ruangan, Tipe, dan Ikon --}}
    <div class="flex items-start justify-between mb-3 gap-3">
        <div class="flex-1 min-w-0">
            {{-- Nama Ruangan --}}
            <h3 class="text-base font-semibold text-gray-900 mb-1 leading-tight truncate">
                {{ $room->name }}
            </h3>
            
            {{-- Badge Tipe Ruangan --}}
            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium leading-5 
                         {{ $room->type == 'Khusus' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}
                         {{ $room->type == 'Khusus' ? 'border border-blue-200' : 'border border-gray-200' }}">
                {{ $room->type }}
            </span>
        </div>
        
        {{-- Ikon Ruangan --}}
        <div class="flex-shrink-0">
            <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
    </div>
    
    {{-- Statistik Meja dan Kursi --}}
    <div class="grid grid-cols-2 gap-4 mb-4">
        {{-- Card Meja --}}
        <div class="text-center p-3 bg-slate-50 rounded-md border border-slate-200 hover:bg-slate-100 transition-colors duration-150">
            <div class="flex items-center justify-center mb-2 text-sm">
                <svg class="w-4 h-4 text-slate-500 mr-1.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="text-slate-700 font-medium">Meja</span>
            </div>
            <div class="text-xl font-bold text-slate-900">{{ number_format($room->jumlah_meja) }}</div>
        </div>
        
        {{-- Card Kursi --}}
        <div class="text-center p-3 bg-slate-50 rounded-md border border-slate-200 hover:bg-slate-100 transition-colors duration-150">
            <div class="flex items-center justify-center mb-2 text-sm">
                <svg class="w-4 h-4 text-slate-500 mr-1.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M17.657 18.657A8 8 0 016.343 7.343S7 9 7 9a8 8 0 0112 12c0 0-1-1-1.343-1.343zM17 17a2 2 0 11-4 0 2 2 0 014 0zm-4 0a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-slate-700 font-medium">Kursi</span>
            </div>
            <div class="text-xl font-bold text-slate-900">{{ number_format($room->jumlah_kursi) }}</div>
        </div>
    </div>
    
    {{-- Tombol Aksi --}}
    <div class="flex flex-col sm:flex-row gap-2 pt-3 border-t border-slate-100">
        {{-- Tombol Edit --}}
        <a href="{{ route('admin.master-data.rooms.edit', $room) }}" 
           class="inline-flex items-center justify-center flex-1 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-md shadow-sm 
                  hover:bg-slate-50 hover:border-slate-400 hover:text-slate-800 
                  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 
                  transition-all duration-200 text-center">
            <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit
        </a>
        
        {{-- Form Hapus --}}
        <form action="{{ route('admin.master-data.rooms.destroy', $room) }}" 
              method="POST" 
              class="inline w-full sm:w-auto"
              onsubmit="return confirm('Yakin ingin menghapus ruangan \"{{ $room->name }}\"?\\n\\nPenghapusan ini tidak dapat dibatalkan.');">
            @csrf @method('DELETE')
            <button type="submit" 
                    class="inline-flex items-center justify-center flex-1 px-4 py-2 text-sm font-medium text-red-700 bg-white border border-red-300 rounded-md shadow-sm 
                           hover:bg-red-50 hover:border-red-400 hover:text-red-800 
                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 
                           transition-all duration-200 w-full sm:w-auto">
                <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus
            </button>
        </form>
    </div>
</div>