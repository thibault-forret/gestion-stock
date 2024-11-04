<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function indexEntrepot() 
    {
        return view('pages.entrepot.dashboard');
    }

    public function indexMagasin()
    {
        return view('pages.magasin.dashboard');
    }
}
