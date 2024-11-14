<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'warehouse_name',
        'warehouse_address',
        'capacity',
        'warehouse_manager_id',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
