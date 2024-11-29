<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'product_name',
        'image_url',
        'reference_price',
        'restock_threshold',
        'alert_threshold',
        // 'category_id',
    ];

    // Chaque produit est associé à une catégorie
    // public function category()
    // {
    //     return $this->belongsTo(Category::class, 'category_id', 'id');
    // }

    // Chaque produit est associé à une ou plusieurs catégories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id')->using(ProductCategory::class);
    }

    // Chaque produit est associé à une ou plusieurs commandes
    public function orderLines()
    {
        return $this->hasMany(OrderLine::class);
    }

    // Chaque produit est associé à un ou plusieurs stock
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    // Chaque produit est associé à un ou plusieurs mouvements de stock
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Chaque produit est associé à un ou plusieurs lignes d'approvisionnement
    public function supplyLines()
    {
        return $this->hasMany(SupplyLine::class);
    }
}
