<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware('role:admin')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            // Not logged in
            return redirect()->route('login');
        }

        if (!$request->user()->hasRole($role)) {
            // User does not have the role
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
