<x-app-layout title="Articles">

    <x-slot:header>
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800">Articles</h1>
            <a href="{{ route('posts.create') }}" class="text-sm text-indigo-600 hover:underline">+ Nouvel article</a>
        </div>
    </x-slot:header>

    @if(session('success'))
        <x-alert type="success" :dismissible="true" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif

    @forelse($posts as $post)
        <x-card class="mb-4">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">{{ $post->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Par <span class="font-medium">{{ $post->user->name }}</span>
                        <x-user-role-badge :user="$post->user" />
                    </p>
                    <p class="text-gray-600 mt-3 text-sm">{{ Str::limit($post->body, 120) }}</p>
                </div>

                <div class="flex gap-2 ml-4 shrink-0">
                    @can('update', $post)
                        <x-button variant="primary" :href="route('posts.edit', $post)">Modifier</x-button>
                    @endcan

                    @can('delete', $post)
                        <form method="POST" action="{{ route('posts.destroy', $post) }}">
                            @csrf
                            @method('DELETE')
                            <x-button variant="danger" type="submit">Supprimer</x-button>
                        </form>
                    @endcan
                </div>
            </div>
        </x-card>
    @empty
        <x-alert type="warning">Aucun article pour le moment.</x-alert>
    @endforelse

</x-app-layout>
