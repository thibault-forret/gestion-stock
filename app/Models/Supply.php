<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    const SUPPLY_STATUS_IN_PROGRESS = 'IN PROGRESS';
    const SUPPLY_STATUS_DELIVERED = 'DELIVERED';

    protected $fillable = [
        'user_id',
        'supplier_id',
        'warehouse_id',
        'supply_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
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

    // Permet de calculer le prix total de la commande
    public function calculateTotalPrice()
    {
        return $this->supplyLines->reduce(function ($total, $line) {
            return $total + ($line->quantity_supplied * $line->unit_price);
        }, 0);
    }
}