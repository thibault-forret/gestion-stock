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

        $products = [];

        $user = auth()->user();

        // Récupérer l'entrepôt de l'utilisateur
        $warehouse = $user->warehouseUser->warehouse;

        // Get all products in the warehouse
        $warehouseProducts = $warehouse->stock->map(function ($stock) {
            return $stock->product;
        });

        // Récupérer toutes les catégories et tous les fournisseurs
        $categories = Category::all();
        $suppliers = Supplier::all();

        // Assurez-vous que $result contient des résultats valides
        if (!empty($result)) {
            foreach ($result as $document) {
                $product = $document->getData();  // Accède aux données du produit

                // Vérification des données essentielles du produit
                if ($this->verifyDataProduct($product)
                ) {
                    continue;  // Passer au produit suivant si les données sont manquantes ou vides
                }

                // Vérifier si le produit est déjà dans l'entrepôt
                if ($warehouseProducts->contains('id', $product['id'])) {
                    continue;  // Passer au produit suivant si le produit est déjà dans l'entrepôt
                }

                $productCategories = $product['categories'];
                $productBrands = $product['brands'];

                // Séparer la chaîne en un tableau, en utilisant la virgule comme délimiteur
                $productBrands = explode(',', $productBrands);
                $productBrands = array_map('trim', $productBrands);

                $identicalSuppliers = $this->searchIdenticalSuppliers($productBrands, $suppliers);

                // Passer au produit suivant si aucun fournisseur n'a été trouvée
                if (empty($identicalSuppliers)) {
                    continue;
                }

                // Séparer la chaîne en un tableau, en utilisant la virgule comme délimiteur
                $productCategories = explode(',', $productCategories);
                $productCategories = array_map('trim', $productCategories);

                $identicalCategories = $this->searchIdenticalCategories($productCategories, $categories);                

                // Passer au produit suivant si aucune catégorie n'a été trouvée
                if (empty($identicalCategories)) {
                    continue;
                }

                // Récupérer le fournisseur et la catégorie correspondant à la requête
                $dataSupplier = Supplier::whereIn('supplier_name', $identicalSuppliers)->first(); 
                $dataCategories = Category::whereIn('category_name', $identicalCategories)->get();

                // -------------------------------------

                // Voir si ne peut pas récupérer tous les fournisseurs du produit pour ensuite avoir 
                // le choix entre commander chez un fournisseur ou un autre

                // Problème : C'est sur les marques ayant des dénominations différentes mais 
                // qui sont en réalité la même marque, par exemple : Marque repère et Délisse (produits laitiers)

                // -------------------------------------

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
            'product_id' => 'required|integer', // Vérifie que l'ID est valide
        ],
        [
            'product_id.required' => 'L\'ID du produit est requis. Veuillez réessayer.',
            'product_id.integer' => 'L\'ID du produit doit être un entier. Veuillez réessayer.',
        ]);

        $productId = $request->input('product_id');
        
        // Récupérer les informations du produit
        $product = OpenFoodFacts::barcode($productId);

        dd($product);

        if (empty($product)) {
            return redirect()->route('product.index')->with('error', 'Le produit n\'a pas été trouvé. Veuillez réessayer.');
        }

        // Valider les informations du produit
        // Faire une fonction pour valider les informations du produit (fractionner searchProducts)

        // Vérifier si le produit existe déjà dans la base de données
        if (Product::where('api_product_id', $product['id'])->exists()) {
            // Ajouter le produit au stock
        }

        // Ajouter le produit à la base de données + au stock de l'entrepôt
        Product::create([
            'name' => $product['name'],
            'image_url' => $product['image_url'],
            'category_id' => $product['category_id'], // Assurer que tu as ces informations
            'supplier_id' => $product['supplier_id'],
            'api_product_id' => $product['id'], // Conserver l'ID de l'API pour référence
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

    private function verifyDataProduct($product)
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

    private function validateProduct($product)
    {
        // Valider les informations du produit
        // Vérifier si le produit existe déjà dans la base de données
        // Vérifier si le produit est déjà dans l'entrepôt
        // Vérifier si le produit est déjà dans le stock
        // Vérifier si le produit est déjà dans la commande
    }
}
