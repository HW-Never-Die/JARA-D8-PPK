<x-layouts.app>
    <x-slot:title>Admin Dashboard</x-slot:title>

    <div class="space-y-6">
        <div class="border-b border-[#232736] pb-6">
            <h1 class="text-2xl font-bold tracking-tight text-zinc-100">Admin Dashboard</h1>
            <p class="text-xs text-zinc-400 mt-1">Kelola seluruh pengguna dan pantau metrik sistem JARA.</p>
        </div>

        <!-- Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-lg border border-[#232736] bg-[#12141C]">
                <span class="text-[10px] uppercase font-mono text-zinc-500">Total Pengguna</span>
                <p class="text-2xl font-bold font-mono text-amber-400 mt-1">{{ $totalUsers }}</p>
            </div>
            <div class="p-4 rounded-lg border border-[#232736] bg-[#12141C]">
                <span class="text-[10px] uppercase font-mono text-zinc-500">Total Daftar Tugas</span>
                <p class="text-2xl font-bold font-mono text-violet-400 mt-1">{{ $totalLists }}</p>
            </div>
            <div class="p-4 rounded-lg border border-[#232736] bg-[#12141C]">
                <span class="text-[10px] uppercase font-mono text-zinc-500">Total Tugas</span>
                <p class="text-2xl font-bold font-mono text-emerald-400 mt-1">{{ $totalTasks }}</p>
            </div>
        </div>

        <!-- User Table -->
        <div class="p-6 rounded-lg border border-[#232736] bg-[#12141C]">
            <h2 class="text-sm font-bold uppercase tracking-wider font-mono text-zinc-200 mb-4">Daftar Pengguna</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="text-[10px] uppercase font-mono text-zinc-500 border-b border-[#232736]">
                        <tr>
                            <th class="py-2.5 px-3">Nama</th>
                            <th class="py-2.5 px-3">Email</th>
                            <th class="py-2.5 px-3">Peran</th>
                            <th class="py-2.5 px-3">Daftar Tugas</th>
                            <th class="py-2.5 px-3">Tugas</th>
                            <th class="py-2.5 px-3">Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#232736]/60 text-zinc-300">
                        @foreach($users as $u)
                            <tr class="hover:bg-[#161823]">
                                <td class="py-3 px-3 font-medium text-zinc-100">{{ $u->name }}</td>
                                <td class="py-3 px-3 font-mono text-zinc-400">{{ $u->email }}</td>
                                <td class="py-3 px-3">
                                    <span class="text-[10px] font-mono uppercase px-2 py-0.5 rounded border {{ $u->role === 'admin' ? 'border-amber-500/50 text-amber-300 bg-amber-500/10' : ($u->role === 'owner' ? 'border-violet-500/50 text-violet-300 bg-violet-500/10' : 'border-zinc-700 text-zinc-300 bg-zinc-800/40') }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 font-mono">{{ $u->owned_task_lists_count }}</td>
                                <td class="py-3 px-3 font-mono">{{ $u->assigned_tasks_count }}</td>
                                <td class="py-3 px-3 font-mono text-zinc-500">{{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
