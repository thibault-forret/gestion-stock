<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;

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

    public function editProduct(int $stock_id) 
    {
        $user = auth()->user();

        // Vérifier si le stock appartient à l'entrepôt de l'utilisateur
        $stock = $user->warehouseUser->warehouse->stock->where('id', $stock_id)->first();

        if (!$stock) {
            return redirect()->route('warehouse.stock.index')->with('error', __('messages.stock_not_found'));
        }

        $product = $stock->product;

        return view('pages.warehouse.stock.edit_product', compact('stock', 'product'));
    }

    public function editProductSubmit(Request $request) 
    {
        // Validation des données
        $request->validate([
            'stock_id' => 'required|integer|exists:stocks,id', 
            'alert_threshold' => 'required|integer|min:1|gte:restock_threshold',
            'restock_threshold' => 'required|integer|min:0',
            'auto_restock_quantity' => 'required|integer|gte:restock_threshold',
        ],
        [
            'stock_id.required' => __('messages.validate.stock_id_required'),
            'stock_id.integer' => __('messages.validate.stock_id_integer'),
            'stock_id.exists' => __('messages.stock_not_found'),
            'restock_threshold.required' => __('messages.validate.restock_threshold_required'),
            'restock_threshold.integer' => __('messages.validate.restock_threshold_integer'),
            'restock_threshold.min' => __('messages.validate.restock_threshold_min'),
            'alert_threshold.required' => __('messages.validate.alert_threshold_required'),
            'alert_threshold.integer' => __('messages.validate.alert_threshold_integer'),
            'alert_threshold.min' => __('messages.validate.alert_threshold_min'),
            'alert_threshold.gte' => __('messages.validate.alert_threshold_gte'),
            'auto_restock_quantity.required' => __('messages.validate.auto_restock_quantity_required'),
            'auto_restock_quantity.integer' => __('messages.validate.auto_restock_quantity_integer'),
            'auto_restock_quantity.min' => __('messages.validate.auto_restock_quantity_min'),
            'auto_restock_quantity.gte' => __('messages.validate.auto_restock_quantity_gte'),
        ]);

        // Récupérer le stock
        $stock = Stock::find($request->stock_id);

        $request->merge($request->except('stock_id'));

        // Mettre à jour le stock
        $success = $stock->update($request->all());

        if ($success) {
            return redirect()->route('warehouse.stock.index')->with('success', __('messages.action_success'));
        }
        else {
            return redirect()->route('warehouse.stock.index')->with('error', __('messages.action_failed'));
        }
    }

    public function supplyProduct(int $stock_id) 
    {
        $user = auth()->user();

        // Vérifier si le stock appartient à l'entrepôt de l'utilisateur
        $stock = $user->warehouseUser->warehouse->stock->where('id', $stock_id)->first();

        if (!$stock) {
            return redirect()->route('warehouse.stock.index')->with('error', __('messages.stock_not_found'));
        }

        $product = $stock->product;

        return view('pages.warehouse.stock.supply_product', compact('stock', 'product'));
    }

    public function supplyProductSubmit(Request $request) 
    {

    }

    public function removeProduct() 
    {

    }

    public function removeProductSubmit() 
    {

    }
}
