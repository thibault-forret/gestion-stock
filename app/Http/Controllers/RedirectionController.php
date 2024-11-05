<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RedirectionController extends Controller
{
    public function redirectToHome()
    {
        return redirect()->route('index');
    }

    public function redirectToDashboardEntrepot()
    {
        return redirect()->route('warehouse.dashboard');
    }

    public function redirectToDashboardMagasin()
    {
        return redirect()->route('store.dashboard');
    }
}
