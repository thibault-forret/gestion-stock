<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'warehouse_id',
        'store_id',
        'order_date',
        'order_status',
    ];

    // Chaque commande est associée à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Chaque commande est associée à un entrepôt
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    // Chaque commande est associée à un magasin
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    // Chaque commande est associée à une facture
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // Chaque commande est associée à une ou plusieurs lignes de commande
    public function orderLines()
    {
        return $this->hasMany(OrderLine::class);
    }
}
