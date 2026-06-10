<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupBuffetBooking extends Model
{
    protected $fillable = [
        'group_name',
        'agent_name',
        'buffet_date',
        'buffet_time',
        'pax',
        'meal_type',
        'price_per_person',
        'total_amount',
        'payment_status',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'buffet_date' => 'date',
        'buffet_time' => 'datetime:H:i',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tables()
{
    return $this->belongsToMany(
        RestaurantTable::class,
        'group_buffet_booking_tables'
    );
}
}