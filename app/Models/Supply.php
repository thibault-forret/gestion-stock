<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    protected $fillable = [
        'supplier_id',
        'warehouse_id',
        'supply_date',
    ];
    
    // Chaque approvisionnement est associé à un fournisseur
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    
    // Chaque approvisionnement est associé à un entrepôt
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    // Chaque approvisionnement est associé à une facture
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // Chaque approvisionnement est associé à une ou plusieurs lignes d'approvisionnement
    public function supplyLines()
    {
        return $this->hasMany(SupplyLine::class);
    }
}