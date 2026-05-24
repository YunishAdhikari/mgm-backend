<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuffetMenu extends Model
{
    // BuffetMenu.php
protected $fillable = [
    'department_id',
    'name',
    'service_type',
    'notes',
    'is_active',
];

public function items()
{
    return $this->hasMany(BuffetMenuItem::class);
}

public function sales()
{
    return $this->hasMany(BuffetSale::class);
}
}
