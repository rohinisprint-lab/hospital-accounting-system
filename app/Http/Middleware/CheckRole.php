<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role ?? 'receptionist';

        // Admin has universal superuser access
        if ($userRole === 'admin') {
            return $next($request);
        }

        // Check if user's role matches any allowed role
        if (!in_array($userRole, $roles)) {
            abort(403, 'Access Denied: You do not have permission to access this financial module.');
        }

        return $next($request);
    }
}