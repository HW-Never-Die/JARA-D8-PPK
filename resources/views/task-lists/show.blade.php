<x-layouts.app>
    <x-slot:title>{{ $taskList->name }}</x-slot:title>

    <div x-data="{
        viewMode: 'board', // 'board' or 'table'
        activeTab: 'tasks', // 'tasks' or 'collaboration'
        createTaskOpen: false,
        inviteMemberOpen: false,
        editTaskOpen: false,
        detailTaskOpen: false,
        activeTask: null,
        
        openEdit(task) {
            this.activeTask = task;
            this.editTaskOpen = true;
        },
        openDetail(task) {
            this.activeTask = task;
            this.detailTaskOpen = true;
        }
    }" class="space-y-6">

        <!-- Top Breadcrumb & Actions Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-[#232736] pb-6">
            <div class="space-y-1">
                <div class="flex items-center gap-2 text-xs font-mono text-zinc-500">
                    <a href="{{ route('dashboard') }}" class="hover:text-zinc-300 transition">DASHBOARD</a>
                    <span>/</span>
                    <span class="text-zinc-400">DAFTAR TUGAS</span>
                    <span>/</span>
                    <span class="text-violet-400 font-semibold uppercase">#{{ $taskList->id }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-zinc-100 font-sans">{{ $taskList->name }}</h1>
                    <span class="text-[10px] font-mono uppercase px-2 py-0.5 rounded border {{ $isOwner ? 'border-violet-500/40 text-violet-400 bg-violet-500/10' : 'border-indigo-500/40 text-indigo-400 bg-indigo-500/10' }}">
                        {{ $isOwner ? 'Owner' : 'Member' }}
                    </span>
                </div>
                @if($taskList->description)
                    <p class="text-xs text-zinc-400 max-w-2xl leading-relaxed">{{ $taskList->description }}</p>
                @endif
            </div>

            <!-- Primary Actions -->
            <div class="flex flex-wrap items-center gap-2.5 self-start md:self-auto">
                <!-- Invite Member Button (Collab UI) -->
                <button type="button" 
                        @click="inviteMemberOpen = true" 
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-md border border-[#2E3347] bg-[#141620] hover:bg-[#1C2030] text-zinc-300 hover:text-white text-xs font-mono transition">
                    <svg class="w-3.5 h-3.5 text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                    </svg>
                    <span>Kelola Kolaborator ({{ $taskList->members->count() + 1 }})</span>
                </button>

                <!-- Create Task Button -->
                <button type="button" 
                        @click="createTaskOpen = true" 
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-mono font-semibold uppercase tracking-wider transition border border-violet-400/30 shadow-[0_0_20px_rgba(144,59,238,0.25)]">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>Tambah Tugas</span>
                </button>
            </div>
        </div>

        <!-- Progress Monitoring Bar (UR-37..41) -->
        <div class="p-4 rounded-lg border border-[#232736] bg-[#12141C] flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-6 w-full md:w-auto">
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase font-mono text-zinc-500">Total Tugas</span>
                    <span class="text-lg font-bold font-mono text-zinc-100">{{ $progress['total'] }}</span>
                </div>
                <div class="h-8 w-px bg-[#232736]"></div>
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase font-mono text-zinc-500">Selesai (Done)</span>
                    <span class="text-lg font-bold font-mono text-emerald-400">{{ $progress['done'] }}</span>
                </div>
                <div class="h-8 w-px bg-[#232736]"></div>
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase font-mono text-zinc-500">Belum Selesai</span>
                    <span class="text-lg font-bold font-mono text-amber-400">{{ $progress['pending'] }}</span>
                </div>
            </div>

            <!-- Progress Meter -->
            <div class="flex items-center gap-4 w-full md:w-80">
                <div class="flex-1">
                    <div class="flex justify-between text-xs font-mono mb-1.5">
                        <span class="text-zinc-400 text-[11px]">Capaian Proyek</span>
                        <span class="font-bold text-violet-400">{{ $progress['percentage'] }}%</span>
                    </div>
                    <div class="h-2 w-full bg-[#0C0D12] rounded-full overflow-hidden border border-[#232736]">
                        <div class="h-full bg-gradient-to-r from-violet-600 to-emerald-400 rounded-full transition-all duration-500" 
                             style="width: {{ $progress['percentage'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Toolbar: Navigation Tabs & Search / Filter -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pt-2">
            <!-- Tabs Switcher: Tasks vs Collaboration View -->
            <div class="flex items-center gap-2 border-b border-[#232736] pb-2 lg:pb-0 lg:border-b-0">
                <button type="button" 
                        @click="activeTab = 'tasks'"
                        class="px-3.5 py-1.5 rounded-md text-xs font-mono uppercase tracking-wider transition font-medium"
                        :class="activeTab === 'tasks' ? 'bg-[#1E2230] text-zinc-100 border border-[#2E3347]' : 'text-zinc-500 hover:text-zinc-300'">
                    Daftar Tugas ({{ $allTasks->count() }})
                </button>
                <button type="button" 
                        @click="activeTab = 'collaboration'"
                        class="px-3.5 py-1.5 rounded-md text-xs font-mono uppercase tracking-wider transition font-medium flex items-center gap-1.5"
                        :class="activeTab === 'collaboration' ? 'bg-[#1E2230] text-zinc-100 border border-[#2E3347]' : 'text-zinc-500 hover:text-zinc-300'">
                    <span>Kolaborator & Penugasan</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded bg-indigo-950/60 text-indigo-400 border border-indigo-800/50">
                        {{ $taskList->members->count() + 1 }}
                    </span>
                </button>
            </div>

            <!-- Filter & View Switcher (Only visible when activeTab === 'tasks') -->
            <div x-show="activeTab === 'tasks'" class="flex flex-wrap items-center gap-3">
                <!-- Search Form (UR-45) -->
                <form method="GET" action="{{ route('task-lists.show', $taskList) }}" class="flex items-center gap-2">
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                    <input type="hidden" name="priority" value="{{ $priorityFilter }}">
                    <input type="hidden" name="sort" value="{{ $sort }}">

                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               placeholder="Cari tugas..." 
                               class="pl-8 pr-3 py-1.5 rounded-md bg-[#12141C] border border-[#2B3042] text-zinc-200 placeholder-zinc-600 text-xs focus:outline-none focus:border-violet-500 font-mono w-44 md:w-56 transition" />
                        <svg class="w-3.5 h-3.5 text-zinc-500 absolute left-2.5 top-2.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                    <!-- Priority Filter Dropdown (UR-46) -->
                    <select name="priority" 
                            onchange="this.form.submit()" 
                            class="px-2.5 py-1.5 rounded-md bg-[#12141C] border border-[#2B3042] text-zinc-300 text-xs font-mono focus:outline-none focus:border-violet-500 transition">
                        <option value="all" {{ $priorityFilter === 'all' ? 'selected' : '' }}>Semua Prioritas</option>
                        <option value="high" {{ $priorityFilter === 'high' ? 'selected' : '' }}>High Priority</option>
                        <option value="medium" {{ $priorityFilter === 'medium' ? 'selected' : '' }}>Medium Priority</option>
                        <option value="low" {{ $priorityFilter === 'low' ? 'selected' : '' }}>Low Priority</option>
                    </select>

                    <!-- Status Filter Dropdown (UR-47) -->
                    <select name="status" 
                            onchange="this.form.submit()" 
                            class="px-2.5 py-1.5 rounded-md bg-[#12141C] border border-[#2B3042] text-zinc-300 text-xs font-mono focus:outline-none focus:border-violet-500 transition">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="todo" {{ $statusFilter === 'todo' ? 'selected' : '' }}>TODO</option>
                        <option value="in_progress" {{ $statusFilter === 'in_progress' ? 'selected' : '' }}>IN PROGRESS</option>
                        <option value="done" {{ $statusFilter === 'done' ? 'selected' : '' }}>DONE</option>
                    </select>

                    @if($search || $priorityFilter !== 'all' || $statusFilter !== 'all')
                        <a href="{{ route('task-lists.show', $taskList) }}" 
                           title="Reset filter"
                           class="p-1.5 rounded border border-[#2B3042] bg-[#12141C] text-zinc-400 hover:text-rose-400 transition text-xs font-mono">
                            &times;
                        </a>
                    @endif
                </form>

                <!-- Board vs Table View Toggle -->
                <div class="flex items-center rounded-md border border-[#2B3042] bg-[#0C0D12] p-0.5">
                    <button type="button" 
                            @click="viewMode = 'board'" 
                            title="Tampilan Kanban Board"
                            class="p-1.5 rounded transition"
                            :class="viewMode === 'board' ? 'bg-[#1E2230] text-violet-400 shadow-sm' : 'text-zinc-500 hover:text-zinc-300'">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="18" rx="1"/>
                            <rect x="14" y="3" width="7" height="10" rx="1"/>
                        </svg>
                    </button>
                    <button type="button" 
                            @click="viewMode = 'table'" 
                            title="Tampilan Tabel Linear"
                            class="p-1.5 rounded transition"
                            :class="viewMode === 'table' ? 'bg-[#1E2230] text-violet-400 shadow-sm' : 'text-zinc-500 hover:text-zinc-300'">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M3 12h18M3 18h18"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 1: TASKS VIEW (BOARD & TABLE) -->
        <!-- ========================================================================= -->
        <div x-show="activeTab === 'tasks'" class="space-y-6">

            <!-- A. KANBAN BOARD VIEW -->
            <div x-show="viewMode === 'board'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Column 1: TODO -->
                <div class="rounded-lg border border-[#232736] bg-[#101219] p-4 flex flex-col min-h-[500px]">
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-[#232736]">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-zinc-500"></span>
                            <span class="text-xs font-mono font-bold uppercase tracking-wider text-zinc-300">TODO</span>
                            <span class="text-[10px] font-mono text-zinc-500 px-1.5 py-0.2 rounded bg-[#161822] border border-[#232736]">
                                {{ $todoTasks->count() }}
                            </span>
                        </div>
                        <button type="button" @click="createTaskOpen = true" class="text-zinc-500 hover:text-zinc-300 transition text-xs font-mono">
                            + Tambah
                        </button>
                    </div>

                    <!-- Task Cards in TODO -->
                    <div class="space-y-3 flex-1 overflow-y-auto pr-0.5">
                        @forelse($todoTasks as $task)
                            @include('task-lists.partials.task-card', ['task' => $task])
                        @empty
                            <div class="p-6 text-center text-zinc-600 text-xs font-mono border border-dashed border-[#232736] rounded-md">
                                Tidak ada tugas TODO
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Column 2: IN PROGRESS -->
                <div class="rounded-lg border border-[#232736] bg-[#101219] p-4 flex flex-col min-h-[500px]">
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-[#232736]">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            <span class="text-xs font-mono font-bold uppercase tracking-wider text-amber-300">IN PROGRESS</span>
                            <span class="text-[10px] font-mono text-amber-400 px-1.5 py-0.2 rounded bg-amber-950/30 border border-amber-800/40">
                                {{ $inProgressTasks->count() }}
                            </span>
                        </div>
                    </div>

                    <!-- Task Cards in IN PROGRESS -->
                    <div class="space-y-3 flex-1 overflow-y-auto pr-0.5">
                        @forelse($inProgressTasks as $task)
                            @include('task-lists.partials.task-card', ['task' => $task])
                        @empty
                            <div class="p-6 text-center text-zinc-600 text-xs font-mono border border-dashed border-[#232736] rounded-md">
                                Tidak ada tugas berjalan
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Column 3: DONE -->
                <div class="rounded-lg border border-[#232736] bg-[#101219] p-4 flex flex-col min-h-[500px]">
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-[#232736]">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-xs font-mono font-bold uppercase tracking-wider text-emerald-300">DONE</span>
                            <span class="text-[10px] font-mono text-emerald-400 px-1.5 py-0.2 rounded bg-emerald-950/30 border border-emerald-800/40">
                                {{ $doneTasks->count() }}
                            </span>
                        </div>
                    </div>

                    <!-- Task Cards in DONE -->
                    <div class="space-y-3 flex-1 overflow-y-auto pr-0.5">
                        @forelse($doneTasks as $task)
                            @include('task-lists.partials.task-card', ['task' => $task])
                        @empty
                            <div class="p-6 text-center text-zinc-600 text-xs font-mono border border-dashed border-[#232736] rounded-md">
                                Belum ada tugas selesai
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- B. TABLE VIEW (Linear Data Dense) -->
            <div x-show="viewMode === 'table'" class="rounded-lg border border-[#232736] bg-[#12141C] overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-zinc-300 font-sans">
                        <thead class="bg-[#0C0D12] text-zinc-500 font-mono text-[11px] uppercase border-b border-[#232736]">
                            <tr>
                                <th class="py-3 px-4 w-12">Status</th>
                                <th class="py-3 px-4">Judul & Deskripsi Tugas</th>
                                <th class="py-3 px-4 w-28">Prioritas</th>
                                <th class="py-3 px-4 w-36">Tenggat (Deadline)</th>
                                <th class="py-3 px-4 w-44">Assignees</th>
                                <th class="py-3 px-4 w-36 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#232736]/60">
                            @forelse($allTasks as $task)
                                <tr class="hover:bg-[#161823] transition group">
                                    <!-- Status Toggle -->
                                    <td class="py-3 px-4">
                                        <form method="POST" action="{{ route('tasks.update-status', $task) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $task->status === 'done' ? 'todo' : 'done' }}">
                                            <button type="submit" 
                                                    title="{{ $task->status === 'done' ? 'Kembalikan ke TODO' : 'Tandai Selesai' }}"
                                                    class="w-4 h-4 rounded border flex items-center justify-center transition {{ $task->status === 'done' ? 'bg-emerald-600 border-emerald-500 text-white' : 'border-zinc-600 hover:border-violet-400 bg-[#0C0D12]' }}">
                                                @if($task->status === 'done')
                                                    <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Title & Description -->
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col">
                                            <button type="button" 
                                                    @click="openDetail({{ json_encode($task->load('assignees')) }})"
                                                    class="text-left font-semibold text-zinc-100 group-hover:text-violet-300 transition line-clamp-1 {{ $task->status === 'done' ? 'line-through text-zinc-500' : '' }}">
                                                {{ $task->title }}
                                            </button>
                                            @if($task->description)
                                                <span class="text-[11px] text-zinc-500 line-clamp-1 mt-0.5">{{ $task->description }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Priority Badge -->
                                    <td class="py-3 px-4 font-mono">
                                        @php
                                            $priClass = match($task->priority) {
                                                'high' => 'border-rose-500/50 text-rose-300 bg-rose-500/10',
                                                'medium' => 'border-amber-500/50 text-amber-300 bg-amber-500/10',
                                                default => 'border-zinc-700 text-zinc-400 bg-zinc-800/40',
                                            };
                                        @endphp
                                        <span class="text-[10px] uppercase px-2 py-0.5 rounded border {{ $priClass }}">
                                            {{ $task->priority }}
                                        </span>
                                    </td>

                                    <!-- Deadline -->
                                    <td class="py-3 px-4 font-mono text-[11px] text-zinc-400">
                                        {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M Y') : '—' }}
                                    </td>

                                    <!-- Assignees Stack -->
                                    <td class="py-3 px-4">
                                        <div class="flex items-center -space-x-1.5 overflow-hidden">
                                            @forelse($task->assignees as $assignee)
                                                <div class="w-5 h-5 rounded bg-gradient-to-tr from-violet-600 to-indigo-600 border border-[#12141C] flex items-center justify-center text-[8px] font-bold text-white font-mono uppercase" 
                                                     title="{{ $assignee->name }} ({{ $assignee->email }})">
                                                    {{ substr($assignee->name, 0, 1) }}
                                                </div>
                                            @empty
                                                <span class="text-[10px] text-zinc-600 font-mono italic">Belum ada</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-3 px-4 text-right font-mono">
                                        <div class="inline-flex items-center gap-2">
                                            <button type="button" 
                                                    @click="openEdit({{ json_encode($task->load('assignees')) }})"
                                                    class="text-zinc-500 hover:text-zinc-200 p-1 transition" 
                                                    title="Edit tugas">
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                            </button>
                                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Hapus tugas ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-zinc-500 hover:text-rose-400 p-1 transition" title="Hapus tugas">
                                                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-zinc-500 font-mono">
                                        Tidak ada tugas yang cocok dengan filter saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 2: COLLABORATION & ASSIGNMENT MANAGEMENT (Collab UI) -->
        <!-- ========================================================================= -->
        <div x-show="activeTab === 'collaboration'" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: List Members & Roles -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="rounded-lg border border-[#232736] bg-[#12141C] p-6 shadow-xl">
                        <div class="flex items-center justify-between border-b border-[#232736] pb-4 mb-4">
                            <div>
                                <h3 class="text-sm font-bold uppercase font-mono tracking-wider text-zinc-100 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                    Anggota Kolaborasi Proyek
                                </h3>
                                <p class="text-xs text-zinc-400 mt-1">Daftar pengguna yang memiliki akses untuk melihat dan mengerjakan tugas.</p>
                            </div>
                            <button type="button" 
                                    @click="inviteMemberOpen = true" 
                                    class="px-3 py-1.5 rounded-md bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-mono font-semibold transition">
                                + Undang Anggota
                            </button>
                        </div>

                        <!-- Members Table -->
                        <div class="divide-y divide-[#232736]/70">
                            <!-- Owner Row -->
                            <div class="py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-violet-600 flex items-center justify-center text-xs font-bold text-white font-mono uppercase">
                                        {{ substr($taskList->owner->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-zinc-200">{{ $taskList->owner->name }}</span>
                                            <span class="text-[9px] font-mono px-1.5 py-0.2 rounded border border-violet-500/40 text-violet-400 bg-violet-500/10 uppercase">
                                                Owner (Pembuat)
                                            </span>
                                        </div>
                                        <span class="text-[11px] font-mono text-zinc-500">{{ $taskList->owner->email }}</span>
                                    </div>
                                </div>
                                <span class="text-[10px] font-mono text-zinc-500">Akses Penuh</span>
                            </div>

                            <!-- Invited Members -->
                            @forelse($taskList->members as $member)
                                <div class="py-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-indigo-700 flex items-center justify-center text-xs font-bold text-white font-mono uppercase">
                                            {{ substr($member->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-zinc-200">{{ $member->name }}</span>
                                                <span class="text-[9px] font-mono px-1.5 py-0.2 rounded border border-indigo-500/40 text-indigo-400 bg-indigo-500/10 uppercase">
                                                    Member
                                                </span>
                                            </div>
                                            <span class="text-[11px] font-mono text-zinc-500">{{ $member->email }}</span>
                                        </div>
                                    </div>

                                    <!-- Remove Member Action (Owner only) -->
                                    @if($isOwner)
                                        <form method="POST" action="{{ route('task-lists.members.remove', [$taskList, $member]) }}" onsubmit="return confirm('Hapus kolaborator ini dari proyek?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 rounded border border-rose-900/60 bg-rose-950/20 text-rose-400 hover:bg-rose-950/40 text-[10px] font-mono transition">
                                                Keluarkan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] font-mono text-zinc-500">Kolaborator</span>
                                    @endif
                                </div>
                            @empty
                                <div class="py-6 text-center text-zinc-500 text-xs font-mono">
                                    Belum ada anggota tambahan. Undang rekan tim untuk mulai berkolaborasi.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right: Quick Invite & Task Assignment Overview -->
                <div class="space-y-6">
                    <!-- Quick Invite Box -->
                    <div class="p-6 rounded-lg border border-[#232736] bg-[#12141C] shadow-lg">
                        <h4 class="text-xs font-bold uppercase font-mono text-zinc-200 mb-3 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <span>Undang via Email</span>
                        </h4>
                        <form method="POST" action="{{ route('task-lists.members.invite', $taskList) }}" class="space-y-3">
                            @csrf
                            <div>
                                <input type="email" 
                                       name="email" 
                                       required 
                                       placeholder="rekan@domain.com" 
                                       class="w-full px-3 py-2 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-xs font-mono focus:outline-none focus:border-indigo-500 transition" />
                            </div>
                            <button type="submit" 
                                    class="w-full py-2 px-3 rounded-md bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-mono font-semibold uppercase tracking-wider transition">
                                Kirim Undangan
                            </button>
                        </form>
                    </div>

                    <!-- Assignable Quick Roster -->
                    <div class="p-5 rounded-lg border border-[#232736] bg-[#12141C]">
                        <div class="text-[10px] font-mono uppercase tracking-wider text-zinc-500 mb-3">Distribusi Tugas Anggota</div>
                        <div class="space-y-2 text-xs font-mono">
                            @foreach($assignableUsers as $u)
                                @php
                                    $tasksCount = $taskList->tasks()->whereHas('assignees', function($q) use ($u) {
                                        $q->where('users.id', $u->id);
                                    })->count();
                                @endphp
                                <div class="flex items-center justify-between p-2 rounded bg-[#0C0D12] border border-[#232736]">
                                    <span class="text-zinc-300 truncate max-w-[150px]">{{ $u->name }}</span>
                                    <span class="text-violet-400 font-bold">{{ $tasksCount }} tugas</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODALS -->
        <!-- ========================================================================= -->

        <!-- 1. MODAL TAMBAH TUGAS (UR-18..22) -->
        <div x-show="createTaskOpen" 
             style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
             x-transition>
            <div @click.away="createTaskOpen = false" 
                 class="w-full max-w-lg rounded-lg border border-[#2E3347] bg-[#12141C] p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-[#232736] pb-3">
                    <h3 class="text-sm font-bold font-mono uppercase tracking-wider text-zinc-100 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                        Tambah Tugas Baru
                    </h3>
                    <button @click="createTaskOpen = false" class="text-zinc-500 hover:text-zinc-300 text-lg leading-none">&times;</button>
                </div>

                <form method="POST" action="{{ route('tasks.store', $taskList) }}" class="space-y-4">
                    @csrf

                    <!-- Title (UR-19) -->
                    <div>
                        <label class="block text-xs font-mono text-zinc-300 mb-1.5">JUDUL TUGAS *</label>
                        <input type="text" 
                               name="title" 
                               required 
                               placeholder="Tuliskan nama tugas spesifik..."
                               class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-mono transition" />
                    </div>

                    <!-- Description (UR-20) -->
                    <div>
                        <label class="block text-xs font-mono text-zinc-300 mb-1.5">DESKRIPSI TUGAS</label>
                        <textarea name="description" 
                                  rows="3"
                                  placeholder="Rincian cara pengerjaan atau acceptance criteria..."
                                  class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-sans transition"></textarea>
                    </div>

                    <!-- Priority & Status & Deadline (UR-21, UR-22) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 mb-1">PRIORITAS</label>
                            <select name="priority" class="w-full px-2.5 py-2 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-200 text-xs font-mono focus:outline-none focus:border-violet-500">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 mb-1">STATUS AWAL</label>
                            <select name="status" class="w-full px-2.5 py-2 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-200 text-xs font-mono focus:outline-none focus:border-violet-500">
                                <option value="todo" selected>TODO</option>
                                <option value="in_progress">IN PROGRESS</option>
                                <option value="done">DONE</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 mb-1">DEADLINE</label>
                            <input type="date" 
                                   name="deadline" 
                                   class="w-full px-2.5 py-2 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-200 text-xs font-mono focus:outline-none focus:border-violet-500" />
                        </div>
                    </div>

                    <!-- Assignee (UR-34) -->
                    <div>
                        <label class="block text-xs font-mono text-zinc-300 mb-1.5">TUGASKAN KEPADA (ASSIGNEES)</label>
                        <div class="max-h-32 overflow-y-auto space-y-1.5 p-2 rounded-md bg-[#0C0D12] border border-[#2B3042]">
                            @foreach($assignableUsers as $u)
                                <label class="flex items-center gap-2 text-xs text-zinc-300 cursor-pointer p-1 rounded hover:bg-[#161822]">
                                    <input type="checkbox" name="assignees[]" value="{{ $u->id }}" class="rounded border-[#2B3042] bg-[#12141C] text-violet-600 focus:ring-0">
                                    <span>{{ $u->name }} ({{ $u->email }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#232736]">
                        <button type="button" 
                                @click="createTaskOpen = false" 
                                class="px-4 py-2 rounded-md border border-[#2E3347] bg-[#161823] text-zinc-400 hover:text-zinc-200 text-xs font-mono transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 rounded-md bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-mono font-semibold uppercase tracking-wider transition">
                            Simpan Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. MODAL EDIT TUGAS (UR-23) -->
        <div x-show="editTaskOpen" 
             style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
             x-transition>
            <div @click.away="editTaskOpen = false" 
                 class="w-full max-w-lg rounded-lg border border-[#2E3347] bg-[#12141C] p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-[#232736] pb-3">
                    <h3 class="text-sm font-bold font-mono uppercase tracking-wider text-zinc-100 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Ubah Informasi Tugas
                    </h3>
                    <button @click="editTaskOpen = false" class="text-zinc-500 hover:text-zinc-300 text-lg leading-none">&times;</button>
                </div>

                <form :action="'{{ url('tasks') }}/' + (activeTask ? activeTask.id : '')" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-mono text-zinc-300 mb-1.5">JUDUL TUGAS *</label>
                        <input type="text" 
                               name="title" 
                               :value="activeTask ? activeTask.title : ''"
                               required 
                               class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-mono transition" />
                    </div>

                    <div>
                        <label class="block text-xs font-mono text-zinc-300 mb-1.5">DESKRIPSI TUGAS</label>
                        <textarea name="description" 
                                  rows="3"
                                  x-text="activeTask ? activeTask.description : ''"
                                  class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-sans transition"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 mb-1">PRIORITAS</label>
                            <select name="priority" :value="activeTask ? activeTask.priority : 'medium'" class="w-full px-2.5 py-2 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-200 text-xs font-mono focus:outline-none focus:border-violet-500">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 mb-1">STATUS</label>
                            <select name="status" :value="activeTask ? activeTask.status : 'todo'" class="w-full px-2.5 py-2 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-200 text-xs font-mono focus:outline-none focus:border-violet-500">
                                <option value="todo">TODO</option>
                                <option value="in_progress">IN PROGRESS</option>
                                <option value="done">DONE</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-400 mb-1">DEADLINE</label>
                            <input type="date" 
                                   name="deadline" 
                                   :value="activeTask && activeTask.deadline ? activeTask.deadline.substring(0, 10) : ''"
                                   class="w-full px-2.5 py-2 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-200 text-xs font-mono focus:outline-none focus:border-violet-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-mono text-zinc-300 mb-1.5">ASSIGNEES</label>
                        <div class="max-h-32 overflow-y-auto space-y-1.5 p-2 rounded-md bg-[#0C0D12] border border-[#2B3042]">
                            @foreach($assignableUsers as $u)
                                <label class="flex items-center gap-2 text-xs text-zinc-300 cursor-pointer p-1 rounded hover:bg-[#161822]">
                                    <input type="checkbox" 
                                           name="assignees[]" 
                                           value="{{ $u->id }}" 
                                           :checked="activeTask && activeTask.assignees && activeTask.assignees.some(a => a.id === {{ $u->id }})"
                                           class="rounded border-[#2B3042] bg-[#12141C] text-violet-600 focus:ring-0">
                                    <span>{{ $u->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#232736]">
                        <button type="button" 
                                @click="editTaskOpen = false" 
                                class="px-4 py-2 rounded-md border border-[#2E3347] bg-[#161823] text-zinc-400 hover:text-zinc-200 text-xs font-mono transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 rounded-md bg-amber-600 hover:bg-amber-500 text-white text-xs font-mono font-semibold uppercase tracking-wider transition">
                            Perbarui Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. DRAWER / MODAL DETAIL TUGAS (UR-25) -->
        <div x-show="detailTaskOpen" 
             style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
             x-transition>
            <div @click.away="detailTaskOpen = false" 
                 class="w-full max-w-lg rounded-lg border border-[#2E3347] bg-[#12141C] p-6 shadow-2xl space-y-5">
                <div class="flex items-start justify-between border-b border-[#232736] pb-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-mono uppercase px-2 py-0.5 rounded border"
                                  :class="{
                                      'border-rose-500/50 text-rose-300 bg-rose-500/10': activeTask && activeTask.priority === 'high',
                                      'border-amber-500/50 text-amber-300 bg-amber-500/10': activeTask && activeTask.priority === 'medium',
                                      'border-zinc-700 text-zinc-400 bg-zinc-800/40': activeTask && activeTask.priority === 'low'
                                  }"
                                  x-text="activeTask ? activeTask.priority.toUpperCase() : ''"></span>
                            <span class="text-[10px] font-mono text-zinc-500" x-text="activeTask && activeTask.deadline ? 'Deadline: ' + activeTask.deadline.substring(0,10) : 'Tanpa Deadline'"></span>
                        </div>
                        <h3 class="text-base font-bold text-zinc-100" x-text="activeTask ? activeTask.title : ''"></h3>
                    </div>
                    <button @click="detailTaskOpen = false" class="text-zinc-500 hover:text-zinc-300 text-lg leading-none">&times;</button>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-[10px] font-mono uppercase text-zinc-500 block mb-1">Deskripsi</span>
                        <p class="text-zinc-300 whitespace-pre-line leading-relaxed p-3 rounded bg-[#0C0D12] border border-[#232736]" 
                           x-text="activeTask && activeTask.description ? activeTask.description : 'Tidak ada deskripsi rinci.'"></p>
                    </div>

                    <div>
                        <span class="text-[10px] font-mono uppercase text-zinc-500 block mb-1.5">Penanggung Jawab (Assignees)</span>
                        <div class="flex flex-wrap gap-2">
                            <template x-if="activeTask && activeTask.assignees && activeTask.assignees.length">
                                <template x-for="a in activeTask.assignees" :key="a.id">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-[#1C2030] border border-[#2E3347] text-zinc-200 text-[11px] font-mono">
                                        <span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>
                                        <span x-text="a.name"></span>
                                    </span>
                                </template>
                            </template>
                            <template x-if="!activeTask || !activeTask.assignees || !activeTask.assignees.length">
                                <span class="text-zinc-500 italic font-mono text-[11px]">Belum ditugaskan ke siapa pun.</span>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-[#232736]">
                    <!-- Quick Status Change (UR-26) -->
                    <form :action="'{{ url('tasks') }}/' + (activeTask ? activeTask.id : '') + '/status'" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <span class="text-[10px] font-mono text-zinc-500 uppercase">Ubah Status:</span>
                        <select name="status" onchange="this.form.submit()" class="px-2 py-1 rounded bg-[#0C0D12] border border-[#2B3042] text-xs font-mono text-zinc-200">
                            <option value="todo" :selected="activeTask && activeTask.status === 'todo'">TODO</option>
                            <option value="in_progress" :selected="activeTask && activeTask.status === 'in_progress'">IN PROGRESS</option>
                            <option value="done" :selected="activeTask && activeTask.status === 'done'">DONE</option>
                        </select>
                    </form>

                    <div class="flex items-center gap-2">
                        <button type="button" 
                                @click="detailTaskOpen = false; openEdit(activeTask)" 
                                class="px-3 py-1.5 rounded bg-[#1C2030] hover:bg-[#252B42] text-violet-300 border border-[#2E3347] text-xs font-mono transition">
                            Edit
                        </button>
                        <button type="button" 
                                @click="detailTaskOpen = false" 
                                class="px-3 py-1.5 rounded bg-zinc-800 text-zinc-300 text-xs font-mono">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. MODAL UNDANG MEMBER (UR-28) -->
        <div x-show="inviteMemberOpen" 
             style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
             x-transition>
            <div @click.away="inviteMemberOpen = false" 
                 class="w-full max-w-md rounded-lg border border-[#2E3347] bg-[#12141C] p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-[#232736] pb-3">
                    <h3 class="text-sm font-bold font-mono uppercase tracking-wider text-zinc-100 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        Undang Kolaborator Proyek
                    </h3>
                    <button @click="inviteMemberOpen = false" class="text-zinc-500 hover:text-zinc-300 text-lg leading-none">&times;</button>
                </div>

                <form method="POST" action="{{ route('task-lists.members.invite', $taskList) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-mono text-zinc-300 mb-1.5">ALAMAT EMAIL PENGGUNA *</label>
                        <input type="email" 
                               name="email" 
                               required 
                               placeholder="nama@domain.com" 
                               class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-indigo-500 font-mono transition" />
                        <p class="text-[10px] text-zinc-500 mt-1">Masukkan email pengguna terdaftar (atau email baru untuk auto-invite).</p>
                    </div>

                    @if($availableUsers->isNotEmpty())
                        <div>
                            <span class="block text-[10px] uppercase font-mono text-zinc-500 mb-1.5">Atau pilih dari pengguna terdaftar:</span>
                            <div class="max-h-28 overflow-y-auto space-y-1 p-2 rounded bg-[#0C0D12] border border-[#232736]">
                                @foreach($availableUsers as $avail)
                                    <button type="button" 
                                            onclick="document.querySelector('input[name=email]').value = '{{ $avail->email }}'"
                                            class="w-full text-left px-2 py-1 rounded text-xs font-mono text-zinc-400 hover:text-zinc-100 hover:bg-[#161822] flex items-center justify-between">
                                        <span>{{ $avail->name }}</span>
                                        <span class="text-[10px] text-zinc-600">{{ $avail->email }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#232736]">
                        <button type="button" 
                                @click="inviteMemberOpen = false" 
                                class="px-4 py-2 rounded-md border border-[#2E3347] bg-[#161823] text-zinc-400 hover:text-zinc-200 text-xs font-mono transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 rounded-md bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-mono font-semibold uppercase tracking-wider transition">
                            Tambah Anggota
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.app>
