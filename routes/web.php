<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RedirectionController;
use App\Http\Controllers\AuthController;


// Redirige vers dashboard en cas d'erreur sur l'url
Route::fallback([RedirectionController::class, 'redirectToHome']);

// Ajouter une page au début pour choisir si on est un entrepot ou un magasin, cf. le login de l'ent de l'upjv pour exemple

// Permet de rediriger vers la page d'accueil, pour séléctionner entrepot ou magasin
// Route::get('/', [IndexController::class, 'index'])->name('index');

Route::get('/', function () {
    return view('pages.accueil');
})->name('index');

Route::get('/entrepot', function () {
    return redirect()->route('entrepot.login');
});

Route::prefix('entrepot')->name('entrepot.')->group(function () {
    Route::get('/login', function () {
        if (auth()->guard('entrepot')->check()) {
            return redirect()->route('entrepot.dashboard');
        }
        return (new AuthController)->showLoginFormEntrepot();
    })->name('login');

    Route::post('/login', [AuthController::class, 'loginEntrepot'])->name('login.post');

    Route::get('/logout', [AuthController::class, 'logoutEntrepot'])->name('logout');

    // Routes lorsque user est authentifié
    Route::middleware('auth:entrepot')->group(function () {
        Route::fallback([RedirectionController::class, 'redirectToDashboardEntrepot']);

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Routes concernant les utilisateurs de l'entrepot
    });
});

