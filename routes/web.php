<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use Illuminate\Support\Facades\Route;

// Public guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated application routes
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Task Lists
    Route::post('/task-lists', [TaskListController::class, 'store'])->name('task-lists.store');
    Route::get('/task-lists/{taskList}', [TaskListController::class, 'show'])->name('task-lists.show');
    Route::put('/task-lists/{taskList}', [TaskListController::class, 'update'])->name('task-lists.update');
    Route::delete('/task-lists/{taskList}', [TaskListController::class, 'destroy'])->name('task-lists.destroy');

    // Task Management
    Route::post('/task-lists/{taskList}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Collaboration UI
    Route::post('/task-lists/{taskList}/members', [CollaborationController::class, 'inviteMember'])->name('task-lists.members.invite');
    Route::delete('/task-lists/{taskList}/members/{user}', [CollaborationController::class, 'removeMember'])->name('task-lists.members.remove');
    Route::post('/tasks/{task}/assign', [CollaborationController::class, 'assignMember'])->name('tasks.assign');
    Route::post('/tasks/{task}/unassign/{user}', [CollaborationController::class, 'unassignMember'])->name('tasks.unassign');
});
