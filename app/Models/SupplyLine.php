<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyLine extends Model
{
    protected $fillable = [
        'supplier_id',
        'product_id',
        'warehouse_id',
        'supply_date',
        'quantity_supplied',
        'unit_price',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
