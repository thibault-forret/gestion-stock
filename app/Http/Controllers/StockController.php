<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Invoice;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supply;
use App\Models\SupplyLine;
use App\Models\Order;

class StockController extends Controller
{
    public function index ()
    {
        return view('pages.warehouse.stock.index');
    }

    public function stockList() 
    {
        $user = auth()->user();

        // Récupérer l'entrepôt de l'utilisateur
        $warehouse = $user->warehouseUser->warehouse;

        // Récupérer les produits déjà dans l'entrepôt
        $products = $warehouse->stock->map(function ($stock) {
            return $stock->product;
        });

        // Récupérer toutes les catégories et tous les fournisseurs
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('pages.warehouse.stock.stock_list', compact('products', 'warehouse', 'categories', 'suppliers'));
    }

    public function searchStock(Request $request)
    {
        $request->validate([
            'search' => 'required|int',
        ], [
            'search.required' => __('messages.validate.search_required'),
            'search.int' => __('messages.validate.search_integer'),
        ]);

        $stock = Stock::where('product_id', $request->input('search'))->first();

        if (!$stock) {
            return redirect()->route('warehouse.stock.list')->with('error', __('messages.product_not_found'));
        }

        return redirect()->route('warehouse.stock.product.info', ['product_id' => $stock->product_id]);
    }

    public function stockMovementList()
    {
        $user = auth()->user();

        // Récupérer l'entrepôt de l'utilisateur
        $warehouse = $user->warehouseUser->warehouse;

        // Récupérer les mouvements de stock de l'entrepôt
        $stockMovements = $warehouse->stockMovements()->orderBy('created_at', 'desc')->get();

        return view('pages.warehouse.stock.movement_list', compact('stockMovements'));
    }

    public function infoProduct(int $product_id)
    {
        $user = auth()->user();

        // Vérifier si le stock appartient à l'entrepôt de l'utilisateur
        $stock = $user->warehouseUser->warehouse->stock->where('product_id', $product_id)->first();

        if (!$stock) {
            return redirect()->route('warehouse.stock.index')->with('error', __('messages.stock_not_found'));
        }

        $product = $stock->product;

        return view('pages.warehouse.stock.info_product', compact('stock', 'product'));
    }

