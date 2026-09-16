<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Autentikasi' }} — JARA</title>

    <!-- Theme check before render to prevent flicker -->
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
        } else {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-jara-pattern min-h-screen text-zinc-100 flex flex-col justify-between antialiased selection:bg-violet-600 selection:text-white">
    <!-- Top Bar with Logo & Theme Switcher -->
    <header class="pt-8 px-6 sm:px-12 flex items-center justify-between">
        <a href="{{ route('login') }}" class="hover:opacity-90 transition">
            <x-logo size="md" />
        </a>
        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-2 text-xs font-mono text-zinc-500">
                <span>D8 PPK</span>
                <span class="w-1 h-1 rounded-full bg-zinc-700"></span>
                <span class="text-violet-400">Task Intelligence</span>
            </div>
            <x-theme-toggle />
        </div>
    </header>

    <!-- Content Slot -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 my-8">
        <div class="w-full max-w-md">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 rounded-md bg-emerald-950/40 border border-emerald-800/60 text-emerald-300 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 px-4 py-3 rounded-md bg-rose-950/40 border border-rose-800/60 text-rose-300 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="pb-8 px-6 text-center text-xs text-zinc-600 font-mono">
        &copy; {{ date('Y') }} JARA Task Intelligence &bull; Kelompok D8 Praktikum Pemrograman Komputer
    </footer>
</body>
</html>
