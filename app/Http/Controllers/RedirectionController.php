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

    public function redirectToDashboardWarehouse()
    {
        return redirect()->route('warehouse.dashboard');
    }

    public function redirectToDashboardStore()
    {
        return redirect()->route('store.dashboard');
    }
}
