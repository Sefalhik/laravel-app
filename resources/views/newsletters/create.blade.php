<x-app-layout :title="__('New newsletter')">

    <x-slot:header>
        <h1 class="text-xl font-semibold text-gray-800">{{ __('New newsletter') }}</h1>
    </x-slot:header>

    <x-card>
        <form method="POST" action="{{ route('newsletters.store') }}">
            @csrf

            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Subject') }}</label>
                <input
                    type="text"
                    id="subject"
                    name="subject"
                    value="{{ old('subject') }}"
                    required
                    autofocus
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('subject') border-red-500 @enderror"
                >
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="body" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Content') }}</label>
                <textarea
                    id="body"
                    name="body"
                    rows="8"
                    required
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('body') border-red-500 @enderror"
                >{{ old('body') }}</textarea>
                @error('body')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4">
                <x-button type="submit" variant="primary">{{ __('Send') }}</x-button>
                <a href="{{ route('newsletters.index') }}" class="text-sm text-indigo-600 hover:underline">{{ __('Cancel') }}</a>
            </div>

        </form>
    </x-card>

</x-app-layout>
