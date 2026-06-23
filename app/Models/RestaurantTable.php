<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    protected $fillable = [
        'restaurant_id',
        'table_name',
        'capacity',
        'table_shape',
        'status',
        'position_x',
        'position_y',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function bookings()
    {
        return $this->hasMany(RestaurantBooking::class);
    }

    public function groupBuffetBookings()
    {
        return $this->belongsToMany(
            GroupBuffetBooking::class,
            'group_buffet_booking_tables'
        );
    }
}