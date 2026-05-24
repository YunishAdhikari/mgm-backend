<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeVotePoll extends Model
{
    protected $fillable = [
        'month',
        'status',
        'winner_id',
    ];

    public function votes()
    {
        return $this->hasMany(EmployeeVote::class, 'poll_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }
}
