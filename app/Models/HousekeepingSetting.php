<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HousekeepingSetting extends Model
{
    protected $fillable = [
        'hotel_id',
        'departure_minutes',
        'stay_minutes',
        'room_move_minutes',
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
}