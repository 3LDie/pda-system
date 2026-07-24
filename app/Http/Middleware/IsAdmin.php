<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user isn't logged in, or their role string isn't exactly 'admin', block them!
        if (!$request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. This operation requires Super Admin clearance.');
        }

        return $next($request);
    }
}