<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskListController extends Controller
{
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
        $taskList->load(['owner', 'members', 'tasks.assignees']);

        $user = Auth::user();
        $isOwner = $taskList->user_id === $user->id || $user->isAdmin();
        $isMember = $taskList->members->contains($user->id) || $isOwner;

        // Filtering & Sorting
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

        if ($sort === 'deadline') {
            $tasksQuery->orderByRaw('deadline IS NULL, deadline ASC');
        } elseif ($sort === 'priority') {
            $tasksQuery->orderByRaw("FIELD(priority, 'high', 'medium', 'low')");
        } else {
            $tasksQuery->latest();
        }

        $allTasks = $tasksQuery->get();

        // Categorize for Kanban board
        $todoTasks = $allTasks->where('status', 'todo');
        $inProgressTasks = $allTasks->where('status', 'in_progress');
        $doneTasks = $allTasks->where('status', 'done');

        // Progress stats
        $progress = $taskList->progress();

        // Candidate users for collaboration (all system users not yet in list)
        $existingMemberIds = $taskList->members->pluck('id')->push($taskList->user_id)->all();
        $availableUsers = User::whereNotIn('id', $existingMemberIds)->orderBy('name')->get();

        // Team members available for assignment (owner + members)
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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $taskList->update($validated);

        return back()->with('success', "Informasi daftar tugas berhasil diperbarui.");
    }

    public function destroy(TaskList $taskList)
    {
        $name = $taskList->name;
        $taskList->delete();

        return redirect()->route('dashboard')
            ->with('success', "Daftar tugas '{$name}' berhasil dihapus.");
    }
}
