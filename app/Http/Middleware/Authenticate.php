<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    // public function handle($request, Closure $next, ...$guards)
    // {
    //     $this->authenticate($request, $guards);

    //     return $next($request)->header('Cache-Control', 'no-store, no-cache, must-revalidate')
    //         ->header('Cache-Control', 'post-check=0, pre-check=0', false)
    //         ->header('Pragma', 'no-cache')
    //         ->header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
    // }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('inicioSesion.index');
        }
    }
}
