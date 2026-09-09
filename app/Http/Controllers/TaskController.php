<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request, TaskList $taskList)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
            'status' => ['nullable', 'in:todo,in_progress,done'],
            'deadline' => ['nullable', 'date'],
            'assignees' => ['nullable', 'array'],
            'assignees.*' => ['exists:users,id'],
        ]);

        $task = $taskList->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'status' => $validated['status'] ?? 'todo',
            'deadline' => $validated['deadline'] ?? null,
        ]);

        if (!empty($validated['assignees'])) {
            $task->assignees()->sync($validated['assignees']);
        }

        return back()->with('success', "Tugas '{$task->title}' berhasil ditambahkan.");
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
            'status' => ['required', 'in:todo,in_progress,done'],
            'deadline' => ['nullable', 'date'],
            'assignees' => ['nullable', 'array'],
            'assignees.*' => ['exists:users,id'],
        ]);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'deadline' => $validated['deadline'] ?? null,
        ]);

        if (isset($validated['assignees'])) {
            $task->assignees()->sync($validated['assignees']);
        }

        return back()->with('success', "Tugas '{$task->title}' berhasil diperbarui.");
    }

    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:todo,in_progress,done'],
        ]);

        $task->update(['status' => $validated['status']]);

        return back()->with('success', "Status tugas diubah menjadi " . strtoupper(str_replace('_', ' ', $task->status)));
    }

    public function destroy(Task $task)
    {
        $title = $task->title;
        $task->delete();

        return back()->with('success', "Tugas '{$title}' berhasil dihapus.");
    }
}
