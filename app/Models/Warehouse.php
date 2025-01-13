<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'warehouse_name',
        'warehouse_address',
        'warehouse_email',
        'warehouse_phone',
        'capacity',
        'user_id',
    ];

    // Chaque entrepôt est associé à un utilisateur, qui est le manager de l'entrepôt
    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Chaque entrepôt peut avoir plusieurs produits en stock
    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    // Chaque entrepôt peut avoir plusieurs mouvements de stock
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Chaque entrepôt peut avoir plusieurs approvisionnements
    public function supplies()
    {
        return $this->hasMany(Supply::class);
    }

    // Chaque entrepôt peut être en charge de plusieurs magasins
    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}
