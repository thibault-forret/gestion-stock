<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function indexWarehouse() 
    {
        return view('pages.warehouse.dashboard');
    }

    public function indexStore()
    {
        return view('pages.store.dashboard');
    }
}
