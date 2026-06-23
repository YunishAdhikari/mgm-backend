<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HousekeepingRoomAllocation extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_status_update_id',
        'room_id',
        'assigned_to',
        'assigned_by',
        'allocation_date',
        'cleaning_status',
        'estimated_minutes',
        'notes',
        'started_at',
        'cleaned_at',
        'inspected_at',
        'inspected_by',

        
    ];

    protected $casts = [
        'allocation_date' => 'date',
        'started_at' => 'datetime',
        'cleaned_at' => 'datetime',
        'inspected_at' => 'datetime',
        
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

    public function roomStatusUpdate()
    {
        return $this->belongsTo(RoomStatusUpdate::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function inspectedBy()
{
    return $this->belongsTo(User::class, 'inspected_by');
}
}