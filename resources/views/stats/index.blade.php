<x-app-layout :title="__('Statistics')">

    <x-slot:header>
        <h1 class="text-xl font-semibold text-gray-800">{{ __('Statistics') }}</h1>
    </x-slot:header>

    <div class="grid grid-cols-2 gap-4">

        <x-card :title="__('Users')">
            <p class="text-4xl font-bold text-indigo-600">{{ $stats['users'] }}</p>
        </x-card>

        <x-card :title="__('Tasks')">
            <p class="text-4xl font-bold text-indigo-600">{{ $stats['tasks'] }}</p>
        </x-card>

    </div>

    @can('manage cache')
        <div class="mt-6">
            <form method="POST" action="{{ route('cache.flush') }}">
                @csrf
                <x-button variant="danger" type="submit">{{ __('Clear cache') }}</x-button>
            </form>
        </div>
    @endcan

</x-app-layout>
