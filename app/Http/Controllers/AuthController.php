<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    private function verifyIfConnected(string $guard)
    {
        if (Auth::guard($guard)->check()) {
            // Redirige vers le tableau de bord avec un message d'erreur si l'utilisateur est déjà connecté
            return redirect()->route($guard.'.dashboard')->with('error', __('auth.error.logout_first_store'));
        }
    }

    // ----------------------------------------------------------------------------------------------- //
    //                                            Entrepot                                             //
    // ----------------------------------------------------------------------------------------------- //

    public function showLoginFormWarehouse()
    {

        // Pour se créer un mot de passe hashé en attendant l'interface de création de compte
        // $password = Hash::make('thibault');
        // dd($password);

        $this->verifyIfConnected('store');

        return view('pages.warehouse.login');
    }

    public function loginWarehouse(Request $request)
    {
        $this->verifyIfConnected('store');

        // Validation des données
        // Faire les messages de traduction
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ], [
            'username.required' => 'Le nom d’utilisateur est requis.',
            'username.string' => 'Le nom d’utilisateur doit être une chaîne de caractères.',
            'password.required' => 'Le mot de passe est requis.',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');
        
        $credentials = [
            'username' => $username,
            'password' => $password,
        ];

        if (Auth::guard('warehouse')->attempt($credentials)) {
            // L'utilistaeur est connecté avec succès
            $user = Auth::guard('warehouse')->user();

            // Rediriger vers la page qu'il essayait d'accéder, sinon le dashboard
            return redirect()->intended(route('warehouse.dashboard'))->with('success', __('auth.success.login'));
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

        $this->verifyIfConnected('warehouse');

        return view('pages.store.login');
    }

    public function loginStore(Request $request)
    {
        $this->verifyIfConnected('warehouse');

        // Faire les messages de traduction
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ], [
            'username.required' => 'Le nom d’utilisateur est requis.',
            'username.string' => 'Le nom d’utilisateur doit être une chaîne de caractères.',
            'password.required' => 'Le mot de passe est requis.',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');
        
        $credentials = [
            'username' => $username,
            'password' => $password,
        ];

        if (Auth::guard('store')->attempt($credentials)) {
            // L'utilistaeur est connecté avec succès

            $user = Auth::guard('store')->user();

            // Rediriger vers la page qu'il essayait d'accéder, sinon le dashboard
            return redirect()->intended(route('store.dashboard'))->with('success', __('auth.success.login'));
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
