<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    protected $fillable = [
        'supply_id',
        'product_id',
        'quantity_supplied',
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class, 'supply_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
