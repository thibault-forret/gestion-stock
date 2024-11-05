<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LanguageToggleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectTo(
            guests: function () {
                // Redirige vers la page de connexion en fonction du guard
                if (Auth::guard('warehouse')->guest()) {
                    return route('warehouse.login');
                }

                if (Auth::guard('store')->guest()) {
                    return route('store.login');
                }

                return '/';
            },
            users: function () {
                // Redirige vers le tableau de bord en fonction du guard
                if (Auth::guard('warehouse')->check()) {
                    return route('warehouse.dashboard');
                }

                if (Auth::guard('store')->check()) {
                    return route('store.dashboard');
                }

                return route('index');
            }
        )

        ->alias(['lang.toggle' => LanguageToggleMiddleware::class]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
