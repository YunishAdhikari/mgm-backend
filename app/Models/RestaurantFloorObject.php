<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantFloorObject extends Model
{
    protected $fillable = [
        'restaurant_id',
        'type',
        'label',
        'position_x',
        'position_y',
        'width',
        'height',
        'rotation',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}