<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStore extends Model
{
    protected $fillable = [
        'user_id',
        'store_id',
        'responsibility_start_date',
        'responsibility_end_date',
    ];

    // Chaque utilisateur est associé à un magasin
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Chaque utilisateur est associé à un magasin
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
