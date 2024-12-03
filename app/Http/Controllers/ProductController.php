<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenFoodFacts;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockMovement;
use App\Models\Invoice;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('pages.warehouse.product.search_new_product', compact('categories', 'suppliers'));
    }

    public function searchProducts(Request $request)
    {
        // Valider les données de la requête
        $request->validate([
            'search_by_name' => 'nullable|string', 
            'supplier_name' => 'required|exists:suppliers,supplier_name', 
            'category_name' => 'nullable|exists:categories,category_name',
            'page_number' => 'nullable|integer|min:1',
        ], [
            'search_by_name.string' => __('messages.validate.search_by_name_string'),
            'supplier_name.exists' => __('messages.validate.supplier_name_exists'),
            'supplier_name.required' => __('messages.validate.supplier_name_required'),
            'category_name.exists' => __('messages.validate.category_name_exists'),
            'page_number.integer' => __('messages.validate.page_number_integer'),
            'page_number.min' => __('messages.validate.page_number_min'),
        ]);
        

        $searchByName = $request->query('search_by_name');
        $supplierName = $request->query('supplier_name');
        $categoryName = $request->query('category_name');
        $pageNumber = $request->query('page_number');

        $result = OpenFoodFacts::search("{$searchByName} {$supplierName} {$categoryName}", $pageNumber, 100);

        // Récupérer toutes les catégories et tous les fournisseurs
        $categories = Category::all();
        $suppliers = Supplier::all();

        // Si des produits ont été trouvés
        if (!empty($result)) {
            $products = [];

            $user = auth()->user();

            // Récupérer l'entrepôt de l'utilisateur
            $warehouse = $user->warehouseUser->warehouse;

            // Récupérer les produits déjà dans l'entrepôt
            $warehouseProducts = $warehouse->stock->map(function ($stock) {
                return $stock->product;
            });

            foreach ($result as $document) {
                // Accède aux données du produit
                $product = $document->getData();

                list($isValid, $identicalSuppliers, $identicalCategories) = $this->validateProduct($product, $categories, $suppliers, $warehouseProducts);

                // Passer au produit suivant si les données ne sont pas valides
                if(!$isValid) {
                    continue;
                }

                // Récupérer le fournisseur et la catégorie correspondant à la requête
                $dataSupplier = Supplier::whereIn('supplier_name', $identicalSuppliers)->first(); 
                $dataCategories = Category::whereIn('category_name', $identicalCategories)->get();

                // Ajouter le produit au tableau
                $products[] = [
                    'id' => $product['id'],
                    'name' => $product['product_name'],
                    'image_url' => $product['image_url'],
                    'supplier' => $dataSupplier,
                    'categories' => $dataCategories,
                ];
            }
        }

        // Retourner la vue avec les produits, les catégories et les fournisseurs
        return view('pages.warehouse.product.search_new_product', compact('categories', 'suppliers', 'products'));
    }

    public function addProduct(int $productId) {
        
        // Récupérer les informations du produit
        $product = OpenFoodFacts::barcode($productId);

        if (empty($product)) {
            return redirect()->route('warehouse.product.index')->with('error', __('messages.validate.product_not_found'));
        }

        $user = auth()->user();

        // Récupérer l'entrepôt de l'utilisateur
        $warehouse = $user->warehouseUser->warehouse;

        // Récupérer les produits déjà dans l'entrepôt
        $warehouseProducts = $warehouse->stock->map(function ($stock) {
            return $stock->product;
        });

        // Récupérer toutes les catégories et tous les fournisseurs
        $categories = Category::all();
        $suppliers = Supplier::all();

        list($isValid, $identicalSuppliers, $identicalCategories) = $this->validateProduct($product, $categories, $suppliers, $warehouseProducts);

        // Si les données ne sont pas valides
        if(!$isValid) {
            return redirect()->route('warehouse.product.index')->with('error', __('messages.problem_with_product_data'));
        }

        // Récupérer le fournisseur et la/les catégorie(s) correspondant à la requête
        $dataSupplier = Supplier::whereIn('supplier_name', $identicalSuppliers)->first();
        $dataCategories = Category::whereIn('category_name', $identicalCategories)->get();

        $product = [
            'id' => $product['id'],
            'name' => $product['product_name'],
            'image_url' => $product['image_url'],
            'supplier' => $dataSupplier,
            'categories' => $dataCategories,
        ];

        return view('pages.warehouse.product.add_product', compact('product'));
    }

    public function addProductSubmit(Request $request)
    {
        // Validation des données
        $request->validate([
            'product_id' => 'required|integer', 
            'quantity' => 'required|integer|min:1|gte:restock_threshold', 
            'alert_threshold' => 'required|integer|min:1|gte:restock_threshold',
            'restock_threshold' => 'required|integer|min:0',
            'auto_restock_quantity' => 'required|integer|gte:restock_threshold',
        ],
        [
            'product_id.required' => __('messages.validate.product_id_required'),
            'product_id.integer' => __('messages.validate.product_id_integer'),
            'quantity.required' => __('messages.validate.quantity_required'),
            'quantity.integer' => __('messages.validate.quantity_integer'),
            'quantity.min' => __('messages.validate.quantity_min'),
            'quantity.gte' => __('messages.validate.quantity_gte'),
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

        $quantity = $request->input('quantity');

        $user = auth()->user();

        $warehouse = $user->warehouseUser->warehouse;

        // Vérifier si la quantité dépasse la capacité de l'entrepôt
        if (($quantity + $warehouse->stock->sum('quantity_available')) > $warehouse->capacity) {
            return redirect()->back()->withErrors(__('messages.validate.quantity_exceeds_capacity'))->withInput();
        }

        // Vérifier si le seuil d'alerte, le seuil de réapprovisionnement et l'auto réappro sont inférieurs à la capacité de l'entrepôt
        if ($request->input('alert_threshold') > $warehouse->capacity || $request->input('restock_threshold') > $warehouse->capacity || $request->input('auto_restock_quantity') > $warehouse->capacity) {
            return redirect()->back()->withErrors(__('messages.validate.thresholds_exceeds_capacity'))->withInput();
        }

        $productId = $request->input('product_id');
        
        // Récupérer les informations du produit
        $product = OpenFoodFacts::barcode($productId);

        if (empty($product)) {
            return redirect()->back()->withErrors(__('messages.validate.product_not_found'))->withInput();
        }

        // Récupérer les produits déjà dans l'entrepôt
        $warehouseProducts = $warehouse->stock->map(function ($stock) {
            return $stock->product;
        });

        // Récupérer toutes les catégories et tous les fournisseurs
        $categories = Category::all();
        $suppliers = Supplier::all();

        list($isValid, $identicalSuppliers, $identicalCategories) = $this->validateProduct($product, $categories, $suppliers, $warehouseProducts);

        // Si les données ne sont pas valides
        if(!$isValid) {
            return redirect()->route('warehouse.product.index')->with('error', __('messages.validate.problem_with_product_data'));
        }

        // Récupérer le fournisseur et la/les catégorie(s) correspondant à la requête
        $dataSupplier = Supplier::whereIn('supplier_name', $identicalSuppliers)->first();

        $dataCategoryIds = Category::whereIn('category_name', $identicalCategories)->pluck('id');

        // Vérifier si le produit est pas déjà dans la base de données globales, de tous les entrepôts
        $dbProduct = Product::find($product['id']);

        if ($dbProduct != null) {
            // Ajouter le produit à l'entrepôt, donc au stock
            $success = $this->addProductToWarehouse($dbProduct, $dataSupplier, $user, $warehouse, $request);
        } else {
            // Ajouter le produit à la base de données
            $newProduct = Product::create([
                'id' => $product['id'],
                'product_name' => $product['product_name'],
                'image_url' => $product['image_url'],
                'reference_price' => mt_rand(100, 2000) / 100, // Prix compris entre 1 et 20 euros
                'supplier_id' => $dataSupplier->id,
            ]);

            // Ajouter les catégories au produit
            $newProduct->categories()->attach($dataCategoryIds);

            $success = $this->addProductToWarehouse($newProduct, $dataSupplier, $user, $warehouse, $request);
        }

        if ($success) {
            return redirect()->route('warehouse.product.index')->with('success', __('messages.product_added'));
        }
        else {
            return redirect()->route('warehouse.product.index')->with('error', __('messages.problem_when_adding_product'));
        }
    }

    /**
     * Ajouter un produit à l'entrepôt.
     *
     * @param Product $product Le produit à ajouter.
     * @param Supplier $supplier Le fournisseur du produit.
     * @param User $user L'utilisateur qui ajoute le produit.
     * @param Warehouse $warehouse L'entrepôt dans lequel ajouter le produit.
     * @param Request $request La requête contenant les données du produit.
     * @return bool Un booléen indiquant si l'ajout a réussi.
     */
    private function addProductToWarehouse($product, $supplier, $user, $warehouse, $request)
    {
        try {
            // Ajouter le produit au stock de l'entrepôt
            $warehouse->stock()->create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'quantity_available' => $request->input('quantity'),
                'restock_threshold' => $request->input('restock_threshold'),
                'alert_threshold' => $request->input('alert_threshold'),
                'auto_restock_quantity' => $request->input('auto_restock_quantity'),
            ]);

            // Créer un mouvement de stock
            $warehouse->stockMovements()->create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'quantity_moved' => $request->input('quantity'),
                'movement_type' => StockMovement::MOVEMENT_TYPE_IN,
                'movement_date' => now(),
                'movement_status' => StockMovement::MOVEMENT_STATUS_COMPLETED,
                'movement_source' => StockMovement::MOVEMENT_SOURCE_SUPPLY,
            ]);

            // Créer un approvisionnement
            $supply = $warehouse->supplies()->create([
                'supplier_id' => $supplier->id,
                'quantity' => $request->input('quantity'),
            ]);

            // Créer une ligne d'approvisionnement
            $supply->supplyLines()->create([
                'product_id' => $product->id,
                'quantity_supplied' => $request->input('quantity'),
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

    
    /**
     * Rechercher des fournisseurs correspondant aux marques de produits données.
     *
     * @param array $productBrands Un tableau de marques de produits à rechercher.
     * @param array $suppliers Un tableau de fournisseurs dans lequel rechercher.
     * @return array Un tableau de fournisseurs correspondant aux marques de produits données.
     */
    private function searchIdenticalSuppliers($productBrands, $suppliers)
    {
        $identicalSuppliers = [];
        foreach ($productBrands as $brand) {
            if (in_array($brand, $suppliers->pluck('supplier_name')->toArray())) {
                $identicalSuppliers[] = $brand;
            }
        }

        return $identicalSuppliers;
    }

    /**
     * Rechercher des catégories correspondant aux catégories de produits données.
     *
     * @param array $productCategories Un tableau de catégories de produits à rechercher.
     * @param array $categories Un tableau de catégories dans lequel rechercher.
     * @return array Un tableau de catégories correspondant aux catégories de produits données.
     */
    private function searchIdenticalCategories($productCategories, $categories)
    {
        $identicalCategories = [];
        foreach ($productCategories as $category) {
            if (in_array($category, $categories->pluck('category_name')->toArray())) {
                $identicalCategories[] = $category;
            }
        }

        return $identicalCategories;
    }

    /**
     * Valider les données du produit.
     *
     * @param array $product Les données du produit à valider.
     * @param array $categories Les catégories disponibles.
     * @param array $suppliers Les fournisseurs disponibles.
     * @param \Illuminate\Support\Collection $warehouseProducts Les produits déjà présents dans l'entrepôt.
     * @return array Un tableau contenant un booléen indiquant si le produit est valide, les fournisseurs identiques et les catégories identiques.
     */
    private function verifyDataProduct($product) : bool
    {
        return 
            !isset($product['id']) ||
            !isset($product['product_name']) || 
            !isset($product['image_url']) || 
            !isset($product['categories']) || 
            !isset($product['brands']) ||
            empty($product['id']) ||
            empty($product['product_name']) || 
            empty($product['image_url']) || 
            empty($product['categories']) ||
            empty($product['brands']);
    }

    /**
     * Séparer une chaîne en un tableau et nettoyer les espaces inutiles autour de chaque élément.
     *
     * @param string $input La chaîne à séparer.
     * @param string $delimiter Le délimiteur à utiliser.
     * @return array Un tableau contenant les éléments séparés et nettoyés.
     */    
    function splitAndTrim(string $input, string $delimiter = ','): array
    {
        // Séparer la chaîne en un tableau
        $array = explode($delimiter, $input);

        // Nettoyer les espaces inutiles autour de chaque élément
        return array_map('trim', $array);
    }

    /**
     * Valider les données du produit.
     *
     * @param array $product Les données du produit à valider.
     * @param array $categories Les catégories disponibles.
     * @param array $suppliers Les fournisseurs disponibles.
     * @param \Illuminate\Support\Collection $warehouseProducts Les produits déjà présents dans l'entrepôt.
     * @return array Un tableau contenant un booléen indiquant si le produit est valide, les fournisseurs identiques et les catégories identiques.
     */
    private function validateProduct($product, $categories, $suppliers, $warehouseProducts)
    {
        // Passer au produit suivant si les données ne sont pas valides
        if ($this->verifyDataProduct($product)) {
            return [false, [], []];
        }

        // Passer au produit suivant si le produit est déjà dans l'entrepôt
        if ($warehouseProducts->contains('id', $product['id'])) {
            return [false, [], []];
        }

        // Vérifier si le produit appartient à un/des fournisseur(s) valide(s)
        $productBrands = $product['brands'];

        $productBrands = $this->splitAndTrim($productBrands);

        $identicalSuppliers = $this->searchIdenticalSuppliers($productBrands, $suppliers);

        // Passer au produit suivant si aucun fournisseur n'a été trouvée
        if (empty($identicalSuppliers)) {
            return [false, [], []];
        }

        // Vérifier si le produit appartient à une/des catégorie(s) valide
        $productCategories = $product['categories'];

        $productCategories = $this->splitAndTrim($productCategories);

        $identicalCategories = $this->searchIdenticalCategories($productCategories, $categories);                

        // Passer au produit suivant si aucune catégorie n'a été trouvée
        if (empty($identicalCategories)) {
            return [false, [], []];
        }

        // Si le produit est valide
        return [true, $identicalSuppliers, $identicalCategories];
    }
}
