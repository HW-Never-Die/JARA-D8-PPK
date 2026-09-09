<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Owned task lists
        $ownedLists = $user->ownedTaskLists()
            ->with(['tasks.assignees', 'members', 'owner'])
            ->latest()
            ->get();

        // Member task lists (where user is added as member)
        $memberLists = $user->memberTaskLists()
            ->with(['tasks.assignees', 'members', 'owner'])
            ->latest()
            ->get();

        // Aggregate statistics across user's scopes
        $allLists = $ownedLists->merge($memberLists);
        $totalLists = $allLists->count();
        
        $totalTasks = 0;
        $completedTasks = 0;
        $inProgressTasks = 0;
        $todoTasks = 0;

        foreach ($allLists as $list) {
            foreach ($list->tasks as $task) {
                $totalTasks++;
                if ($task->status === 'done') {
                    $completedTasks++;
                } elseif ($task->status === 'in_progress') {
                    $inProgressTasks++;
                } else {
                    $todoTasks++;
                }
            }
        }

        $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // User assigned tasks
        $assignedTasks = $user->assignedTasks()
            ->with('taskList')
            ->orderBy('deadline', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'user',
            'ownedLists',
            'memberLists',
            'totalLists',
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'todoTasks',
            'overallProgress',
            'assignedTasks'
        ));
    }
}
