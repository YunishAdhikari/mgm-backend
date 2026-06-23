<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomStatusUpdate extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_id',
        'status_date',
        'status',
        'notes',
        'updated_by',
    ];

    protected $casts = [
        'status_date' => 'date',
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

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}