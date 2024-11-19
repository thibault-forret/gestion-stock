<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'invoice_status',
        'order_id',
        'supply_id'
    ];

    // Chaque facture est associée à une commande
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    // Chaque facture est associée à un approvisionnement
    public function supply()
    {
        return $this->belongsTo(Supply::class, 'supply_id', 'id');
    }
}
