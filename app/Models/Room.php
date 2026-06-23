<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_number',
        'room_type_id',
        'floor',
        'max_occupancy',
        'status',
        'housekeeping_status',
        'maintenance_status',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function statusUpdates()
    {
        return $this->hasMany(RoomStatusUpdate::class);
    }

    public function housekeepingAllocations()
    {
        return $this->hasMany(HousekeepingRoomAllocation::class);
    }
}