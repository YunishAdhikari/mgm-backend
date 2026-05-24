<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HolidayRequest extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'approved_by',
        'manager_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    
}