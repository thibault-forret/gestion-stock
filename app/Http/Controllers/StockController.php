<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index () 
    {

        $user = auth()->user();

        // Récupérer l'entrepôt de l'utilisateur
        $warehouse = $user->warehouseUser->warehouse;

        // Récupérer les produits déjà dans l'entrepôt
        $products = $warehouse->stock->map(function ($stock) {
            return $stock->product;
        });

        return view('pages.warehouse.stock.stock_list', compact('products', 'warehouse'));
    }

    public function editProduct() 
    {

    }

    public function editProductSubmit() 
    {

    }

    public function supplyProduct() 
    {

    }

    public function supplyProductSubmit() 
    {

    }

    public function removeProduct() 
    {

    }

    public function removeProductSubmit() 
    {

    }
}
