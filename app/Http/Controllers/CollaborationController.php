<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CollaborationController extends Controller
{
    public function inviteMember(Request $request, TaskList $taskList)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim($validated['email']));

        // Check if user already exists
        $user = User::where('email', $email)->first();

        // If user doesn't exist, create account for them with temporary password
        if (!$user) {
            $user = User::create([
                'name' => explode('@', $email)[0],
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'member',
            ]);
        }

        // Prevent adding list owner as member
        if ($user->id === $taskList->user_id) {
            return back()->with('error', "Pengguna {$user->name} adalah pemilik daftar tugas ini.");
        }

        // Check if already member
        if ($taskList->members()->where('user_id', $user->id)->exists()) {
            return back()->with('info', "Pengguna {$user->name} sudah menjadi anggota kolaborasi.");
        }

        $taskList->members()->attach($user->id);

        return back()->with('success', "{$user->name} ({$user->email}) berhasil ditambahkan ke daftar kolaborasi.");
    }

    public function removeMember(Request $request, TaskList $taskList, User $user)
    {
        // Detach member from list
        $taskList->members()->detach($user->id);

        // Also detach from any task assignments within this list
        $taskIds = $taskList->tasks()->pluck('id');
        $user->assignedTasks()->detach($taskIds);

        return back()->with('success', "{$user->name} berhasil dihapus dari anggota kolaborasi.");
    }

    public function assignMember(Request $request, Task $task)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $task->assignees()->syncWithoutDetaching([$validated['user_id']]);

        $user = User::find($validated['user_id']);
        return back()->with('success', "{$user->name} berhasil ditugaskan ke '{$task->title}'.");
    }

    public function unassignMember(Request $request, Task $task, User $user)
    {
        $task->assignees()->detach($user->id);

        return back()->with('success', "Penugasan {$user->name} pada '{$task->title}' dibatalkan.");
    }
}
