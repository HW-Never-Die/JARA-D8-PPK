<x-layouts.app>
    <x-slot:title>Profil Akun</x-slot:title>

    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#232736] pb-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-100">Profil & Pengaturan Akun</h1>
                <p class="text-xs text-zinc-400 mt-1">Kelola data identitas, kredensial akses, dan hak peran JARA.</p>
            </div>
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-[#2E3347] bg-[#141620] text-xs text-zinc-300 hover:text-white hover:bg-[#1C2030] transition self-start font-mono">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Column: User Summary Card -->
            <div class="md:col-span-1 space-y-6">
                <div class="p-6 rounded-lg border border-[#232736] bg-[#12141C] text-center">
                    <div class="w-16 h-16 mx-auto rounded-md bg-gradient-to-tr from-violet-600 via-purple-600 to-indigo-600 flex items-center justify-center text-xl font-bold text-white font-mono uppercase shadow-[0_0_24px_rgba(144,59,238,0.3)]">
                        {{ substr($user->name, 0, 2) }}
                    </div>
                    <h2 class="mt-4 font-bold text-base text-zinc-100">{{ $user->name }}</h2>
                    <p class="text-xs text-zinc-400 font-mono mt-0.5 truncate">{{ $user->email }}</p>

                    <div class="mt-4 pt-4 border-t border-[#232736] flex flex-col items-center gap-2">
                        <span class="text-[10px] uppercase font-mono text-zinc-500">Peran Sistem (Role)</span>
                        <span class="text-xs font-mono font-semibold uppercase px-2.5 py-1 rounded border {{ $user->role === 'admin' ? 'border-amber-500/50 text-amber-300 bg-amber-500/10' : ($user->role === 'owner' ? 'border-violet-500/50 text-violet-300 bg-violet-500/10' : 'border-zinc-700 text-zinc-300 bg-zinc-800/40') }}">
                            {{ $user->role }}
                        </span>
                    </div>

                    <div class="mt-4 pt-4 border-t border-[#232736] text-[11px] font-mono text-zinc-500 text-left space-y-1.5">
                        <div class="flex justify-between">
                            <span>ID Pengguna:</span>
                            <span class="text-zinc-300">#{{ $user->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Bergabung:</span>
                            <span class="text-zinc-300">{{ $user->created_at ? $user->created_at->format('d M Y') : 'Hari ini' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Scope Stats -->
                <div class="p-4 rounded-lg border border-[#232736] bg-[#12141C] space-y-3">
                    <div class="text-[10px] font-mono uppercase tracking-wider text-zinc-500">Keterlibatan Proyek</div>
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center justify-between p-2 rounded bg-[#0C0D12] border border-[#232736]">
                            <span class="text-zinc-400">Daftar Dimiliki (Owner)</span>
                            <span class="font-mono font-bold text-violet-400">{{ $user->ownedTaskLists()->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded bg-[#0C0D12] border border-[#232736]">
                            <span class="text-zinc-400">Daftar Kolaborasi (Member)</span>
                            <span class="font-mono font-bold text-indigo-400">{{ $user->memberTaskLists()->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded bg-[#0C0D12] border border-[#232736]">
                            <span class="text-zinc-400">Tugas Ditugaskan</span>
                            <span class="font-mono font-bold text-emerald-400">{{ $user->assignedTasks()->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Edit Profile Form -->
            <div class="md:col-span-2">
                <div class="p-6 rounded-lg border border-[#232736] bg-[#12141C] shadow-lg">
                    <h3 class="text-sm font-bold uppercase tracking-wider font-mono text-zinc-200 mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                        Perbarui Data Diri & Kredensial
                    </h3>

                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-xs font-mono text-zinc-300 mb-1.5">
                                NAMA LENGKAP
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required 
                                   class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-mono transition" />
                            @error('name')
                                <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-mono text-zinc-300 mb-1.5">
                                ALAMAT EMAIL
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required 
                                   class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-mono transition" />
                            @error('email')
                                <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4 border-t border-[#232736]">
                            <h4 class="text-xs font-semibold text-zinc-300 mb-1">Ganti Password (Opsional)</h4>
                            <p class="text-[11px] text-zinc-500 mb-4">Kosongkan kolom di bawah jika tidak ingin mengubah password akun Anda.</p>

                            <div class="space-y-3">
                                <div>
                                    <label for="password" class="block text-xs font-mono text-zinc-400 mb-1">
                                        PASSWORD BARU
                                    </label>
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Minimal 6 karakter"
                                           class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-mono transition" />
                                    @error('password')
                                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-xs font-mono text-zinc-400 mb-1">
                                        ULANGI PASSWORD BARU
                                    </label>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Konfirmasi password baru"
                                           class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-mono transition" />
                                </div>
                            </div>
                        </div>

                        <!-- Action Submit -->
                        <div class="pt-4 border-t border-[#232736] flex justify-end">
                            <button type="submit" 
                                    class="py-2.5 px-5 rounded-md bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-medium text-xs tracking-wider uppercase font-mono transition border border-violet-400/30 shadow-[0_0_16px_rgba(144,59,238,0.25)] flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
