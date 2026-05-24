<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
    'department_id',
    'name',
    'description',
    'selling_price',
    'is_active',
];

public function department()
{
    return $this->belongsTo(Department::class);
}

public function ingredients()
{
    return $this->hasMany(RecipeIngredient::class);
}
}
