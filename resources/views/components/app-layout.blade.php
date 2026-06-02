@props(['header' => null])

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- x-cloak masque les éléments Alpine avant initialisation pour éviter le flash d'affichage --}}
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="flex min-h-screen flex-col bg-gray-50 text-gray-900">

    <nav class="border-b bg-white px-6 py-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between">

            <div class="flex items-center gap-6">
                <a href="/" class="font-bold text-indigo-600">{{ config('app.name') }}</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('posts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Posts</a>
                @endauth
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">Déconnexion</button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Connexion</a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900">Inscription</a>
                @endguest
            </div>

        </div>
    </nav>

    @if($header)
        <header class="bg-white border-b">
            <div class="max-w-5xl mx-auto px-4 py-4">
                {{ $header }}
            </div>
        </header>
    @endif

    <main class="flex-1 py-8 px-4 max-w-5xl mx-auto w-full">{{ $slot }}</main>

</body>
</html>
