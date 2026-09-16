<x-layouts.guest>
    <x-slot:title>Registrasi Akun Baru</x-slot:title>

    <div class="rounded-lg border border-[#232736] bg-[#12141C]/90 p-6 sm:p-8 backdrop-blur-sm shadow-2xl">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl font-bold tracking-tight text-zinc-100 font-sans">Buat Akun Baru</h1>
            <p class="text-xs text-zinc-400 mt-1">Daftarkan diri untuk mulai mengelola task dan tim kolaborasi.</p>
        </div>

        <!-- Form Register -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Full Name -->
            <div>
                <label for="name" class="block text-xs font-medium text-zinc-300 font-mono mb-1.5">
                    NAMA LENGKAP
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus
                       placeholder="Misal: Budi Santoso"
                       class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 placeholder-zinc-600 text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 font-mono transition" />
                @error('name')
                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-medium text-zinc-300 font-mono mb-1.5">
                    ALAMAT EMAIL
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       placeholder="nama@domain.com"
                       class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 placeholder-zinc-600 text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 font-mono transition" />
                @error('email')
                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Selector -->
            <div>
                <label class="block text-xs font-medium text-zinc-300 font-mono mb-1.5">
                    TIPE PERAN AWAL
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 p-2.5 rounded-md border border-[#2B3042] bg-[#0C0D12] cursor-pointer hover:border-violet-500/50 transition">
                        <input type="radio" name="role" value="owner" checked class="text-violet-600 focus:ring-0">
                        <div class="flex flex-col text-left">
                            <span class="text-xs font-semibold text-zinc-200">Owner</span>
                            <span class="text-[10px] text-zinc-500">Pemilik & Pengelola Daftar</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-2 p-2.5 rounded-md border border-[#2B3042] bg-[#0C0D12] cursor-pointer hover:border-violet-500/50 transition">
                        <input type="radio" name="role" value="member" class="text-violet-600 focus:ring-0">
                        <div class="flex flex-col text-left">
                            <span class="text-xs font-semibold text-zinc-200">Member</span>
                            <span class="text-[10px] text-zinc-500">Anggota Kolaborator</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-medium text-zinc-300 font-mono mb-1.5">
                    PASSWORD (MIN. 6 KARAKTER)
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       placeholder="••••••••"
                       class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 placeholder-zinc-600 text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 font-mono transition" />
                @error('password')
                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-xs font-medium text-zinc-300 font-mono mb-1.5">
                    KONFIRMASI PASSWORD
                </label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required
                       placeholder="••••••••"
                       class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 placeholder-zinc-600 text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 font-mono transition" />
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full mt-2 py-2.5 px-4 rounded-md bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-medium text-sm tracking-wide transition shadow-[0_0_20px_rgba(144,59,238,0.25)] border border-violet-400/30 flex items-center justify-center gap-2">
                <span>Daftarkan Akun</span>
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </form>

        <!-- Footer link -->
        <div class="mt-6 text-center text-xs text-zinc-400">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="text-violet-400 hover:text-violet-300 font-medium underline underline-offset-4 ml-1">
                Masuk ke akun
            </a>
        </div>
    </div>
</x-layouts.guest>
