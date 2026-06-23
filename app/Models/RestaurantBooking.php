<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantBooking extends Model
{
protected $fillable = [
    'restaurant_id',
    'booking_type',
    'restaurant_table_id',
    'guest_name',
    'guest_phone',
    'guest_email',
    'booking_date',
    'slot_start_time',
    'slot_end_time',
    'voucher_code',
    'voucher_amount',
    'voucher_note',
    'pax',
    'is_overbooking',
    'status',
    'special_request',
    'created_by',
];

protected $casts = [
    'booking_date' => 'date',
    'is_overbooking' => 'boolean',
];

public function table()
{
    return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
}

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function restaurant()
{
    return $this->belongsTo(Restaurant::class);
}
}
