<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
    'room_number',
    'room_type_id',
    'floor',
    'max_occupancy',
    'is_active',
    'notes',
];

public function roomType()
{
    return $this->belongsTo(RoomType::class);
}

public function statusUpdates()
{
    return $this->hasMany(RoomStatusUpdate::class);
}
}

