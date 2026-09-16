<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If user is not authenticated, redirect to login
        if (! $user) {
            $request->session()->flash('error', 'Please login first.');

            return redirect('/login');
        }

        // Get the intended route prefix
        $path = $request->path();

        // Admin routes - only admin can access
        if (str_starts_with($path, 'admin') && ! $user->isAdmin()) {
            $request->session()->flash('error', 'You do not have admin access.');

            return redirect('/dashboard');
        }

        // Owner routes - owner or admin can access
        if (str_starts_with($path, 'owner') && ! $user->isAdmin() && $user->role !== 'owner') {
            $request->session()->flash('error', 'You do not have owner access.');

            return redirect('/dashboard');
        }

        // Member routes - member or owner or admin can access
        if (str_starts_with($path, 'member') && ! $user->isAdmin() && $user->role !== 'owner' && $user->role !== 'member') {
            $request->session()->flash('error', 'You do not have member access.');

            return redirect('/dashboard');
        }

        return $next($request);
    }
}
