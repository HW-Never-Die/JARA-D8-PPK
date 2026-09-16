@props(['task'])

@php
    $priorityConfig = match($task->priority) {
        'high' => ['border' => 'border-rose-500/40', 'text' => 'text-rose-300', 'bg' => 'bg-rose-500/10', 'label' => 'HIGH'],
        'medium' => ['border' => 'border-amber-500/40', 'text' => 'text-amber-300', 'bg' => 'bg-amber-500/10', 'label' => 'MED'],
        default => ['border' => 'border-zinc-700', 'text' => 'text-zinc-400', 'bg' => 'bg-zinc-800/40', 'label' => 'LOW'],
    };

    $isOverdue = $task->deadline && \Carbon\Carbon::parse($task->deadline)->isPast() && $task->status !== 'done';
@endphp

<div class="rounded-md border border-[#232736] bg-[#141620] p-3.5 shadow-sm hover:border-violet-500/50 hover:bg-[#181A26] transition group flex flex-col justify-between gap-3">
    <!-- Top Row: Priority Badge + Quick Menu -->
    <div class="flex items-center justify-between gap-2">
        <span class="text-[9px] font-mono font-semibold uppercase px-1.5 py-0.2 rounded border {{ $priorityConfig['border'] }} {{ $priorityConfig['text'] }} {{ $priorityConfig['bg'] }}">
            {{ $priorityConfig['label'] }}
        </span>

        <div class="flex items-center gap-1 opacity-60 group-hover:opacity-100 transition">
            <!-- Edit Button -->
            <button type="button" 
                    @click="openEdit({{ json_encode($task->load('assignees')) }})" 
                    title="Ubah tugas" 
                    class="text-zinc-500 hover:text-zinc-200 p-0.5 transition">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
            </button>

            <!-- Delete Button -->
            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Hapus tugas ini?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" title="Hapus tugas" class="text-zinc-500 hover:text-rose-400 p-0.5 transition">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Body: Title & Short Desc (Clickable to detail modal) -->
    <button type="button" 
            @click="openDetail({{ json_encode($task->load('assignees')) }})" 
            class="text-left group-hover:text-violet-200 transition">
        <h4 class="text-xs font-semibold text-zinc-100 line-clamp-2 leading-snug {{ $task->status === 'done' ? 'line-through text-zinc-500' : '' }}">
            {{ $task->title }}
        </h4>
        @if($task->description)
            <p class="text-[11px] text-zinc-500 mt-1 line-clamp-2 leading-relaxed font-sans">
                {{ $task->description }}
            </p>
        @endif
    </button>

    <!-- Bottom Row: Deadline & Assignees & Status Cycle -->
    <div class="pt-2 border-t border-[#232736]/60 flex items-center justify-between gap-2">
        <!-- Deadline chip -->
        <div class="flex items-center gap-1 text-[10px] font-mono {{ $isOverdue ? 'text-rose-400 font-semibold' : 'text-zinc-500' }}">
            <svg class="w-3 h-3 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
            </svg>
            <span>
                {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M') : 'No date' }}
            </span>
        </div>

        <div class="flex items-center gap-2">
            <!-- Assignee avatar stack -->
            <div class="flex items-center -space-x-1 overflow-hidden">
                @foreach($task->assignees as $assignee)
                    <div class="w-5 h-5 rounded bg-gradient-to-tr from-violet-600 to-indigo-600 border border-[#141620] flex items-center justify-center text-[8px] font-bold text-white font-mono uppercase" 
                         title="{{ $assignee->name }}">
                        {{ substr($assignee->name, 0, 1) }}
                    </div>
                @endforeach
            </div>

            <!-- Quick Status Transition Button -->
            <form method="POST" action="{{ route('tasks.update-status', $task) }}" class="inline">
                @csrf
                @method('PATCH')
                @if($task->status === 'todo')
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit" title="Mulai kerjakan (pindah ke IN PROGRESS)" class="text-[9px] font-mono px-1.5 py-0.5 rounded border border-zinc-700 bg-[#0C0D12] text-zinc-400 hover:border-amber-500/50 hover:text-amber-300 transition">
                        &rarr; Prog
                    </button>
                @elseif($task->status === 'in_progress')
                    <input type="hidden" name="status" value="done">
                    <button type="submit" title="Selesaikan tugas (pindah ke DONE)" class="text-[9px] font-mono px-1.5 py-0.5 rounded border border-amber-800/40 bg-amber-950/20 text-amber-300 hover:border-emerald-500/50 hover:text-emerald-300 transition">
                        &rarr; Done
                    </button>
                @else
                    <input type="hidden" name="status" value="todo">
                    <button type="submit" title="Kembalikan ke TODO" class="text-[9px] font-mono px-1.5 py-0.5 rounded border border-emerald-800/40 bg-emerald-950/20 text-emerald-300 hover:border-zinc-600 hover:text-zinc-300 transition">
                        &larr; Todo
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>
