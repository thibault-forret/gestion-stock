<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'supplier_name',
        'supplier_address',
        'supplier_phone',
        'supplier_email',
        'supplier_contact',
    ];
}
