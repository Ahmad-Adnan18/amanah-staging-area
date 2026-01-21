<x-app-layout>
    <div class="bg-gray-50 min-h-screen pb-20 sm:pb-8">
        {{-- Sticky Header (Mobile Native Feel) --}}
        <div class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-100 px-4 py-4 sm:px-6 lg:px-8 transition-all duration-200">
            <div class="max-w-3xl mx-auto flex items-center justify-between">
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Notifikasi</h1>
                
                @if($notifications->count() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 -mr-2 text-blue-600 hover:bg-blue-50 rounded-full transition-colors" title="Tandai semua dibaca">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7m-14 6l4 4m6-10l-4 4"></path>
                        </svg>
                    </button>
                </form>
                @endif
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            {{-- Flash Message --}}
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 class="mb-4 p-4 bg-gray-900/90 backdrop-blur text-white text-sm font-medium rounded-xl shadow-lg flex items-center justify-between animate-fade-in-down">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="text-gray-400 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            @endif

            <div class="space-y-6">
                @php
                    $groupedNotifications = $notifications->groupBy(function($item) {
                        return $item->published_at->isToday() ? 'Hari Ini' : 
                               ($item->published_at->isYesterday() ? 'Kemarin' : $item->published_at->format('d M Y'));
                    });
                @endphp

                @forelse($groupedNotifications as $date => $items)
                <div class="space-y-3">
                    <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider pl-1">{{ $date }}</h2>
                    
                    @foreach($items as $notification)
                    @php
                        $isRead = in_array($notification->id, $readNotificationIds);
                        $typeConfig = match($notification->type) {
                            'warning' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                            'success' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'urgent' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-600', 'icon' => 'M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            default => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z']
                        };
                    @endphp
                    
                    <a href="{{ route('notifications.show', $notification) }}" 
                       class="group block bg-white rounded-2xl p-4 shadow-sm border border-gray-100 active:scale-[0.98] active:bg-gray-50 transition-all duration-200 relative overflow-hidden">
                        
                        {{-- Unread Indicator Dot --}}
                        @if(!$isRead)
                        <div class="absolute top-4 right-4 w-2.5 h-2.5 bg-blue-500 rounded-full shadow-sm ring-2 ring-white"></div>
                        @endif

                        <div class="flex gap-4">
                            {{-- Icon --}}
                            <div class="flex-shrink-0 w-12 h-12 rounded-2xl {{ $typeConfig['bg'] }} {{ $typeConfig['text'] }} flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $typeConfig['icon'] }}"></path>
                                </svg>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0 py-0.5">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-medium text-gray-400">
                                        {{ $notification->creator->name ?? 'System' }}
                                    </span>
                                    <span class="text-xs text-gray-400 pr-4">
                                        {{ $notification->published_at->format('H:i') }}
                                    </span>
                                </div>
                                <h3 class="text-base font-semibold text-gray-900 leading-snug mb-1 pr-4 line-clamp-1 {{ !$isRead ? 'text-gray-900' : 'text-gray-600' }}">
                                    {{ $notification->title }}
                                </h3>
                                <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                                    {{ $notification->message }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Belum ada notifikasi</h3>
                    <p class="text-gray-500 max-w-xs mx-auto mt-1">Saat ada pengumuman atau info terbaru, akan muncul di sini.</p>
                </div>
                @endforelse

                {{-- Pagination --}}
                @if($notifications->hasPages())
                <div class="pt-6 pb-20">
                    {{ $notifications->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
