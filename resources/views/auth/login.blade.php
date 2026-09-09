<x-layouts.guest>
    <x-slot:title>Login Akun</x-slot:title>

    <div class="rounded-lg border border-[#232736] bg-[#12141C]/90 p-6 sm:p-8 backdrop-blur-sm shadow-2xl">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl font-bold tracking-tight text-zinc-100 font-sans">Masuk ke Workspace</h1>
            <p class="text-xs text-zinc-400 mt-1">Akses sistem manajemen tugas dan kolaborasi JARA.</p>
        </div>

        <!-- Form Login -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{
            setEmail(val) {
                $refs.emailInput.value = val;
                $refs.passInput.value = 'password';
            }
        }">
            @csrf

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-xs font-medium text-zinc-300 font-mono mb-1.5">
                    EMAIL PENGGUNA
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       x-ref="emailInput"
                       value="{{ old('email') }}" 
                       required 
                       autofocus
                       placeholder="nama@domain.com"
                       class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 placeholder-zinc-600 text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 font-mono transition" />
                @error('email')
                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-medium text-zinc-300 font-mono">
                        PASSWORD
                    </label>
                </div>
                <input type="password" 
                       id="password" 
                       name="password" 
                       x-ref="passInput"
                       required
                       placeholder="••••••••"
                       class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 placeholder-zinc-600 text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 font-mono transition" />
                @error('password')
                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-[#2B3042] bg-[#0C0D12] text-violet-600 focus:ring-0 focus:ring-offset-0">
                    <span class="text-xs text-zinc-400">Ingat sesi saya</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full py-2.5 px-4 rounded-md bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-medium text-sm tracking-wide transition shadow-[0_0_20px_rgba(144,59,238,0.25)] border border-violet-400/30 flex items-center justify-center gap-2">
                <span>Masuk Sekarang</span>
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>

            <!-- Quick Demo Accounts Switcher -->
            <div class="pt-4 border-t border-[#232736]/70">
                <div class="text-[10px] uppercase font-mono text-zinc-500 tracking-wider mb-2">
                    Akun Cepat Pengujian (Default Seeder):
                </div>
                <div class="grid grid-cols-3 gap-2 text-left">
                    <button type="button" @click="setEmail('test@example.com')" 
                            class="p-2 rounded border border-[#2E3347] bg-[#161823] hover:border-violet-500/60 hover:bg-[#1C2030] transition flex flex-col">
                        <span class="text-[11px] font-semibold text-zinc-200">Owner</span>
                        <span class="text-[9px] text-zinc-500 font-mono truncate">test@example</span>
                    </button>
                    <button type="button" @click="setEmail('admin@jara.test')" 
                            class="p-2 rounded border border-[#2E3347] bg-[#161823] hover:border-amber-500/60 hover:bg-[#1C2030] transition flex flex-col">
                        <span class="text-[11px] font-semibold text-amber-400">Admin</span>
                        <span class="text-[9px] text-zinc-500 font-mono truncate">admin@jara</span>
                    </button>
                    <button type="button" @click="setEmail('moses@jara.test')" 
                            class="p-2 rounded border border-[#2E3347] bg-[#161823] hover:border-indigo-500/60 hover:bg-[#1C2030] transition flex flex-col">
                        <span class="text-[11px] font-semibold text-indigo-400">Member</span>
                        <span class="text-[9px] text-zinc-500 font-mono truncate">moses@jara</span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Footer link -->
        <div class="mt-6 text-center text-xs text-zinc-400">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="text-violet-400 hover:text-violet-300 font-medium underline underline-offset-4 ml-1">
                Daftar akun baru
            </a>
        </div>
    </div>
</x-layouts.guest>
