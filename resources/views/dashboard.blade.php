<x-app-layout title="Tableau de bord">

    <x-slot:header>
        <h1 class="text-xl font-semibold text-gray-800">Tableau de bord</h1>
    </x-slot:header>

    <x-card>
        <p class="text-gray-700 mb-4">
            Bienvenue <span class="font-semibold">{{ auth()->user()->name }}</span>
            <x-user-role-badge />
        </p>

        <a href="{{ route('posts.index') }}" class="text-indigo-600 hover:underline text-sm">
            Gérer les articles
        </a>
    </x-card>

</x-app-layout>
