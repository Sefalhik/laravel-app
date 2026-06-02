<x-guest-layout :title="__('Register')">

    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">{{ __('Register') }}</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }}</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
            >
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email address') }}</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror"
            >
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm password') }}</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
        </div>

        <x-button type="submit" variant="primary" class="w-full justify-center">
            {{ __('Sign up') }}
        </x-button>

    </form>

</x-guest-layout>
