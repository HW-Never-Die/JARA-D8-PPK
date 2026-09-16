<x-layouts.app>
    <x-slot:title>Daftar Tugas</x-slot:title>

    <div x-data="{ deleteModalOpen: false, deleteListId: null, deleteListName: '' }" class="space-y-8">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#232736] pb-6">
            <div>
                <div class="flex items-center gap-2 text-xs font-mono text-zinc-500 mb-1">
                    <span>WORKSPACE</span>
                    <span>/</span>
                    <span class="text-violet-400">DAFTAR TUGAS</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-100 font-sans">Semua Daftar Tugas</h1>
                <p class="text-xs text-zinc-400 mt-1">Kelola semua daftar tugas yang Anda miliki atau ikuti sebagai anggota.</p>
            </div>

            <a href="{{ route('task-lists.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-md bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-mono tracking-wider font-semibold uppercase transition border border-violet-400/30 shadow-[0_0_20px_rgba(144,59,238,0.25)] self-start sm:self-auto">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                <span>Buat Daftar Baru</span>
            </a>
        </div>

        {{-- Section: Daftar Milik Saya (Owner) --}}
        <div class="space-y-4">
            <h2 class="text-sm font-bold tracking-wider uppercase font-mono text-zinc-200 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                Daftar Tugas Saya (Owner)
                <span class="text-xs font-mono text-zinc-500">({{ $ownedLists->count() }})</span>
            </h2>

            @if($ownedLists->isEmpty())
                <div class="p-8 rounded-lg border border-dashed border-[#2B3042] text-center bg-[#12141C]/50">
                    <svg class="w-10 h-10 mx-auto text-zinc-600 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                    <p class="text-xs text-zinc-400 mb-3">Anda belum memiliki daftar tugas.</p>
                    <a href="{{ route('task-lists.create') }}"
                       class="px-3 py-1.5 rounded bg-[#1C2030] hover:bg-[#252B42] text-violet-400 border border-[#2E3347] text-xs font-mono transition inline-flex items-center gap-1">
                        + Buat Daftar Tugas Pertama
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($ownedLists as $taskList)
                        @php
                            $prog = $taskList->progress();
                        @endphp
                        <div class="rounded-lg border border-[#232736] bg-[#12141C] p-5 flex flex-col justify-between hover:border-violet-500/50 hover:bg-[#141620] transition group">
                            {{-- Card Header --}}
                            <div>
                                <div class="flex items-start justify-between gap-3">
                                    <span class="text-[9px] font-mono uppercase px-2 py-0.5 rounded border border-violet-500/30 text-violet-400 bg-violet-500/10">
                                        Owner
                                    </span>

                                    {{-- Delete Button (triggers modal) --}}
                                    <button type="button"
                                            @click="deleteModalOpen = true; deleteListId = {{ $taskList->id }}; deleteListName = {{ Js::from($taskList->name) }}"
                                            title="Hapus daftar"
                                            class="text-zinc-500 hover:text-rose-400 p-1 transition">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>

                                <a href="{{ route('task-lists.show', $taskList) }}" class="block mt-3 group-hover:text-violet-300 transition">
                                    <h3 class="text-base font-bold text-zinc-100 font-sans leading-snug">{{ $taskList->name }}</h3>
                                    <p class="text-xs text-zinc-400 mt-1 line-clamp-2 leading-relaxed">
                                        {{ $taskList->description ?? 'Tidak ada deskripsi proyek.' }}
                                    </p>
                                </a>
                            </div>

                            {{-- Progress --}}
                            <div class="mt-6 pt-4 border-t border-[#232736]/70 space-y-3">
                                <div class="flex items-center justify-between text-xs font-mono">
                                    <span class="text-zinc-500">Progress</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[11px] text-zinc-400">{{ $prog['done'] }}/{{ $prog['total'] }} Selesai</span>
                                        <span class="font-bold text-violet-400">{{ $prog['percentage'] }}%</span>
                                    </div>
                                </div>

                                <div class="h-1.5 w-full bg-[#0C0D12] rounded-full overflow-hidden border border-[#232736]">
                                    <div class="h-full bg-gradient-to-r from-violet-600 to-indigo-500 rounded-full transition-all duration-500"
                                         style="width: {{ $prog['percentage'] }}%"></div>
                                </div>

                                {{-- Members + CTA --}}
                                <div class="flex items-center justify-between pt-1">
                                    <div class="flex items-center -space-x-1.5 overflow-hidden">
                                        <div class="w-6 h-6 rounded bg-violet-600 border border-[#12141C] flex items-center justify-center text-[9px] font-bold text-white font-mono"
                                             title="Owner: {{ $taskList->owner->name }}">
                                            {{ substr($taskList->owner->name, 0, 1) }}
                                        </div>
                                        @foreach($taskList->members->take(3) as $m)
                                            <div class="w-6 h-6 rounded bg-indigo-700 border border-[#12141C] flex items-center justify-center text-[9px] font-bold text-white font-mono"
                                                 title="Member: {{ $m->name }}">
                                                {{ substr($m->name, 0, 1) }}
                                            </div>
                                        @endforeach
                                        @if($taskList->members->count() > 3)
                                            <div class="w-6 h-6 rounded bg-[#252836] border border-[#12141C] flex items-center justify-center text-[9px] font-bold text-zinc-400 font-mono">
                                                +{{ $taskList->members->count() - 3 }}
                                            </div>
                                        @endif
                                    </div>

                                    <a href="{{ route('task-lists.show', $taskList) }}"
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

        {{-- Section: Daftar Kolaborasi (Member) --}}
        @if($memberLists->isNotEmpty())
            <div class="space-y-4 pt-4 border-t border-[#232736]">
                <h2 class="text-sm font-bold tracking-wider uppercase font-mono text-zinc-200 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    Daftar Kolaborasi (Member)
                    <span class="text-xs font-mono text-zinc-500">({{ $memberLists->count() }})</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($memberLists as $taskList)
                        @php
                            $prog = $taskList->progress();
                        @endphp
                        <div class="rounded-lg border border-[#232736] bg-[#12141C] p-5 flex flex-col justify-between hover:border-indigo-500/50 hover:bg-[#141620] transition group">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] font-mono uppercase px-2 py-0.5 rounded border border-indigo-500/30 text-indigo-400 bg-indigo-500/10">
                                        Member
                                    </span>
                                    <span class="text-[10px] font-mono text-zinc-500">Owner: {{ $taskList->owner->name }}</span>
                                </div>

                                <a href="{{ route('task-lists.show', $taskList) }}" class="block mt-3 group-hover:text-indigo-300 transition">
                                    <h3 class="text-base font-bold text-zinc-100 font-sans">{{ $taskList->name }}</h3>
                                    <p class="text-xs text-zinc-400 mt-1 line-clamp-2">
                                        {{ $taskList->description ?? 'Tidak ada deskripsi.' }}
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
                                    <div class="h-full bg-gradient-to-r from-indigo-500 to-teal-500 rounded-full transition-all duration-500"
                                         style="width: {{ $prog['percentage'] }}%"></div>
                                </div>

                                <div class="flex justify-end pt-1">
                                    <a href="{{ route('task-lists.show', $taskList) }}"
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

        {{-- Delete Confirmation Modal --}}
        @include('task-lists._delete-modal')
    </div>
</x-layouts.app>
