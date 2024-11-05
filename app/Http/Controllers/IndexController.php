<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        // Ajouter une page au début pour choisir si on est un entrepot ou un magasin, cf. le login de l'ent de l'upjv pour exemple

        if (Auth::guard('warehouse')->check()) {
            return redirect()->route('warehouse.dashboard')->with('error', __('auth.error.logout_first_warehouse'));
        }
    
        if (Auth::guard('store')->check()) {
            return redirect()->route('store.dashboard')->with('error', __('auth.error.logout_first_store'));
        }

        return view('pages.home');
    }
}
