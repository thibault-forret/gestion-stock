<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    // -----------------------------------------------------------------------------------------------
    //                                            Entrepot
    // -----------------------------------------------------------------------------------------------

    public function showLoginFormWarehouse()
    {

        // Pour se créer un mot de passe hashé en attendant l'interface de création de compte
        // $password = Hash::make('thibault');
        // dd($password);

        if (Auth::guard('store')->check()) {
            return redirect()->route('store.dashboard')->with('error', __('auth.error.logout_first_store'));
        }

        return view('pages.warehouse.login');
    }

    public function loginWarehouse(Request $request)
    {
        // Vérification des données

        if (Auth::guard('store')->check()) {
            return redirect('/magasin/dashboard')->with('error', __('auth.error.logout_first_store'));
        }

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

        $request->session()->invalidate();

        return redirect()->route('index')->with('success', __('auth.success.logout'));
    }


    // -----------------------------------------------------------------------------------------------
    //                                            Magasin
    // -----------------------------------------------------------------------------------------------

    public function showLoginFormStore() {

        if (Auth::guard('warehouse')->check()) {
            return redirect()->route('warehouse.dashboard')->with('error', __('auth.error.logout_first_warehouse'));
        }

        return view('pages.store.login');
    }

    public function loginStore(Request $request)
    {
        if (Auth::guard('warehouse')->check()) {
            return redirect()->route('warehouse.dashboard')->with('error', __('auth.error.logout_first_warehouse'));
        }

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

        $request->session()->invalidate();

        return redirect()->route('index')->with('success', __('auth.success.logout'));
    }
}
