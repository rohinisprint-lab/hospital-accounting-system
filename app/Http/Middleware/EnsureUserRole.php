<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('/incomes');
        }

        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Unauthorized access to this module.');
        }

        return $next($request);
    }
}