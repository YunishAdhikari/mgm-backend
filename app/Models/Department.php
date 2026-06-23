<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'hotel_id',
        'name',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function maintenanceJobs()
    {
        return $this->hasMany(MaintenanceJob::class);
    }
}