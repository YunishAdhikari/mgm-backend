<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealForecast extends Model
{
    protected $fillable = [
        'forecast_date',
        'total_breakfast',
        'total_dinner',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'forecast_date' => 'date',
    ];

    public function groups()
    {
        return $this->hasMany(MealForecastGroup::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}