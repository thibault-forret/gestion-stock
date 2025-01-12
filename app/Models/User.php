<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'last_name',
        'first_name',
        'username',
        'email',
        'password',
        'role_id',
    ];

    // Chaque utilisateur est associé à un rôle
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    // Chaque utilisateur peut faire plusieurs commandes
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Chaque utilisateur peut faire plusieurs approvisionnements
    public function supplies()
    {
        return $this->hasMany(Supply::class);
    }

    // Chaque utilisateur peut avoir plusieurs mouvements de stock
    public function stock_movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Chaque utilisateur peut être responsable d'un seul entrepôts
    public function warehouseManager()
    {
        return $this->hasOne(Warehouse::class);
    }

    // Chaque utilisateur peut être responsable d'un seul magasin
    public function storeManager()
    {
        return $this->hasOne(Store::class);
    }

    // Chaque utilisateur peut appartenir à un entrepôt
    public function warehouseUser()
    {
        return $this->hasOne(UserWarehouse::class);
    }

    // Chaque utilisateur peut appartenir à un magasin
    public function storeUser()
    {
        return $this->hasOne(UserStore::class);
    }
}
