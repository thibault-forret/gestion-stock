<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenFoodFacts;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('pages.warehouse.choose-new-product', compact('categories', 'suppliers'));
    }


    // Retirer les produits qui sont déjà dans l'entrepot (BDD)
    public function searchProducts(Request $request)
    {
        // Valider les données de la requête
        $request->validate([
            'search_by_name' => 'nullable|string', 
            'supplier_name' => 'required|exists:suppliers,supplier_name', 
            'category_name' => 'nullable|exists:categories,category_name',
            'page_number' => 'nullable|integer|min:1',
        ], [
            'search_by_name.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'supplier_name.exists' => 'Le fournisseur sélectionné n\'existe pas.',
            'supplier_name.required' => 'Le nom du fournisseur est requis.',
            'category_name.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'page_number.integer' => 'Le numéro de page doit être un entier.',
            'page_number.min' => 'Le numéro de page doit être supérieur ou égal à 1.',
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
        return view('pages.warehouse.choose-new-product', compact('categories', 'suppliers', 'products'));
    }


    // A faire : Ajouter un produit à l'entrepôt
    public function addProduct(Request $request)
    {
        // Validation des données
        $request->validate([
            'product_id' => 'required|integer|unique:products,id', // Vérifie que l'ID est valide
        ],
        [
            'product_id.required' => 'L\'ID du produit est requis. Veuillez réessayer.',
            'product_id.integer' => 'L\'ID du produit doit être un entier. Veuillez réessayer.',
            'product_id.unique' => 'Le produit est déjà dans l\'entrepôt.',
        ]);

        $productId = $request->input('product_id');
        
        // Récupérer les informations du produit
        $product = OpenFoodFacts::barcode($productId);

        dd($product);

        if (empty($product)) {
            return redirect()->route('product.search')->with('error', 'Le produit n\'a pas été trouvé. Veuillez réessayer.');
        }

        list($isValid, $identicalSuppliers, $identicalCategories) = $this->validateProduct($product, $categories, $suppliers, $warehouseProducts);

        // Si les données ne sont pas valides
        if(!$isValid) {
            return redirect()->route('product.search')->with('error', 'Un problème est survenu avec les données du produit. Veuillez réessayer.');
        }

        // Récupérer le fournisseur et la/les catégorie(s) correspondant à la requête
        $dataSupplier = Supplier::whereIn('supplier_name', $identicalSuppliers)->first(); 
        $dataCategories = Category::whereIn('category_name', $identicalCategories)->get();

        // TO DO : Finir l'ajout du produit à  la base de données
        // Tester si ca fonctionne bien

        // Ajouter le produit à la base de données
        Product::create([
            'id' => $product['id'],
            'name' => $product['name'],
            'image_url' => $product['image_url'],
            'category_id' => $product['category_id'], // Assurer que tu as ces informations
            'supplier_id' => $product['supplier_id'],
        ]);

        return redirect()->route('product.index')->with('success', 'Produit ajouté avec succès.');
    }

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

    function splitAndTrim(string $input, string $delimiter = ','): array
    {
        // Séparer la chaîne en un tableau
        $array = explode($delimiter, $input);

        // Nettoyer les espaces inutiles autour de chaque élément
        return array_map('trim', $array);
    }

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
