{{-- resources/views/components/menu-akses-cepat.blade.php --}}
<div>
    <h2 class="text-2xl font-bold text-slate-800 mb-6">Menu Akses Cepat</h2>
    <div class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 gap-4">
        @foreach ($menuItems as $item)
        @if (is_null($item['roles']) || in_array(Auth::user()->role, $item['roles']))
        <a href="{{ route($item['route']) }}" class="menu-item group" data-color="{{ $item['color'] }}">
            <div class="icon-container bg-{{ $item['color'] }}-100 text-{{ $item['color'] }}-600">
                <svg class="w-6 h-6 transition-transform duration-300 ease-out" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"></path>
                </svg>
            </div>
            <p class="menu-label">{{ $item['label'] }}</p>
            <span class="ripple-effect absolute inset-0 rounded-xl pointer-events-none"></span>
        </a>
        @endif
        @endforeach
    </div>
</div>
