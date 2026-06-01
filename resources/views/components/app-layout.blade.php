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
    <nav class="border-b bg-white px-6 py-4 flex items-center justify-between">
        <a href="/" class="font-bold text-indigo-600">{{ config('app.name') }}</a>
        @auth
        <form method="POST" action="/logout">@csrf
            <button class="text-sm text-gray-600 hover:text-gray-900">Déconnexion</button>
        </form>
        @endauth
    </nav>
    <main class="flex-1 py-8 px-4 max-w-5xl mx-auto w-full">{{ $slot }}</main>
</body>
</html>
