<x-app-layout :title="__('Preferences')">

    <x-slot:header>
        <h1 class="text-xl font-semibold text-gray-800">{{ __('Preferences') }}</h1>
    </x-slot:header>

    @if(session('success'))
        <x-alert type="success" :dismissible="true" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif

    <x-card>
        <form method="POST" action="{{ route('preferences.store') }}">
            @csrf

            <div class="mb-4">
                <label for="theme" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Theme') }}</label>
                <select
                    id="theme"
                    name="theme"
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="light" @selected($theme === 'light')>{{ __('Light') }}</option>
                    <option value="dark"  @selected($theme === 'dark')>{{ __('Dark') }}</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="locale" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Language') }}</label>
                <select
                    id="locale"
                    name="locale"
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="fr" @selected($locale === 'fr')>Français</option>
                    <option value="en" @selected($locale === 'en')>English</option>
                </select>
            </div>

            <x-button type="submit" variant="primary">{{ __('Save') }}</x-button>

        </form>
    </x-card>

</x-app-layout>
