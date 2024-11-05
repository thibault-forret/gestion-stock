<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RedirectionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;


// Redirige vers dashboard en cas d'erreur sur l'url
Route::fallback([RedirectionController::class, 'redirectToHome']);

// Permet de checker la langue défini par l'utilisateur
Route::middleware(['web', 'lang.toggle'])->group(function () {

    // Route pour changer la langue
    Route::get('lang/{locale}', function (string $locale) {
        if (! in_array($locale, ['en', 'fr'])) {
            return redirect()->back()->with('error', 'Langue non supportée');
        }
        
        app()->setLocale($locale);
        session()->put('locale', $locale);

        return redirect()->back(); 
    })->name('lang.switch');

    // Permet de rediriger vers la page d'accueil, pour séléctionner entrepot ou magasin
    Route::get('/', [IndexController::class, 'index'])->name('index');

    // Redifirige vers la page de connexion
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

            Route::get('/dashboard', [DashboardController::class, 'indexEntrepot'])->name('dashboard');

            // Routes concernant les utilisateurs de l'entrepot
        });
    });

    Route::get('/magasin', function () {
        return redirect()->route('magasin.login');
    });

    Route::prefix('magasin')->name('magasin.')->group(function () {
        Route::get('/login', function () {
            if (auth()->guard('magasin')->check()) {
                return redirect()->route('magasin.dashboard');
            }
            return (new AuthController)->showLoginFormMagasin();
        })->name('login');

        Route::post('/login', [AuthController::class, 'loginMagasin'])->name('login.post');

        Route::get('/logout', [AuthController::class, 'logoutMagasin'])->name('logout');

        Route::middleware('auth:magasin')->group(function () {
            Route::fallback([RedirectionController::class, 'redirectToDashboardMagasin']);

            Route::get('/dashboard', [DashboardController::class, 'indexMagasin'])->name('dashboard');

            // Routes concernant les utilisateurs du magasin
        });
    });
});



