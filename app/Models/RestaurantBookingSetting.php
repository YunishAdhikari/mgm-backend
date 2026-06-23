<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantBookingSetting extends Model
{
    protected $fillable = [
        'restaurant_id',
        'booking_type',
        'opening_time',
        'closing_time',
        'slot_duration_minutes',
        'slot_interval_minutes',
        'max_pax_per_slot',
        'allow_overbooking',
        'is_active',
    ];

    protected $casts = [
        'allow_overbooking' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}