    public function editProduct(int $product_id) 
    {
        $user = auth()->user();

        // Vérifier si le stock appartient à l'entrepôt de l'utilisateur
        $stock = $user->warehouseUser->warehouse->stock->where('product_id', $product_id)->first();

        if (!$stock) {
            return redirect()->route('warehouse.stock.index')->with('error', __('messages.stock_not_found'));
        }

        $product = $stock->product;

        $warehouse = $stock->warehouse;

        return view('pages.warehouse.stock.edit_product', compact('stock', 'warehouse', 'product'));
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

        $warehouse = $stock->warehouse;

        // Vérifier si le seuil d'alerte, le seuil de réapprovisionnement et l'auto réappro sont inférieurs à la capacité de l'entrepôt
        if ($request->input('alert_threshold') > $warehouse->capacity || $request->input('restock_threshold') > $warehouse->capacity || $request->input('auto_restock_quantity') > $warehouse->capacity) {
            return redirect()->back()->withErrors(__('messages.validate.thresholds_exceeds_capacity'))->withInput();
        } 

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

    public function supplyProduct(int $product_id) 
    {
        $user = auth()->user();

        // Vérifier si le stock appartient à l'entrepôt de l'utilisateur
        $stock = $user->warehouseUser->warehouse->stock->where('product_id', $product_id)->first();

        if (!$stock) {
            return redirect()->route('warehouse.stock.index')->with('error', __('messages.stock_not_found'));
        }

        $product = $stock->product;

        $warehouse = $stock->warehouse;

        return view('pages.warehouse.stock.supply_product', compact('stock', 'product', 'warehouse'));
    }

    public function supplyProductSubmit(Request $request) 
    {
        $request->validate([
            'stock_id' => 'required|integer|exists:stocks,id',
            'quantity' => 'required|integer|min:1',
        ],
        [
            'stock_id.required' => __('messages.validate.stock_id_required'),
            'stock_id.integer' => __('messages.validate.stock_id_integer'),
            'stock_id.exists' => __('messages.stock_not_found'),
            'quantity.required' => __('messages.validate.quantity_required'),
            'quantity.integer' => __('messages.validate.quantity_integer'),
            'quantity.min' => __('messages.validate.quantity_min'),
        ]);

        // Vérifier si la quantité est inférieure à la capacité maximale
        $quantity = $request->input('quantity');

        $user = auth()->user();

        $warehouse = $user->warehouseUser->warehouse;

        // Vérifier si la quantité dépasse la capacité de l'entrepôt
        if (($quantity + $warehouse->stock->sum('quantity_available')) > $warehouse->capacity) {
            return redirect()->back()->withErrors(__('messages.validate.quantity_exceeds_capacity'))->withInput();
        }

        // Récupérer le stock
        $stock = Stock::find($request->stock_id);

        // Mettre à jour la quantité disponible
        $stock->addQuantity($quantity);

        $product = $stock->product;

        $supplier = $product->supplyLines->first()->supply->supplier;

        // Créer un mouvement de stock, un approvisionnement, une ligne d'approvisionnement et une facture
        $success = $this->createSupplyForProduct($product, $supplier, $user, $warehouse, $quantity);

        if ($success) {
            return redirect()->route('warehouse.stock.index')->with('success', __('messages.action_success'));
        }
        else {
            return redirect()->route('warehouse.stock.index')->with('error', __('messages.action_failed'));
        }
    }

    public function removeProduct(int $product_id) 
    {
        $user = auth()->user();

        // Vérifier si le stock appartient à l'entrepôt de l'utilisateur
        $stock = $user->warehouseUser->warehouse->stock->where('product_id', $product_id)->first();

        if (!$stock) {
            return redirect()->route('warehouse.stock.index')->with('error', __('messages.stock_not_found'));
        }

        $product = $stock->product;

        $warehouse = $stock->warehouse;

        return view('pages.warehouse.stock.remove_product', compact('stock', 'product', 'warehouse'));
    }

    public function removeQuantityProductSubmit(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|integer|exists:stocks,id',
            'quantity' => 'required|integer|min:1',
        ],
        [
            'stock_id.required' => __('messages.validate.stock_id_required'),
            'stock_id.integer' => __('messages.validate.stock_id_integer'),
            'stock_id.exists' => __('messages.stock_not_found'),
            'quantity.required' => __('messages.validate.quantity_required'),
            'quantity.integer' => __('messages.validate.quantity_integer'),
            'quantity.min' => __('messages.validate.quantity_min'),
        ]);

        // Vérifier si la quantité est inférieure à la capacité maximale
        $quantity = $request->input('quantity');

        // Récupérer le stock
        $stock = Stock::find($request->stock_id);

        // Vérifier si la quantité ne sera pas négative
        if ($stock->quantity_available < $quantity) {
            return redirect()->back()->withErrors(__('messages.validate.quantity_to_high'))->withInput();
        }

        // Mettre à jour la quantité disponible
        $stock->removeQuantity($quantity);

        // Ajouter toutes les dépendances nécessaires, stock_movements, etc.
        $success = $this->removeQuantityProductFromStock($stock, $quantity);

