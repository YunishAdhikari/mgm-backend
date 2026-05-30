<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceQrToken extends Model
{
    protected $fillable = [
        'token',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}