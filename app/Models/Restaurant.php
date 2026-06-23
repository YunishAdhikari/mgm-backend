<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
 protected $fillable = [
    'hotel_id',
    'name',
    'description',
    'is_active',
];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function tables()
    {
        return $this->hasMany(RestaurantTable::class);
    }
    public function floorObjects()
{
    return $this->hasMany(RestaurantFloorObject::class);
}
}