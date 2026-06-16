<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function mealForecastGroups()
    {
        return $this->hasMany(MealForecastGroup::class);
    }
}