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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }
}
