<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectionController extends Controller
{
    // Redirige vers le dashboard
    public function redirectToDashboard()
    {
        return redirect()->route('dashboard');
    }
}
