<x-app-layout title="Nouvel article">

    <x-slot:header>
        <h1 class="text-xl font-semibold text-gray-800">Nouvel article</h1>
    </x-slot:header>

    <x-card>
        <form method="POST" action="{{ route('posts.store') }}">
            @csrf

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    required
                    autofocus
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title') border-red-500 @enderror"
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Contenu</label>
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

            <div class="flex items-center gap-3">
                <x-button type="submit" variant="primary">Publier</x-button>
                <a href="{{ route('posts.index') }}" class="text-sm text-gray-500 hover:underline">Annuler</a>
            </div>

        </form>
    </x-card>

</x-app-layout>
