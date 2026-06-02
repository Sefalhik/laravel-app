@props(['header' => null])

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --color-bg:       #f9fafb;
            --color-surface:  #ffffff;
            --color-border:   #e5e7eb;
            --color-text:     #111827;
            --color-muted:    #6b7280;
        }
        [data-theme="dark"] {
            --color-bg:       #111827;
            --color-surface:  #1f2937;
            --color-border:   #374151;
            --color-text:     #f9fafb;
            --color-muted:    #9ca3af;
        }

        body        { background: var(--color-bg);      color: var(--color-text); }
        nav, header { background: var(--color-surface);  border-color: var(--color-border); }
        .theme-card { background: var(--color-surface);  border-color: var(--color-border); }
        .theme-muted { color: var(--color-muted); }

        input, textarea, select {
            background: var(--color-surface);
            color:       var(--color-text);
            border-color: var(--color-border);
        }
        input::placeholder, textarea::placeholder { color: var(--color-muted); }

        {{-- x-cloak masque les éléments Alpine avant initialisation pour éviter le flash d'affichage --}}
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex min-h-screen flex-col">

    <nav class="border-b px-6 py-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between">

            <div class="flex items-center gap-6">
                <a href="/" class="font-bold text-indigo-600">{{ config('app.name') }}</a>
                <a href="{{ route('stats.index') }}" class="text-sm theme-muted hover:text-indigo-600">{{ __('Stats') }}</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm theme-muted hover:text-indigo-600">{{ __('Dashboard') }}</a>
                    <a href="{{ route('posts.index') }}" class="text-sm theme-muted hover:text-indigo-600">{{ __('Posts') }}</a>
                    <a href="{{ route('preferences.index') }}" class="text-sm theme-muted hover:text-indigo-600">{{ __('Preferences') }}</a>
                @endauth
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <span class="text-sm theme-muted">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm theme-muted hover:text-indigo-600">{{ __('Logout') }}</button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="text-sm theme-muted hover:text-indigo-600">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="text-sm theme-muted hover:text-indigo-600">{{ __('Register') }}</a>
                @endguest
            </div>

        </div>
    </nav>

    @if($header)
        <header class="border-b">
            <div class="max-w-5xl mx-auto px-4 py-4">
                {{ $header }}
            </div>
        </header>
    @endif

    <main class="flex-1 py-8 px-4 max-w-5xl mx-auto w-full">{{ $slot }}</main>

</body>
</html>
