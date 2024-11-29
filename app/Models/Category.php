<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_name',
        'category_description',
    ];

    // Une catégorie peut avoir plusieurs produits
    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }

    // Une catégorie peut avoir plusieurs produits
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id')->using(ProductCategory::class);
    }
}
