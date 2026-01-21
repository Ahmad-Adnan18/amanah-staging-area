<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8 pb-24 sm:pb-8">
            {{-- Header --}}
            <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                        Kelola Notifikasi
                    </h1>
                    <p class="mt-2 text-slate-600 text-sm sm:text-base">Buat dan kelola notifikasi untuk pengguna</p>
                </div>
                <a href="{{ route('admin.notifications.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Notifikasi
                </a>
            </div>

            {{-- Flash Message --}}
            @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
                {{ session('success') }}
            </div>
            @endif

            {{-- Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Judul</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider hidden sm:table-cell">Tipe</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider hidden md:table-cell">Target</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider hidden lg:table-cell">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($notifications as $notification)
                            @php
                                $typeColors = [
                                    'info' => 'blue',
                                    'warning' => 'yellow',
                                    'success' => 'green',
                                    'urgent' => 'red',
                                ];
                                $color = $typeColors[$notification->type] ?? 'gray';
                                $typeLabels = [
                                    'info' => 'Informasi',
                                    'warning' => 'Peringatan',
                                    'success' => 'Sukses',
                                    'urgent' => 'Mendesak',
                                ];
                                $isPublished = $notification->published_at && $notification->published_at <= now();
                                $isExpired = $notification->expires_at && $notification->expires_at < now();
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-900">{{ Str::limit($notification->title, 40) }}</div>
                                    <div class="text-sm text-slate-500 mt-1">{{ $notification->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="px-4 py-4 hidden sm:table-cell">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
                                        {{ $typeLabels[$notification->type] ?? $notification->type }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 hidden md:table-cell">
                                    @if($notification->target_roles)
                                        <span class="text-sm text-slate-600">{{ count($notification->target_roles) }} role</span>
                                    @else
                                        <span class="text-sm text-slate-400">Semua</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 hidden lg:table-cell">
                                    @if($isExpired)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Expired</span>
                                    @elseif($isPublished)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Draft</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.notifications.edit', $notification) }}" class="p-2 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center">
                                    <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada notifikasi</h3>
                                    <p class="text-sm text-slate-500">Klik tombol "Buat Notifikasi" untuk membuat notifikasi pertama.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($notifications->hasPages())
                <div class="px-4 py-3 border-t border-slate-200">
                    {{ $notifications->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
