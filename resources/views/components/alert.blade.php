@props(['type' => 'info', 'message', 'dismissible' => true])

@php
    $types = [
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-200',
            'text' => 'text-green-800',
            'icon' => '
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>',
            'title' => 'Berhasil'
        ],
        'error' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-200',
            'text' => 'text-red-800',
            'icon' => '
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>',
            'title' => 'Error'
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-200',
            'text' => 'text-yellow-800',
            'icon' => '
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>',
            'title' => 'Peringatan'
        ],
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-200',
            'text' => 'text-blue-800',
            'icon' => '
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>',
            'title' => 'Info'
        ]
    ];
    
    $currentType = $types[$type] ?? $types['info'];
@endphp

<div {{ $attributes->merge(['class' => "alert {$currentType['bg']} {$currentType['border']} {$currentType['text']} px-4 py-3 rounded-xl shadow-sm border flex items-start transition-all duration-300"]) }}
     x-data="{ show: true }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-1"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95">
    
    <div class="flex-shrink-0 mr-3">
        {!! $currentType['icon'] !!}
    </div>
    
    <div class="flex-1 min-w-0">
        <h3 class="text-sm font-semibold {{ $currentType['text'] }}">{{ $currentType['title'] }}</h3>
        <div class="mt-1 text-sm {{ $currentType['text'] }}">
            @if ($message)
                {{ $message }}
            @else
                {{ $slot }}
            @endif
        </div>
    </div>
    
    @if($dismissible)
        <div class="ml-4 flex-shrink-0 flex">
            <button @click="show = false" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition-colors duration-200">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif
</div>