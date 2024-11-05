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
            return redirect()->back()->with('error', __('messages.langage_not_supported'));
        }
        
        app()->setLocale($locale);
        session()->put('locale', $locale);

        return redirect()->back(); 
    })->name('lang.switch');

    // Permet de rediriger vers la page d'accueil, pour séléctionner entrepot ou magasin
    Route::get('/', [IndexController::class, 'index'])->name('index');

    // Redifirige vers la page de connexion
    Route::get('/warehouse', function () {
        return redirect()->route('warehouse.login');
    });

    Route::prefix('warehouse')->name('warehouse.')->group(function () {
        Route::get('/login', function () {
            if (auth()->guard('warehouse')->check()) {
                return redirect()->route('warehouse.dashboard');
            }
            return (new AuthController)->showLoginFormWarehouse();
        })->name('login');

        Route::post('/login', [AuthController::class, 'loginWarehouse'])->name('login.post');

        Route::get('/logout', [AuthController::class, 'logoutWarehouse'])->name('logout');

        // Routes lorsque user est authentifié
        Route::middleware('auth:warehouse')->group(function () {
            Route::fallback([RedirectionController::class, 'redirectToDashboardWarehouse']);

            Route::get('/dashboard', [DashboardController::class, 'indexWarehouse'])->name('dashboard');

            // Routes concernant les utilisateurs de l'entrepot
        });
    });

    Route::get('/store', function () {
        return redirect()->route('store.login');
    });

    Route::prefix('store')->name('store.')->group(function () {
        Route::get('/login', function () {
            if (auth()->guard('store')->check()) {
                return redirect()->route('store.dashboard');
            }
            return (new AuthController)->showLoginFormStore();
        })->name('login');

        Route::post('/login', [AuthController::class, 'loginStore'])->name('login.post');

        Route::get('/logout', [AuthController::class, 'logoutStore'])->name('logout');

        Route::middleware('auth:store')->group(function () {
            Route::fallback([RedirectionController::class, 'redirectToDashboardStore']);

            Route::get('/dashboard', [DashboardController::class, 'indexStore'])->name('dashboard');

            // Routes concernant les utilisateurs du magasin
        });
    });
});



