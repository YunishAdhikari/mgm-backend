<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    protected $fillable = [
        'hotel_wifi_ip',
        'hotel_latitude',
        'hotel_longitude',
        'allowed_radius_meters',
        'is_ip_check_enabled',
        'is_location_check_enabled',
    ];
}