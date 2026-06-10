<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceJob extends Model
{
    protected $fillable = [
        'reported_by',
        'department_id',
        'assigned_to',
        'title',
        'description',
        'location',
        'room_number',
        'image',
        'priority',
        'status',
        'reported_date',
        'completed_date',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return asset('uploads/maintenance/' . $this->image);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}