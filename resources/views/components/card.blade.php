<div {{ $attributes->merge(['class' => 'theme-card rounded-lg shadow p-6']) }}>
    @if($title)
        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $title }}</h3>
    @endif

    {{ $slot }}
</div>
