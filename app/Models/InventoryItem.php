<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
    'department_id',
    'name',
    'category',
    'quantity',
    'unit',
    'minimum_stock',
    'is_active',
];

public function department()
{
    return $this->belongsTo(Department::class);
}

public function transactions()
{
    return $this->hasMany(InventoryTransaction::class);
}
}
