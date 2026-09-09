<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollaborationController extends Controller
{
    private function authorizeOwner(TaskList $taskList): void
    {
        $user = Auth::user();
        if ($taskList->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Hanya pemilik daftar tugas yang dapat mengelola kolaborator.');
        }
    }

    public function inviteMember(Request $request, TaskList $taskList)
    {
        $this->authorizeOwner($taskList);

        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim($validated['email']));
        $user = User::where('email', $email)->first();

        if (! $user) {
            return back()->with('error', "Pengguna dengan email '{$email}' belum terdaftar di sistem.");
        }

        if ($user->id === $taskList->user_id) {
            return back()->with('error', "Pengguna {$user->name} adalah pemilik daftar tugas ini.");
        }

        if ($taskList->members()->where('user_id', $user->id)->exists()) {
            return back()->with('info', "Pengguna {$user->name} sudah menjadi anggota kolaborasi.");
        }

        $taskList->members()->attach($user->id);

        return back()->with('success', "{$user->name} ({$user->email}) berhasil ditambahkan ke daftar kolaborasi.");
    }

    public function removeMember(Request $request, TaskList $taskList, User $user)
    {
        $this->authorizeOwner($taskList);

        $taskList->members()->detach($user->id);

        $taskIds = $taskList->tasks()->pluck('id');
        $user->assignedTasks()->detach($taskIds);

        return back()->with('success', "{$user->name} berhasil dihapus dari anggota kolaborasi.");
    }

    public function assignMember(Request $request, Task $task)
    {
        $this->authorizeOwner($task->taskList);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $isMember = $task->taskList->members()->where('user_id', $validated['user_id'])->exists();
        $isOwner = $task->taskList->user_id == $validated['user_id'];

        if (! $isMember && ! $isOwner) {
            return back()->with('error', 'Pengguna harus menjadi anggota daftar tugas terlebih dahulu.');
        }

        $task->assignees()->syncWithoutDetaching([$validated['user_id']]);

        $user = User::find($validated['user_id']);

        return back()->with('success', "{$user->name} berhasil ditugaskan ke '{$task->title}'.");
    }

    public function unassignMember(Request $request, Task $task, User $user)
    {
        $this->authorizeOwner($task->taskList);

        $task->assignees()->detach($user->id);

        return back()->with('success', "Penugasan {$user->name} pada '{$task->title}' dibatalkan.");
    }
}
