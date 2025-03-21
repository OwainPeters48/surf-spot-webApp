<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            Log::warning('Unauthenticated access attempt', [
                'url' => $request->url(),
            ]);
            return route('login');
        }
    }

    /**
     * Handle the incoming request.
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        Log::info('Authenticate middleware executed', [
            'url' => $request->url(),
            'user' => auth()->user() ? auth()->user()->toArray() : 'Guest',
        ]);

        return parent::handle($request, $next, ...$guards);
    }
}
