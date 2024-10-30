<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectionController extends Controller
{
    // Redirige vers le dashboard
    public function redirectToDashboardEntrepot()
    {
        return redirect()->route('entrepot.dashboard');
    }
}
