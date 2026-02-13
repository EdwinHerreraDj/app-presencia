<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {

        /*
        |--------------------------------------------------------------------------
        | TRUST PROXIES (Hosting compartido / SiteGround)
        |--------------------------------------------------------------------------
        */
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
        );

        /*
        |--------------------------------------------------------------------------
        | Alias de middlewares personalizados
        |--------------------------------------------------------------------------
        */
        $middleware->alias([
            'checkRole' => \App\Http\Middleware\CheckRole::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Forzar HTTPS en producción
        |--------------------------------------------------------------------------
        */
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    })

    ->withExceptions(function (Exceptions $exceptions) {

        /*
        |--------------------------------------------------------------------------
        | Manejo personalizado 419 (CSRF / sesión expirada)
        |--------------------------------------------------------------------------
        */
        $exceptions->render(function (TokenMismatchException $e, $request) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'Tu sesión ha expirado. Inicia sesión nuevamente.');
        });
    })

    ->create();
