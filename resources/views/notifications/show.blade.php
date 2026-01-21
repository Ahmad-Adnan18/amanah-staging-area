<x-app-layout>
    <div class="bg-white min-h-screen">
        {{-- Custom Navigation Header for Detail View --}}
        <div class="sticky top-0 z-20 bg-white/90 backdrop-blur-md border-b border-gray-100 px-4 py-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto flex items-center gap-3">
                <a href="{{ route('notifications.index') }}" class="p-2 -ml-2 text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-lg font-bold text-gray-900 truncate">Detail Notifikasi</h1>
            </div>
        </div>

        @php
            $typeConfig = match($notification->type) {
                'warning' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-100', 'label' => 'Peringatan'],
                'success' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-100', 'label' => 'Sukses'],
                'urgent' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-100', 'label' => 'Penting'],
                default => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-100', 'label' => 'Informasi']
            };
        @endphp

        <div class="max-w-3xl mx-auto">
            {{-- Hero Section / Header Content --}}
            <div class="px-5 py-6 sm:px-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $typeConfig['bg'] }} {{ $typeConfig['text'] }} border {{ $typeConfig['border'] }}">
                        {{ $typeConfig['label'] }}
                    </span>
                    <span class="text-sm text-gray-400">
                        {{ $notification->published_at->format('d M Y, H:i') }}
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-tight mb-2">
                    {{ $notification->title }}
                </h1>
                
                <div class="flex items-center gap-2 mt-4 text-sm text-gray-500 border-b border-gray-100 pb-6">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span>Dari: <span class="font-medium text-gray-900">{{ $notification->creator->name ?? 'System' }}</span></span>
                </div>
            </div>

            {{-- Body Content --}}
            <div class="px-5 sm:px-8 pb-12">
                <div class="prose prose-lg prose-slate max-w-none prose-headings:font-bold prose-p:text-gray-600 prose-p:leading-relaxed">
                    {!! nl2br(e($notification->message)) !!}
                </div>

                @if($notification->expires_at)
                <div class="mt-8 p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Masa Berlaku</p>
                        <p class="text-sm text-gray-500">Notifikasi ini berlaku hingga {{ $notification->expires_at->format('d F Y') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
