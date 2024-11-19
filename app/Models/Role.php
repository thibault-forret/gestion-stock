<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'role_name',
        'role_description',
    ];

    // Chaque rôle peut avoir plusieurs utilisateurs
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
