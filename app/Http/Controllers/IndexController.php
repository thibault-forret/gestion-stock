<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthService;


class IndexController extends Controller
{
    public function index()
    {
        // Ajouter une page au début pour choisir si on est un entrepot ou un magasin, cf. le login de l'ent de l'upjv pour exemple

        if ($redirect = AuthService::verifyIfConnected('warehouse')) {
            return $redirect;  // Si redirection, on redirige
        }
        
        if ($redirect = AuthService::verifyIfConnected('store')) {
            return $redirect;  // Si redirection, on redirige
        }

        return view('pages.home');
    }
}
