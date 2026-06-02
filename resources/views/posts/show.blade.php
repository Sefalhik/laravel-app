<x-app-layout :title="$post->title">

    <x-slot:header>
        <h1 class="text-xl font-semibold text-gray-800">{{ $post->title }}</h1>
    </x-slot:header>

    <x-card>
        <p class="text-sm text-gray-500 mb-6">
            {{ __('By') }} <span class="font-medium">{{ $post->user->name }}</span>
        </p>

        <div class="text-gray-700 whitespace-pre-wrap">{{ $post->body }}</div>

        <div class="mt-6 pt-4 border-t">
            <a href="{{ route('posts.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; {{ __('Back to articles') }}</a>
        </div>
    </x-card>

</x-app-layout>
