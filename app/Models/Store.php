<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'store_name',
        'store_address',
        'store_email',
        'store_phone',
        'capacity',
        'warehouse_id',
        'user_id',
    ];

    // Chaque magasin est associé à un utilisateur, qui est le manager de l'entrepôt
    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Chaque magasin est associé à un entrepôt
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // Chaque magasin peut avoir plusieurs commandes
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
