<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyOperation extends Model
{
    protected $fillable = [

        'hotel_id',

        'operation_date',

        'snapshot',

        'arrivals',

        'departures',

        'stayovers',

        'ooo_rooms',

        'ooi_rooms',

        'vip_arrivals',

        'group_arrivals',

        'group_departures',

        'expected_breakfast',

        'expected_dinner',

        'notes',

        'is_finalised',

        'created_by',
    ];

protected $casts = [
    'operation_date' => 'date',
    'is_finalised' => 'boolean',
];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Calculated Attributes
    |--------------------------------------------------------------------------
    */

    public function getAvailableRoomsAttribute()
    {
        $totalRooms = $this->hotel?->rooms()
            ->where('is_active', true)
            ->count() ?? 0;

        return max(
            0,
            $totalRooms
            -
            $this->ooo_rooms
            -
            $this->ooi_rooms
        );
    }

    public function getOccupiedRoomsAttribute()
    {
        return $this->arrivals + $this->stayovers;
    }

    public function getOccupancyPercentageAttribute()
    {
        if ($this->available_rooms == 0) {
            return 0;
        }

        return round(
            ($this->occupied_rooms / $this->available_rooms) * 100,
            1
        );
    }

    public function getPickupAttribute()
    {
        if ($this->snapshot !== 'PM') {
            return null;
        }

        $am = self::where('hotel_id', $this->hotel_id)
            ->whereDate('operation_date', $this->operation_date)
            ->where('snapshot', 'AM')
            ->first();

        if (!$am) {
            return null;
        }

        return $this->arrivals - $am->arrivals;
    }
}