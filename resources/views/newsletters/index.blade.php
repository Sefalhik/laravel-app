<x-app-layout :title="__('Newsletters')">

    <x-slot:header>
        <h1 class="text-xl font-semibold text-gray-800">{{ __('Newsletters') }}</h1>
    </x-slot:header>

    @if(session('success'))
        <x-alert type="success" :dismissible="true" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif

    <div class="mb-4 flex justify-end">
        @role('admin')
            <a href="{{ route('newsletters.create') }}">
                <x-button variant="primary">{{ __('New newsletter') }}</x-button>
            </a>
        @endrole
    </div>

    <x-card>
        @forelse($newsletters as $newsletter)
            <div class="flex items-center justify-between py-3 @unless($loop->last) border-b @endunless">
                <div>
                    <p class="font-medium">{{ $newsletter->subject }}</p>
                    <p class="text-sm theme-muted">
                        {{ $newsletter->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                <span @class([
                    'text-xs font-semibold px-2 py-1 rounded-full',
                    'bg-green-100 text-green-700' => $newsletter->sent_at !== null,
                    'bg-yellow-100 text-yellow-700' => $newsletter->sent_at === null,
                ])>
                    {{ $newsletter->sent_at ? __('Sent') : __('Pending') }}
                </span>
            </div>
        @empty
            <p class="text-sm theme-muted">{{ __('No newsletters yet.') }}</p>
        @endforelse
    </x-card>

</x-app-layout>
