<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPasswordChanged
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Check if user is authenticated
        // 2. Check if they MUST change their password
        // 3. Exclude admins and the password change routes
        if (auth()->check() && auth()->user()->must_change_password && auth()->user()->role !== 'admin') {
            if (!$request->is('password/change') && !$request->is('password/change/update')) {
                return redirect()->route('password.change.form');
            }
        }

        return $next($request);
    }
}