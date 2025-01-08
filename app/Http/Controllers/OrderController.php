<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

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

        // Rediriger vers la page de commande
        return redirect()->route('store.order.place', ['order_id' => $order->id]);
    }

    public function addProductToOrder(Request $request)
    {
        // Vérification des données
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ],
        [
            'order_id.required' => __('messages.order_id_required'),
            // Faire les messages
        ]);

        // Récupérer la commande et le produit
        $order = Order::find($request->order_id);

        $product = Product::find($request->product_id);

        // Vérifier si la quantité n'excède pas le stock
        $stock = $order->store->warehouse->stock->where('product_id', $request->product_id)->first();

        $quantity = $request->quantity;

        if($quantity > $stock->quantity_available)
        {
            return redirect()->back()->with('error', __('messages.quantity_exceed_stock'));
        }

        // Vérifier si le produit n'est pas déjà dans la commande
        if($order->orderLines->where('product_id', $request->product_id)->first())
        {
            // Mettre à jour la quantité
            $orderLine = $order->orderLines->where('product_id', $request->product_id)->first();
            
            $orderLine->addQuantity($quantity);
        }
        else
        {
            // Ajouter le produit à la commande
            $order->orderLines()->create([
                'product_id' => $request->product_id,
                'quantity_ordered' => $quantity,
                'unit_price' => $product->reference_price,
            ]);
        }

        // Retirer la quantité du stock (réserve la quantité pour cette commande)
        $stock->removeQuantity($quantity);

        return redirect()->route('store.order.place', ['order_id' => $request->order_id]);
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
