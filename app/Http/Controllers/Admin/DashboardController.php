<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::withCount(['ownedTaskLists', 'assignedTasks'])->latest()->paginate(15);
        $totalUsers = User::count();
        $totalLists = TaskList::count();
        $totalTasks = Task::count();

        return view('admin.dashboard', compact('users', 'totalUsers', 'totalLists', 'totalTasks'));
    }
}
