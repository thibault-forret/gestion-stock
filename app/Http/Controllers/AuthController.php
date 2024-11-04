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

    public function showLoginFormEntrepot()
    {

        // Pour se créer un mot de passe hashé en attendant l'interface de création de compte
        // $password = Hash::make('thibault');
        // dd($password);

        if (Auth::guard('magasin')->check()) {
            return redirect('/magasin/dashboard')->with('error', 'Veuillez d\'abord vous déconnecter de la section magasin.');
        }

        return view('pages.entrepot.login');
    }

    public function loginEntrepot(Request $request)
    {
        // Vérification des données

        if (Auth::guard('magasin')->check()) {
            return redirect('/magasin/dashboard')->with('error', 'Veuillez d\'abord vous déconnecter de la section magasin.');
        }

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

        if (Auth::guard('entrepot')->attempt($credentials)) {
            // L'utilistaeur est connecté avec succès

            $admin = Auth::guard('entrepot')->user();

            // Rediriger vers la page qu'il essayait d'accéder, sinon le dashboard
            return redirect()->intended(route('entrepot.dashboard'))->with('success', 'Vous êtes connecté.');
        }
        else {
            // L'utilisateur n'est pas connecté
            return back()->withErrors([
                'error' => 'Les informations de connexion sont incorrectes.',
            ]);
        }
    }

    public function logoutEntrepot(Request $request)
    {
        Auth::guard('entrepot')->logout();

        $request->session()->invalidate();

        return redirect()->route('index')->with('success', 'Vous avez été déconnecté.');
    }


    // -----------------------------------------------------------------------------------------------
    //                                            Magasin
    // -----------------------------------------------------------------------------------------------

    public function showLoginFormMagasin() {

        if (Auth::guard('entrepot')->check()) {
            return redirect('/entrepot/dashboard')->with('error', 'Veuillez d\'abord vous déconnecter de la section entrepot.');
        }

        return view('pages.magasin.login');
    }

    public function loginMagasin(Request $request)
    {
        if (Auth::guard('entrepot')->check()) {
            return redirect('/entrepot/dashboard')->with('error', 'Veuillez d\'abord vous déconnecter de la section entrepot.');
        }

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

        if (Auth::guard('magasin')->attempt($credentials)) {
            // L'utilistaeur est connecté avec succès

            $admin = Auth::guard('magasin')->user();

            // Rediriger vers la page qu'il essayait d'accéder, sinon le dashboard
            return redirect()->intended(route('magasin.dashboard'))->with('success', 'Vous êtes connecté.');
        }
        else {
            // L'utilisateur n'est pas connecté
            return back()->withErrors([
                'error' => 'Les informations de connexion sont incorrectes.',
            ]);
        }
    }

    public function logoutMagasin(Request $request)
    {
        Auth::guard('magasin')->logout();

        $request->session()->invalidate();

        return redirect()->route('index')->with('success', 'Vous avez été déconnecté.');
    }
}
