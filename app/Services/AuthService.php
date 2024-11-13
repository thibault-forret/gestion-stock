<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AuthService
{
    /**
     * Vérifie si l'utilisateur est connecté, et redirige si nécessaire.
     *
     * @param string $guard
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public static function verifyIfConnected(string $guard)
    {
        // Si l'utilisateur est déjà connecté avec ce guard
        if (Auth::guard($guard)->check()) {
            // Redirige vers le dashboard avec un message d'erreur
            return redirect()->route($guard . '.dashboard')->with('error', __('auth.error.logout_first_store'));
        }

        // Retourne null si l'utilisateur n'est pas connecté, ce qui permet de continuer normalement
        return null;
    }
}
