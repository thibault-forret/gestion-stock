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
        $user = auth()->user();
        
        $store = $user->storeUser->store;

        $warehouse = $store->warehouse;

        $orders = $store->orders;

        return view('pages.store.order.list', compact('orders', 'warehouse'));
    }

    public function detailOrder(int $order_id)
    {
        $order = Order::find($order_id);

        // Vérifier si la commande existe
        if(!$order)
        {
            return redirect()->route('store.order.list')->with('error', __('messages.order_not_found'));
        }

        return view('pages.store.order.detail', compact('order'));
    }

    public function removeOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ],
        [
            'order_id.required' => __('messages.order_not_found'),
            'order_id.integer' => __('messages.order_not_found'),
            'order_id.exists' => __('messages.order_not_found'),
        ]);

        // Récupérer la commande
        $order = Order::find($request->order_id);

        // Vérifier le statut de la commande
        if($order->order_status == Order::ORDER_STATUS_DELIVERED)
        {
            return redirect()->route('store.order.list')->with('error', __('messages.order_not_in_progress'));
        }

        // Remettre la quantité commandée dans le stock
        $order->orderLines->each(function ($orderLine) use ($order) {
            $stock = $order->store->warehouse->stock->where('product_id', $orderLine->product_id)->first();

            $stock->addQuantity($orderLine->quantity_ordered);
        });

        // Supprimer la commande
        $order->delete();

        return redirect()->route('store.order.list')->with('success', __('messages.order_removed'));
    }

    public function placeOrder(int $order_id)
    {
        $order = Order::find($order_id);

        if(!$order)
        {
            return redirect()->route('store.order.index')->with('error', __('messages.order_not_found'));
        }

        // Vérifier le statut de la commande
        if($order->order_status != Order::ORDER_STATUS_IN_PROGRESS)
        {
            return redirect()->route('store.order.index')->with('error', __('messages.order_not_in_progress'));
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

        // Supprimer les commandes ayant 0 produits commandés
        $store->orders->each(function ($order) {
            if(count($order->orderLines) == 0)
            {
                $order->delete();
            }
        });

        // Créer une nouvelle commande
        $order = $store->orders()->create([
            'user_id' => $user->id,
            'store_id' => $store->id,
            'order_date' => now(),
            'order_status' => Order::ORDER_STATUS_IN_PROGRESS,
        ]);

        // Rediriger vers la page de commande
        return redirect()->route('store.order.place', ['order_id' => $order->id])->with('success', __('messages.order_created'));
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
            'order_id.required' => __('messages.order_not_found'),
            'order_id.integer' => __('messages.order_not_found'),
            'order_id.exists' => __('messages.order_not_found'),
            'product_id.required' => __('messages.product_not_found'),
            'product_id.integer' => __('messages.product_not_found'),
            'product_id.exists' => __('messages.product_not_found'),
            'quantity.required' => __('messages.quantity_required'),
            'quantity.integer' => __('messages.quantity_integer'),
            'quantity.min' => __('messages.quantity_min'),
        ]);

        // Récupérer la commande et le produit
        $order = Order::find($request->order_id);

        // Vérifier le statut de la commande
        if($order->order_status != Order::ORDER_STATUS_IN_PROGRESS)
        {
            return redirect()->route('store.order.index')->with('error', __('messages.order_not_in_progress'));
        }

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

        return redirect()->route('store.order.place', ['order_id' => $request->order_id])->with('success', __('messages.product_added'));
    }

    public function removeProductFromOrder(Request $request)
    {
        // Vérification des données
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'product_id' => 'required|integer|exists:products,id',
        ],
        [
            'order_id.required' => __('messages.order_not_found'),
            'order_id.integer' => __('messages.order_not_found'),
            'order_id.exists' => __('messages.order_not_found'),
            'product_id.required' => __('messages.product_not_found'),
            'product_id.integer' => __('messages.product_not_found'),
            'product_id.exists' => __('messages.product_not_found'),
        ]);

        // Récupérer la commande et le produit
        $order = Order::find($request->order_id);

        // Vérifier le statut de la commande
        if($order->order_status != Order::ORDER_STATUS_IN_PROGRESS)
        {
            return redirect()->route('store.order.index')->with('error', __('messages.order_not_in_progress'));
        }

        $product = Product::find($request->product_id);

        // Vérifier si le produit est dans la commande
        $orderLine = $order->orderLines->where('product_id', $request->product_id)->first();

        if(!$orderLine)
        {
            return redirect()->back()->with('error', __('messages.product_not_in_order'));
        }

        // Récupérer la quantité
        $quantity = $orderLine->quantity_ordered;

        // Ajouter la quantité au stock
        $stock = $order->store->warehouse->stock->where('product_id', $request->product_id)->first();

        $stock->addQuantity($quantity);

        // Supprimer la ligne de commande
        $orderLine->delete();

        return redirect()->back()->with('success', __('messages.product_removed'));
    }

    public function removeQuantityProductFromOrder(Request $request)
    {
        // Vérification des données
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ],
        [
            'order_id.required' => __('messages.order_not_found'),
            'order_id.integer' => __('messages.order_not_found'),
            'order_id.exists' => __('messages.order_not_found'),
            'product_id.required' => __('messages.product_not_found'),
            'product_id.integer' => __('messages.product_not_found'),
            'product_id.exists' => __('messages.product_not_found'),
        ]);

        // Récupérer la commande et le produit
        $order = Order::find($request->order_id);

        // Vérifier le statut de la commande
        if($order->order_status != Order::ORDER_STATUS_IN_PROGRESS)
        {
            return redirect()->route('store.order.index')->with('error', __('messages.order_not_in_progress'));
        }

        $product = Product::find($request->product_id);

        // Vérifier si le produit est dans la commande
        $orderLine = $order->orderLines->where('product_id', $request->product_id)->first();

        if(!$orderLine)
        {
            return redirect()->back()->with('error', __('messages.product_not_in_order'));
        }

        $quantity = $request->quantity;        

        // Vérifier si la quantité n'excède pas la quantité commandée
        if($quantity > $orderLine->quantity_ordered)
        {
            return redirect()->back()->with('error', __('messages.quantity_exceed'));
        }

        // Enlever la quantité de la ligne de commande
        $orderLine->removeQuantity($quantity);

        if($orderLine->quantity_ordered == 0)
        {
            // Supprimer la ligne de commande
            $orderLine->delete();
        }

        // Ajouter la quantité au stock
        $stock = $order->store->warehouse->stock->where('product_id', $request->product_id)->first();

        $stock->addQuantity($quantity);

        return redirect()->back()->with('success', __('messages.remove_quantity_success'));
    }

    public function recapOrder(int $order_id)
    {
        // Vérifier si la commande existe
        $order = Order::find($order_id);

        if(!$order)
        {
            return redirect()->route('store.order.index')->with('error', __('messages.order_not_found'));
        }

        // Vérifier le statut de la commande
        if($order->order_status != Order::ORDER_STATUS_IN_PROGRESS)
        {
            return redirect()->route('store.order.index')->with('error', __('messages.order_not_in_progress'));
        }

        // Vérifier si la commande n'est pas vide
        if(count($order->orderLines) == 0)
        {
            return redirect()->route('store.order.place', ['order_id' => $order->id])->with('error', __('messages.order_empty'));
        } 
        
        return view('pages.store.order.recap_order', compact('order'));
    }

    public function confirmOrder(Request $request)
    {
        // Vérification des données
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ],
        [
            'order_id.required' => __('messages.order_not_found'),
            'order_id.integer' => __('messages.order_not_found'),
            'order_id.exists' => __('messages.order_not_found'),
        ]);

        // Récupérer la commande
        $order = Order::find($request->order_id);

        // Vérifier le statut de la commande
        if($order->order_status != Order::ORDER_STATUS_IN_PROGRESS)
        {
            return redirect()->route('store.order.index')->with('error', __('messages.order_not_in_progress'));
        }

        // Vérifier si la commande n'est pas vide
        if(count($order->orderLines) == 0)
        {
            return redirect()->route('store.order.place', ['order_id' => $order->id])->with('error', __('messages.order_empty'));
        }

        // Changer le statut de la commande
        $order->order_status = Order::ORDER_STATUS_PENDING;
        $order->save();

        return redirect()->route('store.order.index')->with('success', __('messages.order_confirmed'));
    }

    // -----------------------------------------------------------------------------------------------
    //                                  WAREHOUSE
    // -----------------------------------------------------------------------------------------------

    public function listOrdersWarehouse()
    {
        $user = auth()->user();
        
        $warehouse = $user->warehouseUser->warehouse;

        $orders = $warehouse->stores->flatMap(function ($store) {
            return $store->orders;
        });

        return view('pages.warehouse.order.list', compact('orders', 'warehouse'));
    }

    public function detailOrderWarehouse(int $order_id)
    {
        $order = Order::find($order_id);

        // Vérifier si la commande existe
        if(!$order)
        {
            return redirect()->route('warehouse.order.list')->with('error', __('messages.order_not_found'));
        }

        return view('pages.warehouse.order.detail', compact('order'));
    }

    public function deliverOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ],
        [
            'order_id.required' => __('messages.order_not_found'),
            'order_id.integer' => __('messages.order_not_found'),
            'order_id.exists' => __('messages.order_not_found'),        
        ]);

        // Récupérer la commande
        $order = Order::find($request->order_id);

        // Vérifier le statut de la commande
        if($order->order_status != Order::ORDER_STATUS_PENDING)
        {
            return redirect()->route('warehouse.order.list')->with('error', __('messages.order_not_pending'));
        }

        // Changer le statut de la commande
        $order->order_status = Order::ORDER_STATUS_DELIVERED;
        $order->save();

        return redirect()->route('warehouse.order.list')->with('success', __('messages.order_delivered'));
    }

    public function refuseOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ],
        [
            'order_id.required' => __('messages.order_not_found'),
            'order_id.integer' => __('messages.order_not_found'),
            'order_id.exists' => __('messages.order_not_found'),
        ]);

        // Récupérer la commande
        $order = Order::find($request->order_id);

        // Vérifier le statut de la commande
        if($order->order_status != Order::ORDER_STATUS_PENDING)
        {
            return redirect()->route('warehouse.order.list')->with('error', __('messages.order_not_pending'));
        }

        // Remettre la quantité commandée dans le stock
        $order->orderLines->each(function ($orderLine) use ($order) {
            $stock = $order->store->warehouse->stock->where('product_id', $orderLine->product_id)->first();

            $stock->addQuantity($orderLine->quantity_ordered);
        });

        // Changer le statut de la commande
        $order->order_status = Order::ORDER_STATUS_REFUSED;
        $order->save();

        return redirect()->route('warehouse.order.list')->with('success', __('messages.order_refused'));
    }

    public function removeOrderWarehouse(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ],
        [
            'order_id.required' => __('messages.order_not_found'),
            'order_id.integer' => __('messages.order_not_found'),
            'order_id.exists' => __('messages.order_not_found'),
        ]);

        // Récupérer la commande
        $order = Order::find($request->order_id);

        // Vérifier le statut de la commande
        if($order->order_status == Order::ORDER_STATUS_DELIVERED)
        {
            return redirect()->route('warehouse.order.list')->with('error', __('messages.order_not_in_progress'));
        }

        // Remettre la quantité commandée dans le stock
        $order->orderLines->each(function ($orderLine) use ($order) {
            $stock = $order->store->warehouse->stock->where('product_id', $orderLine->product_id)->first();

            $stock->addQuantity($orderLine->quantity_ordered);
        });

        // Supprimer la commande
        $order->delete();

        return redirect()->route('warehouse.order.list')->with('success', __('messages.order_removed'));
    }
}
