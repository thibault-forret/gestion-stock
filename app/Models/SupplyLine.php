<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyLine extends Model
{
    protected $fillable = [
        'supply_id',
        'product_id',
        'quantity_supplied',
    ];

    // Chaque ligne d'approvisionnement est associée à un approvisionnement
    public function supply()
    {
        return $this->belongsTo(Supply::class, 'supply_id', 'id');
    }

    // Chaque ligne d'approvisionnement est associée à un produit
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
