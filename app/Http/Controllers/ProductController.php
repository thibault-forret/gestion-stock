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

    public function searchProducts(Request $request)
    {
        // Valider les données de la requête
        $request->validate([
            'supplier_name' => 'required|exists:suppliers,supplier_name',
            'category_name' => 'required|exists:categories,category_name',
        ], [
            'supplier_name.required' => 'Le nom du fournisseur est requis.',
            'supplier_name.exists' => 'Le fournisseur sélectionné n\'existe pas.',
            'category_name.required' => 'Le nom de la catégorie est requis.',
            'category_name.exists' => 'La catégorie sélectionnée n\'existe pas.',
        ]);

        $supplierName = $request->input('supplier_name');
        $categoryName = $request->input('category_name');

        $result = OpenFoodFacts::search("{$supplierName} {$categoryName}", 1, 50);

        $products = [];

        // Assurez-vous que $result contient des résultats valides
        if (!empty($result)) {
            foreach ($result as $document) {
                $product = $document->getData();  // Accède aux données du produit

                // Vérification des données essentielles du produit
                if (!isset($product['product_name']) || !isset($product['image_url']) || empty($product['product_name']) || empty($product['image_url'])) {
                    continue;  // Passer au produit suivant si les données sont manquantes ou vides
                }

                $productName = $product['product_name'];
                $imageUrl = $product['image_url'];
                
                // Récupérer le fournisseur et la catégorie correspondant à la requête
                $supplier = Supplier::where('supplier_name', $supplierName)->first();
                $category = Category::where('category_name', $categoryName)->first();

                // Ajouter le produit au tableau
                $products[] = [
                    'name' => $productName,
                    'image_url' => $imageUrl,
                    'supplier' => $supplier,
                    'category' => $category,
                ];
            }
        }

        // Récupérer les catégories et les fournisseurs pour la vue
        $categories = Category::all();
        $suppliers = Supplier::all();

        // Retourner la vue avec les produits, les catégories et les fournisseurs
        return view('pages.warehouse.choose-new-product', compact('categories', 'suppliers', 'products'));
    }


    // public function indexTest()
    // {
    //     $categories = Category::all();
    //     $suppliers = Supplier::all();

    //     $products = [];

    //     ini_set('memory_limit', '256M');

    //     $result = OpenFoodFacts::search("Pâtes sèches", 1, 100); 

    //     dd($result);

    //     foreach ($suppliers as $supplier) {
    //         $result = OpenFoodFacts::search("{$supplier->supplier_name}", 1, 50, 'asc'); 
            
    //         if ($result->searchCount() === 0) {
    //             continue; 
    //         }

    //         foreach ($result as $document) {
    //             $product = $document->getData();  // Accède aux données du produit

    //             if (!isset($product['product_name']) || !isset($product['brands']) || !isset($product['image_url']) || !isset($product['categories'])) {
    //                 continue;
    //             }

    //             if (empty($product['product_name']) || empty($product['brands']) || empty($product['image_url']) || empty($product['categories'])) {
    //                 continue;
    //             }
                
    //             $productName = $product['product_name'];
    //             $brands = $product['brands'];
    //             $imageUrl = $product['image_url'];
    //             $productCategories = $product['categories'];

    //             // Séparer la chaîne en un tableau, en utilisant la virgule comme délimiteur
    //             $productCategories = explode(',', $productCategories);
    //             $productCategories = array_map('trim', $productCategories);

    //             $category = null;
    //             foreach ($productCategories as $productCategory) {
    //                 if (in_array($productCategory, $categories->pluck('category_name')->toArray())) {
    //                     $category = Category::where('category_name', $productCategory)->first();
    //                     break;
    //                 }
    //             }

    //             if (!$category) {
    //                 continue;
    //             }

    //             $data = [
    //                 'product_name' => $productName,
    //                 'product_url' => $imageUrl,
    //                 'brands' => $brands,
    //                 'category_id' => $category->id,
    //             ];

    //             if(!in_array($data, $products)) {
    //                 $products[] = $data;
    //             }
    //         }
    //     }

    //     dd($products);

        
    //     // Filtrage des produits récupérés (par exemple, pour un fournisseur ou d'autres critères)
    //     $filteredProducts = array_filter($products, function($product) {
    //         // Exemple de filtrage par fournisseur (ajuste selon tes critères)
    //         return isset($product['brands']) && $product['brands'] === 'Panzani';
    //     });
        
    //     // Réindexe le tableau après filtrage (optionnel)
    //     $filteredProducts = array_values($filteredProducts);
        
    //     // Vérifie si des produits ont été trouvés après filtrage
    //     if (empty($filteredProducts)) {
    //         dd('Aucun produit trouvé après filtrage');
    //     }
        
    //     dd($filteredProducts);

    //     return view('pages.warehouse.products.choose-new-product', compact('products'));
    // }
}
