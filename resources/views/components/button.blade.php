@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "inline-flex items-center px-4 py-2 rounded font-medium transition-colors $classes"]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => "inline-flex items-center px-4 py-2 rounded font-medium transition-colors $classes"]) }}>
        {{ $slot }}
    </button>
@endif
