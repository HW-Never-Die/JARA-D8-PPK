<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskListController extends Controller
{
    private function authorizeOwner(TaskList $taskList): void
    {
        $user = Auth::user();
        if ($taskList->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke daftar tugas ini.');
        }
    }

    private function authorizeAccess(TaskList $taskList): void
    {
        $user = Auth::user();
        $isOwner = $taskList->user_id === $user->id;
        $isMember = $taskList->members()->where('user_id', $user->id)->exists();

        if (! $isOwner && ! $isMember && ! $user->isAdmin()) {
            abort(403, 'Anda bukan anggota dari daftar tugas ini.');
        }
    }

    public function index()
    {
        $user = Auth::user();

        $taskLists = TaskList::where('user_id', $user->id)
            ->orWhereHas('members', fn ($q) => $q->where('user_id', $user->id))
            ->with(['owner', 'members', 'tasks'])
            ->latest()
            ->get();

        return view('task-lists.index', compact('taskLists'));
    }

    public function create()
    {
        return view('task-lists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $taskList = Auth::user()->ownedTaskLists()->create($validated);

        return redirect()->route('task-lists.show', $taskList)
            ->with('success', "Daftar tugas '{$taskList->name}' berhasil dibuat.");
    }

    public function show(Request $request, TaskList $taskList)
    {
        $this->authorizeAccess($taskList);

        $taskList->load(['owner', 'members', 'tasks.assignees']);

        $user = Auth::user();
        $isOwner = $taskList->user_id === $user->id || $user->isAdmin();
        $isMember = $taskList->members->contains($user->id) || $isOwner;

        $statusFilter = $request->query('status', 'all');
        $priorityFilter = $request->query('priority', 'all');
        $search = $request->query('search', '');
        $sort = $request->query('sort', 'deadline');

        $tasksQuery = $taskList->tasks()->with('assignees');

        if ($statusFilter && $statusFilter !== 'all') {
            $tasksQuery->where('status', $statusFilter);
        }

        if ($priorityFilter && $priorityFilter !== 'all') {
            $tasksQuery->where('priority', $priorityFilter);
        }

        if ($search) {
            $tasksQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($sort === 'priority') {
            $tasksQuery->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 ELSE 4 END");
        } elseif ($sort === 'deadline') {
            $tasksQuery->orderByRaw('CASE WHEN deadline IS NULL THEN 1 ELSE 0 END, deadline ASC');
        } else {
            $tasksQuery->latest();
        }

        $allTasks = $tasksQuery->get();

        $todoTasks = $allTasks->where('status', 'todo');
        $inProgressTasks = $allTasks->where('status', 'in_progress');
        $doneTasks = $allTasks->where('status', 'done');

        $progress = $taskList->progress();

        $existingMemberIds = $taskList->members->pluck('id')->push($taskList->user_id)->all();
        $availableUsers = User::whereNotIn('id', $existingMemberIds)->orderBy('name')->get();
        $assignableUsers = $taskList->members->push($taskList->owner)->unique('id');

        return view('task-lists.show', compact(
            'taskList',
            'isOwner',
            'isMember',
            'allTasks',
            'todoTasks',
            'inProgressTasks',
            'doneTasks',
            'progress',
            'statusFilter',
            'priorityFilter',
            'search',
            'sort',
            'availableUsers',
            'assignableUsers'
        ));
    }

    public function update(Request $request, TaskList $taskList)
    {
        $this->authorizeOwner($taskList);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $taskList->update($validated);

        return back()->with('success', 'Informasi daftar tugas berhasil diperbarui.');
    }

    public function destroy(TaskList $taskList)
    {
        $this->authorizeOwner($taskList);

        $name = $taskList->name;
        $taskList->delete();

        return redirect()->route('dashboard')
            ->with('success', "Daftar tugas '{$name}' berhasil dihapus.");
    }
}
