<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        // Ajouter une page au début pour choisir si on est un entrepot ou un magasin, cf. le login de l'ent de l'upjv pour exemple

        if (Auth::guard('entrepot')->check()) {
            return redirect('/entrepot/dashboard')->with('error', 'Veuillez d\'abord vous déconnecter de la section entrepot.');
        }
    
        if (Auth::guard('magasin')->check()) {
            return redirect('/magasin/dashboard')->with('error', 'Veuillez d\'abord vous déconnecter de la section magasin.');
        }

        return view('pages.accueil');
    }
}
