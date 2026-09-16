@extends('layouts.app')

@section('title', 'Daftar Tugas')

@section('content')
<div x-data="{ deleteModalOpen: false, deleteListId: null, deleteListName: '' }" class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#232736] pb-6">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-zinc-500 mb-1">
                <span>WORKSPACE</span>
                <span>/</span>
                <span class="text-violet-400">DAFTAR TUGAS</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-100 font-sans">Daftar Tugas Saya</h1>
            <p class="text-xs text-zinc-400 mt-1">Kelola seluruh daftar tugas yang Anda miliki.</p>
        </div>

        <a href="{{ route('task-lists.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-md bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-mono tracking-wider font-semibold uppercase transition border border-violet-400/30 shadow-[0_0_20px_rgba(144,59,238,0.25)] self-start sm:self-auto">
            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            <span>Buat Daftar Baru</span>
        </a>
    </div>

    <!-- Task Lists Grid -->
    @if($taskLists->isEmpty())
        <div class="p-8 rounded-lg border border-dashed border-[#2B3042] text-center bg-[#12141C]/50">
            <p class="text-xs text-zinc-400 mb-3">Anda belum memiliki daftar tugas.</p>
            <a href="{{ route('task-lists.create') }}" 
               class="px-3 py-1.5 rounded bg-[#1C2030] hover:bg-[#252B42] text-violet-400 border border-[#2E3347] text-xs font-mono transition">
                + Buat Daftar Tugas Pertama
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($taskLists as $taskList)
                @php
                    $prog = $taskList->progress();
                @endphp
                <div class="rounded-lg border border-[#232736] bg-[#12141C] p-5 flex flex-col justify-between hover:border-violet-500/50 hover:bg-[#141620] transition group">
                    <!-- Card Header -->
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <span class="text-[9px] font-mono uppercase px-2 py-0.5 rounded border border-violet-500/30 text-violet-400 bg-violet-500/10">
                                {{ $taskList->user_id === auth()->id() ? 'Owner' : 'Member' }}
                            </span>

                            @if($taskList->user_id === auth()->id())
                                <!-- Delete Button (triggers modal) -->
                                <button type="button" 
                                        @click="deleteModalOpen = true; deleteListId = {{ $taskList->id }}; deleteListName = {{ Js::from($taskList->name) }}"
                                        title="Hapus daftar"
                                        class="text-zinc-500 hover:text-rose-400 p-1 transition">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            @endif
                        </div>

                        <a href="{{ route('task-lists.show', $taskList) }}" class="block mt-3 group-hover:text-violet-300 transition">
                            <h3 class="text-base font-bold text-zinc-100 font-sans leading-snug">{{ $taskList->name }}</h3>
                            <p class="text-xs text-zinc-400 mt-1 line-clamp-2 leading-relaxed">
                                {{ $taskList->description ?? 'Tidak ada deskripsi proyek.' }}
                            </p>
                        </a>
                    </div>

                    <!-- Progress Bar -->
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

                        <div class="flex justify-end pt-1">
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

    <!-- Modal Konfirmasi Hapus -->
    <div x-show="deleteModalOpen" 
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         x-transition>
        <div @click.away="deleteModalOpen = false" 
             class="w-full max-w-md rounded-lg border border-[#2E3347] bg-[#12141C] p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-[#232736] pb-3">
                <h3 class="text-sm font-bold font-mono uppercase tracking-wider text-rose-400 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    Konfirmasi Hapus
                </h3>
                <button @click="deleteModalOpen = false" class="text-zinc-500 hover:text-zinc-300 text-lg leading-none">&times;</button>
            </div>

            <p class="text-xs text-zinc-300 leading-relaxed">
                Menghapus daftar ini akan menghapus seluruh tugas dan keanggotaan di dalamnya secara permanen. Tindakan ini tidak dapat dibatalkan. Lanjutkan?
            </p>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#232736]">
                <button type="button" 
                        @click="deleteModalOpen = false" 
                        class="px-4 py-2 rounded-md border border-[#2E3347] bg-[#161823] text-zinc-400 hover:text-zinc-200 text-xs font-mono transition">
                    Batal
                </button>
                <form :action="'{{ url('task-lists') }}/' + deleteListId" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 rounded-md bg-rose-600 hover:bg-rose-500 text-white text-xs font-mono font-semibold uppercase tracking-wider transition">
                        Hapus Permanen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
