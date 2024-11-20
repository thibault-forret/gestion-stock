<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenFoodFacts;
use App\Models\Product;
use App\Models\Category;

class ProductsController extends Controller
{
    public function createProducts()
    {
        $products = OpenFoodFacts::search('Pâtes sèches', 1, 10, 'popularity', 'asc');

        dd($products);

        // 'product_name' => $product->product_name,
        // 'product_description' => $product->image_url,
        // 'reference_price' => $product->brands,
        // 'categories' => $product->categories,

        $category_id = 0;

        foreach ($collection as $product) {
            $product = Product::create([
                'product_name' => $product->product_name,
                'product_description' => $product->image_url,
                'reference_price' => 0,
                'category_id' => $category_id,
            ]);
        }

        return view('pages.products.index');
    }
}
