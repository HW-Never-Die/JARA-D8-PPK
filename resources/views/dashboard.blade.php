<x-layouts.app>
    <x-slot:title>Dashboard</x-slot:title>

    <div x-data="{ createModalOpen: false, editModalOpen: false, editListId: null, editListName: '', editListDesc: '' }" class="space-y-8">
        <!-- Dashboard Top Header with Action -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#232736] pb-6">
            <div>
                <div class="flex items-center gap-2 text-xs font-mono text-zinc-500 mb-1">
                    <span>WORKSPACE</span>
                    <span>/</span>
                    <span class="text-violet-400">PENGELOLA TUGAS</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-100 font-sans">Dashboard Pengguna</h1>
                <p class="text-xs text-zinc-400 mt-1">Ringkasan kemajuan tugas dan kolaborasi seluruh proyek aktif.</p>
            </div>

            <!-- Create New List Button -->
            <button type="button" 
                    @click="createModalOpen = true" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-md bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-mono tracking-wider font-semibold uppercase transition border border-violet-400/30 shadow-[0_0_20px_rgba(144,59,238,0.25)] self-start sm:self-auto cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                <span>Buat Daftar Baru</span>
            </button>
        </div>

        <!-- KPI Metrics Grid (UR-37..41 Progress Monitoring) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Lists -->
            <div class="p-4 rounded-lg border border-[#232736] bg-[#12141C] relative overflow-hidden group hover:border-[#2E3347] transition">
                <div class="flex items-center justify-between text-zinc-500 text-xs font-mono">
                    <span class="uppercase">Total Daftar</span>
                    <svg class="w-4 h-4 text-zinc-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono text-zinc-100">{{ $totalLists }}</span>
                    <span class="text-[10px] text-zinc-500 font-mono">proyek</span>
                </div>
                <div class="mt-2 text-[10px] text-zinc-500 font-mono">
                    {{ $ownedLists->count() }} milik saya &bull; {{ $memberLists->count() }} kolaborasi
                </div>
            </div>

            <!-- Total Tasks -->
            <div class="p-4 rounded-lg border border-[#232736] bg-[#12141C] relative overflow-hidden group hover:border-[#2E3347] transition">
                <div class="flex items-center justify-between text-zinc-500 text-xs font-mono">
                    <span class="uppercase">Semua Tugas</span>
                    <svg class="w-4 h-4 text-indigo-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono text-zinc-100">{{ $totalTasks }}</span>
                    <span class="text-[10px] text-indigo-400 font-mono">item</span>
                </div>
                <div class="mt-2 text-[10px] text-zinc-500 font-mono">
                    {{ $todoTasks }} TODO &bull; {{ $inProgressTasks }} IN PROGRESS
                </div>
            </div>

            <!-- Selesai (Completed) -->
            <div class="p-4 rounded-lg border border-[#232736] bg-[#12141C] relative overflow-hidden group hover:border-[#2E3347] transition">
                <div class="flex items-center justify-between text-zinc-500 text-xs font-mono">
                    <span class="uppercase">Tugas Selesai</span>
                    <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono text-emerald-400">{{ $completedTasks }}</span>
                    <span class="text-[10px] text-zinc-500 font-mono">/ {{ $totalTasks }} total</span>
                </div>
                <div class="mt-2 text-[10px] text-emerald-500/80 font-mono">
                    {{ $totalTasks - $completedTasks }} tersisa
                </div>
            </div>

            <!-- Overall Progress Percentage -->
            <div class="p-4 rounded-lg border border-[#232736] bg-[#12141C] relative overflow-hidden group hover:border-[#2E3347] transition">
                <div class="flex items-center justify-between text-zinc-500 text-xs font-mono">
                    <span class="uppercase">Total Progress</span>
                    <span class="text-xs font-mono font-bold text-violet-400">{{ $overallProgress }}%</span>
                </div>
                <div class="mt-2">
                    <div class="h-2 w-full bg-[#0C0D12] rounded-full overflow-hidden border border-[#232736]">
                        <div class="h-full bg-gradient-to-r from-violet-600 via-purple-500 to-emerald-400 transition-all duration-500" 
                             style="width: {{ $overallProgress }}%"></div>
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-between text-[10px] font-mono text-zinc-500">
                    <span>Efisiensi</span>
                    <span class="text-zinc-400">{{ $completedTasks > 0 ? 'Aktif Berjalan' : 'Belum Dimulai' }}</span>
                </div>
            </div>
        </div>

        <!-- Section 1: Daftar Milik Saya (Owner) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold tracking-wider uppercase font-mono text-zinc-200 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                    Daftar Tugas Saya (Owner)
                    <span class="text-xs font-mono text-zinc-500">({{ $ownedLists->count() }})</span>
                </h2>
            </div>

            @if($ownedLists->isEmpty())
                <div class="p-8 rounded-lg border border-dashed border-[#2B3042] text-center bg-[#12141C]/50">
                    <p class="text-xs text-zinc-400 mb-3">Anda belum memiliki daftar tugas.</p>
                    <button type="button" @click="createModalOpen = true" 
                            class="px-3 py-1.5 rounded bg-[#1C2030] hover:bg-[#252B42] text-violet-400 border border-[#2E3347] text-xs font-mono transition">
                        + Buat Daftar Tugas Pertama
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($ownedLists as $list)
                        @php
                            $prog = $list->progress();
                        @endphp
                        <div class="rounded-lg border border-[#232736] bg-[#12141C] p-5 flex flex-col justify-between hover:border-violet-500/50 hover:bg-[#141620] transition group">
                            <!-- Card Header -->
                            <div>
                                <div class="flex items-start justify-between gap-3">
                                    <span class="text-[9px] font-mono uppercase px-2 py-0.5 rounded border border-violet-500/30 text-violet-400 bg-violet-500/10">
                                        Owner
                                    </span>
                                    
                                    <!-- Action Menu (Edit / Delete) -->
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" 
                                                @click="editModalOpen = true; editListId = {{ $list->id }}; editListName = '{{ addslashes($list->name) }}'; editListDesc = '{{ addslashes($list->description ?? '') }}'"
                                                title="Edit info daftar"
                                                class="text-zinc-500 hover:text-zinc-300 p-1 transition">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                            </svg>
                                        </button>
                                        <form method="POST" action="{{ route('task-lists.destroy', $list) }}" onsubmit="return confirm('Hapus daftar tugas ini beserta semua tugas di dalamnya?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus daftar" class="text-zinc-500 hover:text-rose-400 p-1 transition">
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <a href="{{ route('task-lists.show', $list) }}" class="block mt-3 group-hover:text-violet-300 transition">
                                    <h3 class="text-base font-bold text-zinc-100 font-sans leading-snug">{{ $list->name }}</h3>
                                    <p class="text-xs text-zinc-400 mt-1 line-clamp-2 leading-relaxed">
                                        {{ $list->description ?? 'Tidak ada deskripsi proyek.' }}
                                    </p>
                                </a>
                            </div>

                            <!-- Progress Track (Far-Right Visual Progress) -->
                            <div class="mt-6 pt-4 border-t border-[#232736]/70 space-y-3">
                                <div class="flex items-center justify-between text-xs font-mono">
                                    <span class="text-zinc-500">Progress</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[11px] text-zinc-400">{{ $prog['done'] }}/{{ $prog['total'] }} Selesai</span>
                                        <span class="font-bold text-violet-400">{{ $prog['percentage'] }}%</span>
                                    </div>
                                </div>

                                <div class="h-1.5 w-full bg-[#0C0D12] rounded-full overflow-hidden border border-[#232736]">
                                    <div class="h-full bg-gradient-to-r from-violet-600 to-indigo-500 rounded-full" 
                                         style="width: {{ $prog['percentage'] }}%"></div>
                                </div>

                                <!-- Members Avatar Stack & CTA -->
                                <div class="flex items-center justify-between pt-1">
                                    <div class="flex items-center -space-x-1.5 overflow-hidden">
                                        <div class="w-6 h-6 rounded bg-violet-600 border border-[#12141C] flex items-center justify-center text-[9px] font-bold text-white font-mono" title="Owner: {{ $list->owner->name }}">
                                            {{ substr($list->owner->name, 0, 1) }}
                                        </div>
                                        @foreach($list->members->take(3) as $m)
                                            <div class="w-6 h-6 rounded bg-indigo-700 border border-[#12141C] flex items-center justify-center text-[9px] font-bold text-white font-mono" title="Member: {{ $m->name }}">
                                                {{ substr($m->name, 0, 1) }}
                                            </div>
                                        @endforeach
                                        @if($list->members->count() > 3)
                                            <div class="w-6 h-6 rounded bg-[#252836] border border-[#12141C] flex items-center justify-center text-[9px] font-bold text-zinc-400 font-mono">
                                                +{{ $list->members->count() - 3 }}
                                            </div>
                                        @endif
                                    </div>

                                    <a href="{{ route('task-lists.show', $list) }}" 
                                       class="inline-flex items-center gap-1 text-xs font-mono text-violet-400 hover:text-violet-300 font-medium">
                                        <span>Buka Task</span>
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Section 2: Daftar Kolaborasi (Member) -->
        @if($memberLists->isNotEmpty())
            <div class="space-y-4 pt-4 border-t border-[#232736]">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold tracking-wider uppercase font-mono text-zinc-200 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        Daftar Kolaborasi (Member)
                        <span class="text-xs font-mono text-zinc-500">({{ $memberLists->count() }})</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($memberLists as $list)
                        @php
                            $prog = $list->progress();
                        @endphp
                        <div class="rounded-lg border border-[#232736] bg-[#12141C] p-5 flex flex-col justify-between hover:border-indigo-500/50 hover:bg-[#141620] transition group">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] font-mono uppercase px-2 py-0.5 rounded border border-indigo-500/30 text-indigo-400 bg-indigo-500/10">
                                        Member
                                    </span>
                                    <span class="text-[10px] font-mono text-zinc-500">Owner: {{ $list->owner->name }}</span>
                                </div>

                                <a href="{{ route('task-lists.show', $list) }}" class="block mt-3 group-hover:text-indigo-300 transition">
                                    <h3 class="text-base font-bold text-zinc-100 font-sans">{{ $list->name }}</h3>
                                    <p class="text-xs text-zinc-400 mt-1 line-clamp-2">
                                        {{ $list->description ?? 'Tidak ada deskripsi.' }}
                                    </p>
                                </a>
                            </div>

                            <div class="mt-6 pt-4 border-t border-[#232736]/70 space-y-3">
                                <div class="flex items-center justify-between text-xs font-mono">
                                    <span class="text-zinc-500">Progress</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[11px] text-zinc-400">{{ $prog['done'] }}/{{ $prog['total'] }}</span>
                                        <span class="font-bold text-indigo-400">{{ $prog['percentage'] }}%</span>
                                    </div>
                                </div>

                                <div class="h-1.5 w-full bg-[#0C0D12] rounded-full overflow-hidden border border-[#232736]">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 to-teal-500 rounded-full" 
                                         style="width: {{ $prog['percentage'] }}%"></div>
                                </div>

                                <div class="flex justify-end pt-1">
                                    <a href="{{ route('task-lists.show', $list) }}" 
                                       class="inline-flex items-center gap-1 text-xs font-mono text-indigo-400 hover:text-indigo-300 font-medium">
                                        <span>Buka Task</span>
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Modal Buat Daftar Tugas Baru (UR-12, UR-13) -->
        <div x-show="createModalOpen" 
             style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
             x-transition>
            <div @click.away="createModalOpen = false" 
                 class="w-full max-w-md rounded-lg border border-[#2E3347] bg-[#12141C] p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-[#232736] pb-3">
                    <h3 class="text-sm font-bold font-mono uppercase tracking-wider text-zinc-100 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                        Buat Daftar Tugas Baru
                    </h3>
                    <button @click="createModalOpen = false" class="text-zinc-500 hover:text-zinc-300 text-lg leading-none">&times;</button>
                </div>

                <form method="POST" action="{{ route('task-lists.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="new_list_name" class="block text-xs font-mono text-zinc-300 mb-1.5">
                            NAMA DAFTAR TUGAS *
                        </label>
                        <input type="text" 
                               id="new_list_name" 
                               name="name" 
                               required 
                               placeholder="Contoh: Project Website / Tugas Kuliah"
                               class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-mono transition" />
                    </div>

                    <div>
                        <label for="new_list_desc" class="block text-xs font-mono text-zinc-300 mb-1.5">
                            DESKRIPSI PROYEK (OPSIONAL)
                        </label>
                        <textarea id="new_list_desc" 
                                  name="description" 
                                  rows="3"
                                  placeholder="Tuliskan tujuan atau cakupan tugas ini..."
                                  class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-sans transition"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#232736]">
                        <button type="button" 
                                @click="createModalOpen = false" 
                                class="px-4 py-2 rounded-md border border-[#2E3347] bg-[#161823] text-zinc-400 hover:text-zinc-200 text-xs font-mono transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 rounded-md bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-mono font-semibold uppercase tracking-wider transition border border-violet-400/30">
                            Simpan Daftar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Daftar Tugas (UR-15) -->
        <div x-show="editModalOpen" 
             style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
             x-transition>
            <div @click.away="editModalOpen = false" 
                 class="w-full max-w-md rounded-lg border border-[#2E3347] bg-[#12141C] p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-[#232736] pb-3">
                    <h3 class="text-sm font-bold font-mono uppercase tracking-wider text-zinc-100 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Ubah Informasi Daftar
                    </h3>
                    <button @click="editModalOpen = false" class="text-zinc-500 hover:text-zinc-300 text-lg leading-none">&times;</button>
                </div>

                <form :action="'{{ url('task-lists') }}/' + editListId" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-mono text-zinc-300 mb-1.5">
                            NAMA DAFTAR TUGAS *
                        </label>
                        <input type="text" 
                               name="name" 
                               x-model="editListName"
                               required 
                               class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-mono transition" />
                    </div>

                    <div>
                        <label class="block text-xs font-mono text-zinc-300 mb-1.5">
                            DESKRIPSI PROYEK
                        </label>
                        <textarea name="description" 
                                  rows="3"
                                  x-model="editListDesc"
                                  class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-sans transition"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#232736]">
                        <button type="button" 
                                @click="editModalOpen = false" 
                                class="px-4 py-2 rounded-md border border-[#2E3347] bg-[#161823] text-zinc-400 hover:text-zinc-200 text-xs font-mono transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 rounded-md bg-amber-600 hover:bg-amber-500 text-white text-xs font-mono font-semibold uppercase tracking-wider transition">
                            Perbarui Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
