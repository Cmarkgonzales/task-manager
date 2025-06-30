<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            HandleCors::class,
            EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Session\Middleware\StartSession::class, // ✅ required!
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            ]);

        $middleware->alias([
            'check.admin' => \App\Http\Middleware\CheckAdmin::class,
        ]);
    })
    ->withProviders([
        App\Providers\AuthServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
    ])
    ->withExceptions(function (\Illuminate\Foundation\Configuration\Exceptions $exceptions) {
        $exceptions->renderable(function (ValidationException $e, $request) {
            return response()->json([
                'errors' => $e->errors()
            ], $e->status);
        });
    })
    ->create();
