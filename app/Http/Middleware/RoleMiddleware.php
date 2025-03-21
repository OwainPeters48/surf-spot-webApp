<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role)
    {
        \Log::info('RoleMiddleware executed', [
            'required_role' => $role,
            'user' => auth()->user() ? auth()->user()->toArray() : null,
        ]);

        if (auth()->check() && auth()->user()->role === $role) {
            \Log::info('User passed role check', [
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role,
                'required_role' => $role,
            ]);
            return $next($request);
        }

        \Log::warning('User failed role check', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role ?? 'guest',
            'required_role' => $role,
        ]);

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
