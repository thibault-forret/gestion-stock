<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RedirectionController;

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

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');