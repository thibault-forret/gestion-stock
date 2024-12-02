<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductCategory extends Pivot
{
    protected $primaryKey = ['product_id', 'category_id'];

    public $incrementing = false; 
    public $timestamps = false;


    protected $fillable = ['product_id', 'category_id'];

}