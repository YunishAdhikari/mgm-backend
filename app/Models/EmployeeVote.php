<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeVote extends Model
{
        protected $fillable = [
        'poll_id',
        'voter_id',
        'employee_id',
        'points',
        'reason',
    ];

    public function poll()
    {
        return $this->belongsTo(EmployeeVotePoll::class, 'poll_id');
    }

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
