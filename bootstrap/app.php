<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

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
                if (Auth::guard('entrepot')->guest()) {
                    return '/entrepot/login';
                }

                if (Auth::guard('magasin')->guest()) {
                    return '/magasin/login';
                }

                return '/';
            },
            users: function () {
                // Redirige vers le tableau de bord en fonction du guard
                if (Auth::guard('entrepot')->check()) {
                    return '/entrepot/dashboard';
                }

                if (Auth::guard('magasin')->check()) {
                    return '/magasin/dashboard';
                }

                return '/';
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
