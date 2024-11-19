<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'user_id',
        'quantity_moved',
        'movement_type',
        'movement_date',
        'movement_status',
        'movement_source',
    ];

    // Chaque mouvement de stock est associé à un produit
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // Chaque mouvement de stock est associé à un entrepôt
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    // Chaque mouvement de stock est associé à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Ajout du stock de chaque magasin ?
}