        if ($success) {
            return redirect()->route('warehouse.stock.index')->with('success', __('messages.action_success'));
        }
        else {
            return redirect()->route('warehouse.stock.index')->with('error', __('messages.action_failed'));
        }
    }

    public function removeProductSubmit(Request $request) 
    {
        $request->validate([
            'stock_id' => 'required|integer|exists:stocks,id',
        ],
        [
            'stock_id.required' => __('messages.validate.stock_id_required'),
            'stock_id.integer' => __('messages.validate.stock_id_integer'),
            'stock_id.exists' => __('messages.stock_not_found'),
        ]);

        // Récupérer le stock
        $stock = Stock::find($request->stock_id);

        $success = $this->removeQuantityProductFromStock($stock, $stock->quantity_available);

        if ($success){
            // Supprimer le stock
            $stock->delete();  

            return redirect()->route('warehouse.stock.index')->with('success', __('messages.action_success'));
        }
        else {
            return redirect()->route('warehouse.stock.index')->with('error', __('messages.action_failed'));
        }
    }

    // -----------------------------------------------
    //          Gestion des approvisionnements
    // -----------------------------------------------

    public function indexSupply()
    {
        return view('pages.warehouse.stock.supply.index');
    }

    public function listSupplies()
    {
        $user = auth()->user();

        $warehouse = $user->warehouseUser->warehouse;

        $supplies = $warehouse->supplies->sortByDesc('created_at');

        return view('pages.warehouse.stock.supply.list', compact('supplies', 'warehouse'));
    }

    public function removeSupply(Request $request)
    {
        $request->validate([
            'supply_id' => 'required|integer|exists:supplies,id',
        ],
        [
            'supply_id.required' => __('messages.supply_not_found'),
            'supply_id.integer' => __('messages.supply_not_found'),
            'supply_id.exists' => __('messages.supply_not_found'),
        ]);

        // Récupérer la commande
        $supply = Supply::find($request->supply_id);

        // Vérifier le statut de la commande
        if($supply->supply_status == Supply::SUPPLY_STATUS_DELIVERED)
        {
            return redirect()->route('warehouse.stock.supply.list')->with('error', __('messages.supply_not_in_progress'));
        }

        // Remettre la quantité commandée dans le stock
        $supply->supplyLines->each(function ($supplyLines) use ($supply) {
            $stock = $supply->warehouse->stock->where('product_id', $supplyLines->product_id)->first();

            $stock->addQuantity($supplyLines->quantity_supplied);
        });

        // Supprimer la commande
        $supply->delete();

        return redirect()->route('warehouse.stock.supply.list')->with('success', __('messages.supply_removed'));
    }

    public function detailSupply(int $supply_id)
    {
        $supply = Supply::find($supply_id);

        // Vérifier si la commande existe
        if(!$supply)
        {
            return redirect()->route('warehouse.stock.supply.list')->with('error', __('messages.supply_not_found'));
        }

        // Vérifier si la commande n'est pas vide
        if(count($supply->supplyLines) == 0)
        {
            return redirect()->route('warehouse.stock.supply.place', ['supply_id' => $supply->id])->with('error', __('messages.order_empty'));
        }

        $warehouse = $supply->warehouse;

        return view('pages.warehouse.stock.supply.detail', compact('supply', 'warehouse'));
    }

    public function newSupply()
    {
        $user = auth()->user();

        $warehouse = $user->warehouseUser->warehouse;

        // Récupérer les fournisseurs
        $suppliers = $warehouse->stock->flatMap(function ($stock) {
            // Récupérer toutes les lignes d'approvisionnement associées au produit
            return $stock->product->supplyLines->map(function ($supplyLine) {
                // Retourner le fournisseur associé à la ligne d'approvisionnement
                return $supplyLine->supply->supplier;
            });
        })->unique();
        
        return view('pages.warehouse.stock.supply.new', compact('suppliers'));
    }

    public function placeNewSupply(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|integer|exists:suppliers,id',
        ],
        [
            'supplier_id.required' => __('messages.supplier_not_found'),
            'supplier_id.integer' => __('messages.supplier_not_found'),
            'supplier_id.exists' => __('messages.supplier_not_found'),
        ]);

        $supplier = Supplier::find($request->supplier_id);

        $user = auth()->user();

        $warehouse = $user->warehouseUser->warehouse;

        // Supprimer les commandes ayant 0 produits commandés
        $warehouse->supplies->each(function ($supply) {
            if(count($supply->supplyLines) == 0)
            {
                $supply->delete();
            }
        });

        // Créer un nouvel approvisionnement
        $supply = $warehouse->supplies()->create([
            'user_id' => $user->id,
            'supplier_id' => $supplier->id,
            'supply_status' => Supply::SUPPLY_STATUS_IN_PROGRESS,
        ]);

        return redirect()->route('warehouse.stock.supply.place', ['supply_id' => $supply->id])->with('success', __('messages.supply_created'));
    }

    public function placeSupply(int $supply_id)
    {
        $supply = Supply::find($supply_id);

        if(!$supply)
        {
            return redirect()->route('warehouse.stock.supply.index')->with('error', __('messages.supply_not_found'));
        }

        // Vérifier le statut de la commande
        if($supply->supply_status != Supply::SUPPLY_STATUS_IN_PROGRESS)
        {
            return redirect()->route('warehouse.stock.supply.index')->with('error', __('messages.supply_not_in_progress'));
        }

        $user = auth()->user();

        // Récupérer les produits de l'entrepot
        $warehouse = $user->warehouseUser->warehouse;

        $supplier = $supply->supplier;

        // Récupérer les produits de l'entrepôt qui sont associés au fournisseur
        $products = Product::whereHas('supplyLines.supply', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->whereHas('stocks', function ($query) use ($warehouse) {
            $query->where('warehouse_id', $warehouse->id);
        })->get();

        $warehouse = $supply->warehouse;

        // Récupérer toutes les commandes IN PROGRESS et PENDING (des magasins associés à l'entrepôt)
        $orders = $warehouse->stores->flatMap(function ($store) {
            return $store->orders->where('order_status', Order::ORDER_STATUS_IN_PROGRESS)->concat($store->orders->where('order_status', Order::ORDER_STATUS_PENDING));
        })->flatMap(function ($order) {
            return $order->orderLines;
        });
        
        // Récupérer la quantité restante du stock
        $total_quantity_ordered = $orders->sum('quantity_ordered');

        $total_quantity_stock = $warehouse->stock->sum('quantity_available');

        $total_quantity_supplied = $supply->supplyLines->sum('quantity_supplied');

        $total = $total_quantity_ordered + $total_quantity_stock + $total_quantity_supplied;

        $total_quantity = $warehouse->capacity - $total;
            
        return view('pages.warehouse.stock.supply.place_supply', compact('supply', 'products', 'warehouse', 'total_quantity'));
    }


    public function addProductToSupply(Request $request)
    {
        // Vérification des données
        $request->validate([
            'supply_id' => 'required|integer|exists:supplies,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ],
        [
            'supply_id.required' => __('messages.supply_not_found'),
            'supply_id.integer' => __('messages.supply_not_found'),
            'supply_id.exists' => __('messages.supply_not_found'),
            'product_id.required' => __('messages.product_not_found'),
            'product_id.integer' => __('messages.product_not_found'),
            'product_id.exists' => __('messages.product_not_found'),
            'quantity.required' => __('messages.quantity_required'),
            'quantity.integer' => __('messages.quantity_integer'),
            'quantity.min' => __('messages.quantity_min'),
        ]);

        // Récupérer la commande et le produit
        $supply = Supply::find($request->supply_id);

        // Vérifier le statut de la commande
        if($supply->supply_status != Supply::SUPPLY_STATUS_IN_PROGRESS)
        {
            return redirect()->route('warehouse.supply.index')->with('error', __('messages.supply_not_in_progress'));
        }

        $product = Product::find($request->product_id);

        // Vérifier si la quantité n'excède pas la capacité du stock
        $warehouse = $supply->warehouse;

        // Récupérer toutes les commandes IN PROGRESS et PENDING (des magasins associés à l'entrepôt)
        $orders = $warehouse->stores->flatMap(function ($store) {
            return $store->orders->where('order_status', Order::ORDER_STATUS_IN_PROGRESS)->concat($store->orders->where('order_status', Order::ORDER_STATUS_PENDING));
        })->flatMap(function ($order) {
            return $order->orderLines;
        });

        // Récupérer la quantité totale du stock
        $total_quantity_ordered = $orders->sum('quantity_ordered');

        $total_quantity_stock = $warehouse->stock->sum('quantity_available');

        $total_quantity_supplied = $supply->supplyLines->sum('quantity_supplied');

        $total = $total_quantity_ordered + $total_quantity_stock + $total_quantity_supplied;

        if ($total + $request->quantity > $warehouse->capacity) {
            return redirect()->back()->with('error', __('messages.quantity_exceeds_capacity'));
        }

        // Vérifier si le produit n'est pas déjà dans la commande
        if($supply->supplyLines->where('product_id', $request->product_id)->first())
        {
            // Mettre à jour la quantité
            $supplyLine = $supply->supplyLines->where('product_id', $request->product_id)->first();
            
            $supplyLine->addQuantity($request->quantity);
        }
        else
        {
            // Ajouter le produit à la commande
            $supply->supplyLines()->create([
                'product_id' => $request->product_id,
                'quantity_supplied' => $request->quantity,
                'unit_price' => $product->reference_price,
            ]);
        }

        return redirect()->route('warehouse.stock.supply.place', ['supply_id' => $request->supply_id])->with('success', __('messages.product_added'));
    }

    public function removeProductFromSupply(Request $request)
    {
        // Vérification des données
        $request->validate([
            'supply_id' => 'required|integer|exists:supplies,id',
            'product_id' => 'required|integer|exists:products,id',
        ],
        [
            'supply_id.required' => __('messages.supply_not_found'),
            'supply_id.integer' => __('messages.supply_not_found'),
            'supply_id.exists' => __('messages.supply_not_found'),
            'product_id.required' => __('messages.product_not_found'),
            'product_id.integer' => __('messages.product_not_found'),
            'product_id.exists' => __('messages.product_not_found'),
        ]);

        // Récupérer la commande et le produit
        $supply = Supply::find($request->supply_id);

        // Vérifier le statut de la commande
        if($supply->supply_status != Supply::SUPPLY_STATUS_IN_PROGRESS)
        {
            return redirect()->route('warehouse.stock.supply.index')->with('error', __('messages.supply_not_in_progress'));
        }

        $product = Product::find($request->product_id);

        // Vérifier si le produit est dans la commande
        $supplyLine = $supply->supplyLines->where('product_id', $request->product_id)->first();

        if(!$supplyLine)
        {
            return redirect()->back()->with('error', __('messages.product_not_in_order'));
        }

        // Supprimer la ligne de commande
        $supplyLine->delete();

        return redirect()->back()->with('success', __('messages.product_removed'));
    }

    public function removeQuantityProductFromSupply(Request $request)
    {
        // Vérification des données
        $request->validate([
            'supply_id' => 'required|integer|exists:supplies,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ],
        [
            'supply_id.required' => __('messages.supply_not_found'),
            'supply_id.integer' => __('messages.supply_not_found'),
            'supply_id.exists' => __('messages.supply_not_found'),
            'product_id.required' => __('messages.product_not_found'),
            'product_id.integer' => __('messages.product_not_found'),
            'product_id.exists' => __('messages.product_not_found'),
        ]);

        // Récupérer la commande et le produit
        $supply = Supply::find($request->supply_id);

        // Vérifier le statut de la commande
        if($supply->supply_status != Supply::SUPPLY_STATUS_IN_PROGRESS)
        {
            return redirect()->route('warehouse.stock.supply.index')->with('error', __('messages.supply_not_in_progress'));
        }

        $product = Product::find($request->product_id);

        // Vérifier si le produit est dans la commande
        $supplyLine = $supply->supplyLines->where('product_id', $request->product_id)->first();

        if(!$supplyLine)
        {
            return redirect()->back()->with('error', __('messages.product_not_in_order'));
        }

        $quantity = $request->quantity;        

        // Vérifier si la quantité n'excède pas la quantité commandée
        if($quantity > $supplyLine->quantity_supplied)
        {
            return redirect()->back()->with('error', __('messages.quantity_exceed'));
        }

        // Enlever la quantité de la ligne de commande
        $supplyLine->removeQuantity($quantity);

        if($supplyLine->quantity_supplied == 0)
        {
            // Supprimer la ligne de commande
            $supplyLine->delete();
        }

        return redirect()->back()->with('success', __('messages.remove_quantity_success'));
    }

    public function recapSupply(int $supply_id)
    {
        // Vérifier si la commande existe
        $supply = Supply::find($supply_id);

        if(!$supply)
        {
            return redirect()->route('warehouse.stock.supply.index')->with('error', __('messages.supply_not_found'));
        }

        // Vérifier le statut de la commande
        if($supply->supply_status != Supply::SUPPLY_STATUS_IN_PROGRESS)
        {
            return redirect()->route('warehouse.stock.supply.index')->with('error', __('messages.supply_not_in_progress'));
        }

        // Vérifier si la commande n'est pas vide
        if(count($supply->supplyLines) == 0)
        {
            return redirect()->route('warehouse.stock.supply.place', ['supply_id' => $supply->id])->with('error', __('messages.supply_empty'));
        }

        $warehouse = $supply->warehouse;
        
        return view('pages.warehouse.stock.supply.recap_supply', compact('supply', 'warehouse'));
    }

    public function confirmSupply(Request $request)
    {
        // Vérification des données
        $request->validate([
            'supply_id' => 'required|integer|exists:supplies,id',
        ],
        [
            'supply_id.required' => __('messages.supply_not_found'),
            'supply_id.integer' => __('messages.supply_not_found'),
            'supply_id.exists' => __('messages.supply_not_found'),
        ]);

        // Récupérer la commande
        $supply = Supply::find($request->supply_id);

        // Vérifier le statut de la commande
        if($supply->supply_status != Supply::SUPPLY_STATUS_IN_PROGRESS)
        {
            return redirect()->route('warehouse.stock.supply.index')->with('error', __('messages.supply_not_in_progress'));
        }

        // Vérifier si la commande n'est pas vide
        if(count($supply->supplyLines) == 0)
        {
            return redirect()->route('warehouse.stock.supply.place', ['supply_id' => $supply->id])->with('error', __('messages.supply_empty'));
        }

        // Vérifier si la quantité commandée n'excède pas la capacité de l'entrepôt
        $warehouse = $supply->warehouse;

        // Récupérer toutes les commandes IN PROGRESS et PENDING (des magasins associés à l'entrepôt)
        $orders = $warehouse->stores->flatMap(function ($store) {
            return $store->orders->where('order_status', Order::ORDER_STATUS_IN_PROGRESS)->concat($store->orders->where('order_status', Order::ORDER_STATUS_PENDING));
        })->flatMap(function ($order) {
            return $order->orderLines;
        });

        // Récupérer la quantité totale du stock
        $total_quantity_ordered = $orders->sum('quantity_ordered');

        $total_quantity_stock = $warehouse->stock->sum('quantity_available');

        $total_quantity_supplied = $supply->supplyLines->sum('quantity_supplied');

        $total = $total_quantity_ordered + $total_quantity_stock + $total_quantity_supplied;

        if ($total + $request->quantity > $warehouse->capacity) {
            return redirect()->back()->with('error', __('messages.quantity_exceeds_capacity'));
        }

        // Changer le statut de la commande
        $supply->supply_status = supply::SUPPLY_STATUS_DELIVERED;
        $supply->save();

        // Approvisionner le stock
        $supply->supplyLines->each(function ($supplyLine) use ($supply) {
            $stock = $supply->warehouse->stock->where('product_id', $supplyLine->product_id)->first();

            $stock->addQuantity($supplyLine->quantity_supplied);
        });

        // Réaliser les mouvements de stock pour chaque produits
        $supply->supplyLines->each(function ($supplyLine) use ($warehouse) {
            $warehouse->stockMovements()->create([
                'product_id' => $supplyLine->product_id,
                'user_id' => auth()->id(),
                'quantity_moved' => $supplyLine->quantity_supplied,
                'movement_type' => StockMovement::MOVEMENT_TYPE_IN,
                'movement_date' => now(),
                'movement_status' => StockMovement::MOVEMENT_STATUS_COMPLETED,
                'movement_source' => StockMovement::MOVEMENT_SOURCE_SUPPLY,
            ]);
        });

        // Editer une facture
        $supply->invoice()->create([
            'invoice_number' => strtoupper(uniqid()),
            'invoice_date' => now(),
            'invoice_status' => Invoice::INVOICE_STATUS_UNPAID,
            'order_id' => null,
            'supply_id' => $supply->id,
        ]);

        return redirect()->route('warehouse.stock.supply.index')->with('success', __('messages.supply_confirmed'));
    }

    /**
    * Crée un approvisionnement pour un produit spécifique.
    *
    * @param mixed $product Le produit à approvisionner.
    * @param mixed $supplier Le fournisseur associé.
    * @param mixed $user L'utilisateur associé.
    * @param mixed $warehouse L'entrepôt associé.
    * @param int $quantity La quantité à approvisionner.
    * @return bool
    */
    private function createSupplyForProduct($product, $supplier, $user, $warehouse, $quantity)
    {
        try {
            DB::beginTransaction(); // Démarrer une transaction
            // Créer un mouvement de stock
            $warehouse->stockMovements()->create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'quantity_moved' => $quantity,
                'movement_type' => StockMovement::MOVEMENT_TYPE_IN,
                'movement_date' => now(),
                'movement_status' => StockMovement::MOVEMENT_STATUS_COMPLETED,
                'movement_source' => StockMovement::MOVEMENT_SOURCE_SUPPLY,
            ]);

            // Créer un approvisionnement
            $supply = $warehouse->supplies()->create([
                'user_id' => $user->id,
                'warehouse_id' => $warehouse->id,
                'supplier_id' => $supplier->id,
                'supply_status' => Supply::SUPPLY_STATUS_DELIVERED,
            ]);

            // Créer une ligne d'approvisionnement
            $supply->supplyLines()->create([
                'product_id' => $product->id,
                'quantity_supplied' => $quantity,
                'unit_price' => $product->reference_price,
            ]);

            // Créer une facture
            $supply->invoice()->create([
                'invoice_number' => strtoupper(uniqid()),
                'invoice_date' => now(),
                'invoice_status' => Invoice::INVOICE_STATUS_UNPAID,
                'order_id' => null,
                'supply_id' => $supply->id,
            ]);
            DB::commit(); // Valider les changements si tout se passe bien
        } catch (\Exception $e) {
            DB::rollBack(); // Annuler toutes les opérations en cas d'erreur
            return false;
        }

        return true;
    }

    /**
    * Supprime une quantité spécifique d'un produit en stock.
    *
    * @param mixed $stock Le stock du produit.
    * @param int $quantity La quantité à supprimer.
    * @return bool
    */
    private function removeQuantityProductFromStock($stock, $quantity)
    {
        try {
            DB::beginTransaction(); // Démarrer une transaction
            // Créer un mouvement de stock
            $stock->warehouse->stockMovements()->create([
                'product_id' => $stock->product->id,
                'user_id' => auth()->id(),
                'quantity_moved' => $quantity,
                'movement_type' => StockMovement::MOVEMENT_TYPE_OUT,
                'movement_date' => now(),
                'movement_status' => StockMovement::MOVEMENT_STATUS_COMPLETED,
                'movement_source' => StockMovement::MOVEMENT_SOURCE_USER,
            ]);
            DB::commit(); // Valider les changements si tout se passe bien
        } catch (\Exception $e) {
            DB::rollBack(); // Annuler toutes les opérations en cas d'erreur
            return false;
        }

        return true;
    }
}
