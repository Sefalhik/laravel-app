<div x-data="{ open: true }" x-show="open" {{ $attributes->merge(['class' => "border-l-4 p-4 rounded flex items-start justify-between $classes"]) }}>
    <span>{{ $slot }}</span>

    @if($dismissible)
        <button @click="open = false" class="ml-4 text-current opacity-60 hover:opacity-100 leading-none">&times;</button>
    @endif
</div>
