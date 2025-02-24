<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function indexWarehouse()
    {
        $user = auth()->user();
        $warehouse = $user->warehouseUser->warehouse;

        // Récupération des stocks avec les produits associés
        $stocks = DB::table('stocks')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->where('warehouse_id', $warehouse->id)
            ->select(
                'products.product_name as produit',
                'stocks.quantity_available as quantite',
                'stocks.alert_threshold as seuil_alerte',
                'stocks.restock_threshold as restock_threshold'
            )
            ->orderBy('quantite', 'asc')
            ->get()
            ->toArray();

        return view('pages.warehouse.dashboard', compact('stocks'));
    }

    public function indexStore()
    {
        return view('pages.store.dashboard');
    }
}
