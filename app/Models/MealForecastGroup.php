<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealForecastGroup extends Model
{
    protected $fillable = [
        'meal_forecast_id',
        'forecast_group_id',
        'package_type',
        'pax',
        'check_in_date',
        'check_out_date',
        'notes',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
    ];

    public function mealForecast()
    {
        return $this->belongsTo(MealForecast::class);
    }

    public function forecastGroup()
    {
        return $this->belongsTo(ForecastGroup::class);
    }
}