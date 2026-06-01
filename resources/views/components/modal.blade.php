<div x-data="{ open: false }" {{ $attributes->merge(['class' => '']) }}>

    {{-- Trigger --}}
    <div @click="open = true">
        {{ $trigger }}
    </div>

    {{-- Overlay --}}
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">

        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

        <div class="relative z-10 bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">

            {{-- Header --}}
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-800">{{ $title }}</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600 leading-none text-xl">&times;</button>
            </div>

            {{-- Content --}}
            <div class="px-6 py-4">
                {{ $content }}
            </div>

        </div>
    </div>

</div>
