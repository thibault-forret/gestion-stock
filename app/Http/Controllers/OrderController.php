<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return view('pages.store.order.index');
    }

    public function listOrders()
    {
        return view('pages.store.order.list');
    }

    public function placeOrder(int $order_id)
    {
        $order = Order::find($order_id);

        if(!$order)
        {
            return redirect()->route('store.order.index')->with('error', __('messages.order_not_found'));
        }

        $user = auth()->user();

        // Récupérer les produits de l'entrepot
        $warehouse = $user->storeUser->store->warehouse;

        // Récupérer les produits de l'entrepôt
        $products = $warehouse->stock->map(function ($stock) {
            return $stock->product;
        });

        return view('pages.store.order.place_order', compact('order', 'products', 'warehouse'));
    }

    public function placeNewOrder() 
    {
        $user = auth()->user();

        $store = $user->storeUser->store;

        // Créer une nouvelle commande
        $order = $store->orders()->create([
            'user_id' => $user->id,
            'store_id' => $store->id,
            'order_date' => now(),
            'order_status' => Order::ORDER_STATUS_IN_PROGRESS,
        ]);

        return redirect()->route('store.order.place', ['order_id' => $order->id]);
    }

    public function placeOrderConfirm(Request $request)
    {
        // Stocker les données dans le panier

        return redirect()->route('store.order.recap');
    }

    public function recapOrder()
    {
        // Récupérer contenu du panier

        return view('pages.store.order.recap_order');
    }

    public function confirmOrder(Request $request)
    {
        // Vérification des données

        // Envoyer mail avec récapitulatif commande + facture ?

        return redirect()->route('store.order.index')->with('success', __('messages.confirm_order'));
    }

}

// Order process :

// Place order
//    -> Add content to cart
//    -> Confirm what he has added
//    -> Send to recap
// Recap order
//   -> Show what he has added
//   -> Confirm order
// Confirm order

// We need to store the cart in the database, maybe new database ?
// How to store data in the cart ?
