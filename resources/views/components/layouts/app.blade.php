<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Workspace' }} — JARA</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-jara-pattern min-h-screen text-zinc-100 flex flex-col antialiased selection:bg-violet-600 selection:text-white">
    <!-- Top Navigation Header -->
    <header class="border-b border-[#232736] bg-[#0C0D10]/90 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <!-- Brand + Main Links -->
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="hover:opacity-90 transition shrink-0">
                    <x-logo size="sm" />
                </a>

                <nav class="hidden md:flex items-center gap-1 font-mono text-xs">
                    <a href="{{ route('dashboard') }}" 
                       class="px-3 py-1.5 rounded-md transition {{ request()->routeIs('dashboard') ? 'bg-[#1E2230] text-zinc-100 border border-[#2E3347]' : 'text-zinc-400 hover:text-zinc-200 hover:bg-[#14161F]' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('profile.index') }}" 
                       class="px-3 py-1.5 rounded-md transition {{ request()->routeIs('profile.*') ? 'bg-[#1E2230] text-zinc-100 border border-[#2E3347]' : 'text-zinc-400 hover:text-zinc-200 hover:bg-[#14161F]' }}">
                        Profile & Akun
                    </a>
                </nav>
            </div>

            <!-- Header Right: User Info & Actions -->
            <div class="flex items-center gap-4">
                <!-- User Profile Pill -->
                <div class="flex items-center gap-3 pl-3 pr-2 py-1 rounded-md border border-[#232736] bg-[#12141C]">
                    <div class="w-6 h-6 rounded bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-[10px] font-bold text-white font-mono uppercase">
                        {{ substr(auth()->user()->name ?? 'U', 0, 2) }}
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="text-xs font-medium text-zinc-200 leading-none truncate max-w-[130px]">
                            {{ auth()->user()->name }}
                        </span>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[9px] font-mono uppercase px-1 py-0.2 rounded border {{ auth()->user()->role === 'admin' ? 'border-amber-500/40 text-amber-400 bg-amber-500/10' : (auth()->user()->role === 'owner' ? 'border-violet-500/40 text-violet-400 bg-violet-500/10' : 'border-zinc-700 text-zinc-400 bg-zinc-800/40') }}">
                                {{ auth()->user()->role ?? 'user' }}
                            </span>
                        </div>
                    </div>

                    <!-- Logout Form -->
                    <form method="POST" action="{{ route('logout') }}" class="ml-2 pl-2 border-l border-zinc-800">
                        @csrf
                        <button type="submit" 
                                title="Keluar dari sistem" 
                                class="text-zinc-500 hover:text-rose-400 transition p-1 rounded hover:bg-rose-950/30">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Notification Toast / Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-4">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="mb-4 px-4 py-3 rounded-md bg-emerald-950/40 border border-emerald-800/60 text-emerald-300 text-xs flex items-center justify-between transition">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-300">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" 
                 class="mb-4 px-4 py-3 rounded-md bg-rose-950/40 border border-rose-800/60 text-rose-300 text-xs flex items-center justify-between transition">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-500 hover:text-rose-300">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded-md bg-rose-950/40 border border-rose-800/60 text-rose-300 text-xs">
                <p class="font-semibold mb-1">Terdapat kesalahan pada isian form:</p>
                <ul class="list-disc list-inside space-y-0.5 text-zinc-400">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-6">
        {{ $slot }}
    </main>

    <!-- Clean Footer -->
    <footer class="border-t border-[#232736] py-6 text-center text-xs text-zinc-600 font-mono">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>JARA &bull; Distributed Task Architecture</span>
            <span>PPK Kelompok D8 &bull; Moses &bull; Dewa &bull; Sulthon</span>
        </div>
    </footer>
</body>
</html>
