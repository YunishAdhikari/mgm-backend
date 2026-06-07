<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomStatusUpdate extends Model
{
    protected $fillable = [
        'room_id',
        'status_date',
        'status',
        'notes',
        'updated_by',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}