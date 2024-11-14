<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWarehouse extends Model
{
    protected $fillable = [
        'user_id',
        'warehouse_id',
        'responsibility_start_date',
        'responsibility_end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
