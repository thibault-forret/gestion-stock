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

    // Chaque fournisseur peut avoir plusieurs approvisionnements
    public function supplies()
    {
        return $this->hasMany(Supply::class, 'supplier_id', 'id');
    }
}
