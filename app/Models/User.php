<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'last_name',
        'first_name',
        'user_email',
        'user_password',
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

    // Chaque utilisateur peut avoir plusieurs mouvements de stock
    public function stock_movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Chaque utilisateur peut être responsable d'un seul entrepôts
    public function warehouse()
    {
        return $this->hasOne(Warehouse::class);
    }

    // Chaque utilisateur peut être responsable d'un seul magasin
    public function store()
    {
        return $this->hasOne(Store::class);
    }
}
