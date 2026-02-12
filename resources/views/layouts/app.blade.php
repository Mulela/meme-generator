<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meme Generator</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-950 text-zinc-100">
    <header class="border-b border-zinc-800 bg-zinc-950/80 backdrop-blur">
        <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between">
            <a href="{{ route('editor') }}" class="font-semibold tracking-tight">
                Meme Generator
            </a>

            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('editor') }}" class="px-3 py-2 rounded-lg hover:bg-zinc-900 {{ request()->routeIs('editor') ? 'bg-zinc-900' : '' }}">
                    Editor
                </a>
                <a href="{{ route('gallery') }}" class="px-3 py-2 rounded-lg hover:bg-zinc-900 {{ request()->routeIs('gallery') ? 'bg-zinc-900' : '' }}">
                    Gallery
                </a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8">
        @yield('content')
    </main>

    <footer class="mx-auto max-w-6xl px-4 pb-10 text-xs text-zinc-500">
        Laravel {{ app()->version() }} · Tailwind 4.1.18 · SQLite (dev)
    </footer>
</body>
</html>
