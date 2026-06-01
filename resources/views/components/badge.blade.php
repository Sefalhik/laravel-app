@props(['color' => 'green'])

@php
    $colors = [
        'green' => 'bg-green-100 text-green-800',
        'red'   => 'bg-red-100 text-red-800',
        'blue'  => 'bg-blue-100 text-blue-800',
    ];

    $classes = $colors[$color] ?? $colors['green'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $classes"]) }}>
    {{ $slot }}
</span>
