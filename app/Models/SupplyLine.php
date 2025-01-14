<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyLine extends Model
{
    protected $fillable = [
        'supply_id',
        'product_id',
        'quantity_supplied',
        'unit_price',
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

    // Ajout de quantité
    public function addQuantity(int $quantity)
    {
        $this->quantity_supplied += $quantity;
        return $this->save();
    }

    // Retrait de quantité
    public function removeQuantity(int $quantity)
    {
        $this->quantity_supplied -= $quantity;
        return $this->save();
    }
}
