<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Registers the secure Super Admin middleware and the Password Check guard
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'password.check' => \App\Http\Middleware\CheckPasswordChanged::class,
        ]);

        // 🔒 Intercepts and overrides the default framework guest/user redirection paths
        $middleware->redirectTo(
            guests: '/login',   // Forces logged-out traffic straight to the login form
            users: '/dentists'  // Forces logged-in users away from standard auth paths
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();