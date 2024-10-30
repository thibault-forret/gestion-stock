<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RedirectionController;
use App\Http\Controllers\AuthController;

// Route::get('/login', function () {
//     if (auth()->guard('admin')->check()) {
//         return redirect()->route('admin.dashboard');
//     }
//     return (new AdminLoginController)->showLoginForm();
// })->name('login');

// Route::post('/login', [AdminLoginController::class, 'login']);

// // Routes lorsque user est authentifié
// Route::middleware('auth:admin')->group(function () 
// {
//     Route::fallback([RedirectionController::class, 'redirectToDashboardPage']);

//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// });

// Redirige vers dashboard en cas d'erreur sur l'url
Route::fallback([RedirectionController::class, 'redirectToDashboard']);


// Ajouter une page au début pour choisir si on est un entrepot ou un magasin, cf. le login de l'ent de l'upjv pour exemple

// Permet de rediriger vers la page d'accueil, pour séléctionner entrepot ou magasin
Route::get('/', [IndexController::class, 'index'])->name('index');


Route::prefix('entrepot')->name('entrepot.')->group(function () {
    Route::get('/login', function () {
        if (auth()->guard('entrepot')->check()) {
            return redirect()->route('entrepot.dashboard');
        }
        return (new AuthController)->loginFormEntrepot();
    })->name('login');

    Route::post('/login', [AuthController::class, 'loginEntrepotConnexion']);

    Route::get('/logout', [AuthController::class, 'logoutEntrepot'])->name('logout');

    // Routes lorsque user est authentifié
    Route::middleware('auth:entrepot')->group(function () {
        Route::fallback([RedirectionController::class, 'redirectToDashboardEntrepot']);

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


        // Routes concernant les utilisateurs de l'entrepot
    });
});

