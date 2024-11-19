<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity_available',
    ];

    // Chaque stock est associé à un produit
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // Chaque stock est associé à un entrepôt
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    // Ajout du stock de chaque magasin ?
}
