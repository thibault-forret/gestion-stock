<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Invoice;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index ()
    {
        return view('pages.warehouse.stock.index');
    }

    public function stockList () 
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

    public function infoProduct(int $stock_id)
    {
        $user = auth()->user();

        // Vérifier si le stock appartient à l'entrepôt de l'utilisateur
        $stock = $user->warehouseUser->warehouse->stock->where('id', $stock_id)->first();

        if (!$stock) {
            return redirect()->route('warehouse.stock.index')->with('error', __('messages.stock_not_found'));
        }

        $product = $stock->product;

        return view('pages.warehouse.stock.info_product', compact('stock', 'product'));
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
        $stock->addStock($quantity);

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

    public function removeProduct(int $stock_id) 
    {
        $user = auth()->user();

        // Vérifier si le stock appartient à l'entrepôt de l'utilisateur
        $stock = $user->warehouseUser->warehouse->stock->where('id', $stock_id)->first();

        if (!$stock) {
            return redirect()->route('warehouse.stock.index')->with('error', __('messages.stock_not_found'));
        }

        $product = $stock->product;

        return view('pages.warehouse.stock.remove_product', compact('stock', 'product'));
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
        $stock->removeStock($quantity);

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

    public function newSupplyStock()
    {
        $user = auth()->user();

        // Récupérer l'entrepôt de l'utilisateur
        $warehouse = $user->warehouseUser->warehouse;

        // Récupérer les produits déjà dans l'entrepôt
        $products = $warehouse->stock->map(function ($stock) {
            return $stock->product;
        });

        return view('pages.warehouse.stock.new_supply', compact('products', 'warehouse'));
    }

    public function newSupplyStockSubmit(Request $request)
    {
        // Validation des produits et des quantités
        $request->validate([
            'products' => 'required|array', // Le champ 'products' doit être un tableau
            'products.*' => 'required|integer|exists:products,id', // Chaque produit doit être un entier existant dans la table products
            
            'quantities' => 'required|array', // Le champ 'quantities' doit être un tableau
            'quantities.*' => 'required|integer|min:1', // Chaque quantité doit être un entier et au minimum 1
        ],
        [
            'products.required' => __('messages.validate.products_required'),
            'products.array' => __('messages.validate.products_array'),
            'products.*.required' => __('messages.validate.products_each_required'),
            'products.*.integer' => __('messages.validate.products_each_integer'),
            'products.*.exists' => __('messages.validate.products_each_exists'),
            'quantities.required' => __('messages.validate.quantities_required'),
            'quantities.array' => __('messages.validate.quantities_array'),
            'quantities.*.required' => __('messages.validate.quantities_each_required'),
            'quantities.*.integer' => __('messages.validate.quantities_each_integer'),
            'quantities.*.min' => __('messages.validate.quantities_each_min'),
        ]);

        $user = auth()->user();

        $warehouse = $user->warehouseUser->warehouse;

        $request->merge($request->except('products', 'quantities'));

        // Vérifier si la quantité totale inférieure à la capacité maximale
        if (array_sum($request->quantities) + $warehouse->stock->sum('quantity_available') > $warehouse->capacity) {
            return redirect()->back()->withErrors(__('messages.validate.quantity_exceeds_capacity'))->withInput();
        }

        // Vérifier si les produits sont dans le stock
        $warehouseProducts = $warehouse->stock->whereIn('product_id', $request->products);

        if ($warehouseProducts->count() != count($request->products)) {
            return redirect()->back()->withErrors(__('messages.validate.product_not_in_stock'))->withInput();
        }

        // Récupérer les fournisseurs des produits et trier le produits selon leurs fournisseurs
        $suppliersData = [];

        foreach ($request->products as $index => $productId) {
            $product = $warehouseProducts->where('product_id', $productId)->first()->product;

            $supplier = $product->supplyLines->first()->supply->supplier;

             // Initialiser l'entrée pour ce fournisseur si elle n'existe pas
            if (!isset($suppliersData[$supplier->id])) {
                $suppliersData[$supplier->id] = [];
            }

            // Ajouter le produit et la quantité sous ce fournisseur
            $suppliersData[$supplier->id][] = [
                'product_id' => $productId,
                'quantity' => $request->quantities[$index],
            ];
        }

        // Créer les approvisionnements en fonction des fournisseurs
        $success = $this->createSupplyBySupplier($suppliersData, $warehouse, $user);

        if ($success) {
            return redirect()->route('warehouse.stock.index')->with('success', __('messages.action_success'));
        } 
        else {
            return redirect()->route('warehouse.stock.index')->with('error', __('messages.action_failed'));
        }
    }

    private function createSupplyBySupplier($suppliersData, $warehouse, $user)
    {
        try {
            DB::beginTransaction(); // Démarrer une transaction

            // Pour chaque fournisseur, créer un approvisionnement
            foreach ($suppliersData as $supplierId => $productsData) {
                $supplier = Supplier::find($supplierId);

                $supply = $warehouse->supplies()->create([
                    'supplier_id' => $supplier->id,
                ]);

                foreach ($productsData as $productData) {
                    $stock = $warehouse->stock->whereIn('product_id', $productData['product_id'])->first();

                    $stock->addStock($productData['quantity']);

                    $product = $stock->product;

                    $warehouse->stockMovements()->create([
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'quantity_moved' => $productData['quantity'],
                        'movement_type' => StockMovement::MOVEMENT_TYPE_IN,
                        'movement_date' => now(),
                        'movement_status' => StockMovement::MOVEMENT_STATUS_COMPLETED,
                        'movement_source' => StockMovement::MOVEMENT_SOURCE_SUPPLY,
                    ]);

                    $supply->supplyLines()->create([
                        'product_id' => $product->id,
                        'quantity_supplied' => $productData['quantity'],
                        'unit_price' => $product->reference_price,
                    ]);
                }

                $supply->invoice()->create([
                    'invoice_number' => strtoupper(uniqid()),
                    'invoice_date' => now(),
                    'invoice_status' => Invoice::INVOICE_STATUS_UNPAID,
                    'order_id' => null,
                    'supply_id' => $supply->id,
                ]);
            }

            DB::commit(); // Valider les changements si tout se passe bien
        } catch (\Exception $e) {
            DB::rollBack(); // Annuler toutes les opérations en cas d'erreur

            return false;
        }

        return true;
    }

    private function createSupplyForProduct($product, $supplier, $user, $warehouse, $quantity)
    {
        try {
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
                'supplier_id' => $supplier->id,
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
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    private function removeQuantityProductFromStock($stock, $quantity)
    {
        try {
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
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
