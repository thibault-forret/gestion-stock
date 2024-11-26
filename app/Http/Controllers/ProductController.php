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

        // Assurez-vous que $result contient des résultats valides
        if (!empty($result)) {
            foreach ($result as $document) {
                $product = $document->getData();  // Accède aux données du produit

                // Vérification des données essentielles du produit
                if (!isset($product['product_name']) || 
                    !isset($product['image_url']) || 
                    !isset($product['categories']) || 
                    !isset($product['brands']) ||
                    empty($product['product_name']) || 
                    empty($product['image_url']) || 
                    empty($product['categories']) ||
                    empty($product['brands'])
                ) {
                    continue;  // Passer au produit suivant si les données sont manquantes ou vides
                }

                $productName = $product['product_name'];
                $imageUrl = $product['image_url'];

                // Vérifier si le produit est déjà dans l'entrepôt
                if ($warehouseProducts->contains('product_name', $productName)) {
                    continue;  // Passer au produit suivant si le produit est déjà dans l'entrepôt
                }

                // Récupérer toutes les catégories présentes
                $categories = Category::all();
                $suppliers = Supplier::all();

                $productCategories = $product['categories'];
                $productBrands = $product['brands'];

                // Séparer la chaîne en un tableau, en utilisant la virgule comme délimiteur
                $productBrands = explode(',', $productBrands);
                $productBrands = array_map('trim', $productBrands);

                // Trouver les catégories identiques entre les catégories du produit et les catégories de la base de données
                $identicalSuppliers = [];
                foreach ($productBrands as $brand) {
                    if (in_array($brand, $suppliers->pluck('supplier_name')->toArray())) {
                        $identicalSuppliers[] = $brand;
                    }
                }

                // Passer au produit suivant si aucun fournisseur n'a été trouvée
                if (empty($identicalSuppliers)) {
                    continue;
                }

                // Séparer la chaîne en un tableau, en utilisant la virgule comme délimiteur
                $productCategories = explode(',', $productCategories);
                $productCategories = array_map('trim', $productCategories);

                // Trouver les catégories identiques entre les catégories du produit et les catégories de la base de données
                $identicalCategories = [];
                foreach ($productCategories as $category) {
                    if (in_array($category, $categories->pluck('category_name')->toArray())) {
                        $identicalCategories[] = $category;
                    }
                }

                // Passer au produit suivant si aucune catégorie n'a été trouvée
                if (empty($identicalCategories)) {
                    continue;
                }

                // Récupérer le fournisseur et la catégorie correspondant à la requête
                $supplier = Supplier::whereIn('supplier_name', $identicalSuppliers)->first(); 
                $categories = Category::whereIn('category_name', $identicalCategories)->get();

                // -------------------------------------

                // Voir si ne peut pas récupérer tous les fournisseurs du produit pour ensuite avoir 
                // le choix entre commander chez un fournisseur ou un autre

                // Problème : C'est sur les marques ayant des dénominations différentes mais 
                // qui sont en réalité la même marque, par exemple : Marque repère et Délisse (produits laitiers)

                // -------------------------------------

                // Ajouter le produit au tableau
                $products[] = [
                    'name' => $productName,
                    'image_url' => $imageUrl,
                    'supplier' => $supplier,
                    'categories' => $categories,
                ];
            }
        }

        // Récupérer les catégories et les fournisseurs pour la vue
        $categories = Category::all();
        $suppliers = Supplier::all();

        // Retourner la vue avec les produits, les catégories et les fournisseurs
        return view('pages.warehouse.choose-new-product', compact('categories', 'suppliers', 'products'));
    }
}
