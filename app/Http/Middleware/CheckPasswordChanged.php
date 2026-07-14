<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPasswordChanged
{
    public function handle(Request $request, Closure $next)
    {
        // If user is logged in, must change password, and isn't already on the change page
        if (auth()->check() && auth()->user()->must_change_password && !$request->is('password/change*')) {
            return redirect()->route('password.change.form');
        }
        return $next($request);
    }
}