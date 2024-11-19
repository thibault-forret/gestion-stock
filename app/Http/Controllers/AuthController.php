<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\AuthService;
use App\Models\WarehouseUser;
use App\Models\StoreUser;

class AuthController extends Controller
{
    /**
     * Gère la déconnexion de l'utilisateur en invalidant la session, régénérant le token CSRF
     * et en conservant la langue préférée de l'utilisateur.
     */
    private function logout(Request $request) 
    {
        $locale = $request->session()->get('locale', config('app.locale'));

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        app()->setLocale($locale);
        session()->put('locale', $locale);        
    }

    // ----------------------------------------------------------------------------------------------- //
    //                                            Entrepot                                             //
    // ----------------------------------------------------------------------------------------------- //

    public function showLoginFormWarehouse()
    {

        // Pour se créer un mot de passe hashé en attendant l'interface de création de compte
        // $password = Hash::make('thibault');
        // dd($password);
        
        if ($redirect = AuthService::verifyIfConnected('store')) {
            return $redirect;  // Si redirection, on redirige
        }

        return view('pages.login', ['page' => 'warehouse']);
    }

    public function loginWarehouse(Request $request)
    {
        if ($redirect = AuthService::verifyIfConnected('store')) {
            return $redirect;  // Si redirection, on redirige
        }

        // Validation des données
        $validated = $request->validate([
            'user_email' => ['required', 'email'],
            'user_password' => ['required'],
        ], [
            'user_email.required' => __('messages.validate.email_required'),
            'user_email.email' => __('messages.validate.email_valid'),
            'user_password.required' => __('messages.validate.email_required'),
        ]);
        
        $credentials = [
            'email' => $validated['user_email'],
            'password' => $validated['user_password'],
        ];

        if (Auth::guard('warehouse')->attempt($credentials)) {
            // L'utilistaeur est connecté avec succès
            $user = Auth::guard('warehouse')->user();

            // Vérifie si l'utilisateur est bien associé à un entrepôt
            if ($user->warehouseUser) {
                // Rediriger vers la page qu'il essayait d'accéder, sinon le dashboard
                return redirect()->intended(route('warehouse.dashboard'))->with('success', __('auth.success.login'));
            }

            // Déconnexion si l'utilisateur n'est pas associé à un entrepôt
            Auth::guard('warehouse')->logout();
            return back()->withErrors(['error' => __('auth.error.not_authorized')]);
        }
        else {
            // L'utilisateur n'est pas connecté
            return back()->withErrors([
                'error' => __('auth.error.failed'),
            ]);
        }
    }

    public function logoutWarehouse(Request $request)
    {
        Auth::guard('warehouse')->logout();

        $this->logout($request);

        return redirect()->route('index')->with('success', __('auth.success.logout'));
    }


    // ----------------------------------------------------------------------------------------------- //
    //                                            Magasin                                              //
    // ----------------------------------------------------------------------------------------------- //

    public function showLoginFormStore() {

        if ($redirect = AuthService::verifyIfConnected('warehouse')) {
            return $redirect;  // Si redirection, on redirige
        }

        return view('pages.login', ['page' => 'store']);
    }

    public function loginStore(Request $request)
    {
        if ($redirect = AuthService::verifyIfConnected('warehouse')) {
            return $redirect;  // Si redirection, on redirige
        }

        // Validation des données
        $validated = $request->validate([
            'user_email' => ['required', 'email'],
            'user_password' => ['required'],
        ], [
            'user_email.required' => __('messages.validate.email_required'),
            'user_email.email' => __('messages.validate.email_valid'),
            'user_password.required' => __('messages.validate.email_required'),
        ]);
        
        $credentials = [
            'email' => $validated['user_email'],
            'password' => $validated['user_password'],
        ];

        if (Auth::guard('store')->attempt($credentials)) {
            // L'utilistaeur est connecté avec succès

            $user = Auth::guard('store')->user();

            if($user->storeUser)
            {
                // Rediriger vers la page qu'il essayait d'accéder, sinon le dashboard
                return redirect()->intended(route('store.dashboard'))->with('success', __('auth.success.login'));
            }

            // Déconnexion si l'utilisateur n'est pas associé à un entrepôt
            Auth::guard('store')->logout();
            return back()->withErrors(['error' => __('auth.error.not_authorized')]);
        }
        else {
            // L'utilisateur n'est pas connecté
            return back()->withErrors([
                'error' => __('auth.error.failed'),
            ]);
        }
    }

    public function logoutStore(Request $request)
    {
        Auth::guard('store')->logout();

        $this->logout($request);

        return redirect()->route('index')->with('success', __('auth.success.logout'));
    }
}
