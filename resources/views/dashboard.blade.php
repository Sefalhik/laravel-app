<x-app-layout :title="__('Dashboard')">

    <x-slot:header>
        <h1 class="text-xl font-semibold text-gray-800">{{ __('Dashboard') }}</h1>
    </x-slot:header>

    @if(session('success'))
        <x-alert type="success" :dismissible="true" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif

    <x-card>
        <p class="text-gray-700 mb-4">
            {{ __('Welcome') }}, <span class="font-semibold">{{ auth()->user()->name }}</span>
            <x-user-role-badge />
        </p>

        <a href="{{ route('posts.index') }}" class="text-indigo-600 hover:underline text-sm">
            {{ __('Manage articles') }}
        </a>
    </x-card>

</x-app-layout>
