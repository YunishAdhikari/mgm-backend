<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RotaShift extends Model
{
protected $fillable = [
    'hotel_id',
    'user_id',
    'department_id',
    'shift_date',
    'shift_type',
    'start_time',
    'end_time',
    'break_minutes',
    'status',
    'notes',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function hotel()
{
    return $this->belongsTo(Hotel::class);
}

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}
}