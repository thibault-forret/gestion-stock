<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity_ordered',
        'unit_price',
    ];

    // Chaque ligne de commande est associée à une commande
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    // Chaque ligne de commande est associée à un produit
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
