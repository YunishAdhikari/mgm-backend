<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\MobileResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'hotel_id',
        'name',
        'email',
        'password',
        'phone',
        'employee_code',
        'job_title',
        'role_id',
        'image',
        'department_id',
        'status',
        'fcm_token',
        'last_login_at',
        'last_login_ip',
    ];

    protected $appends = ['image_url'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public function getImageUrlAttribute()
    {
        if (! $this->image) {
            return null;
        }

        return asset('storage/' . $this->image);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function isAdmin()
    {
        return $this->role && $this->role->name === 'admin';
    }

    public function isManager()
    {
        return $this->role && $this->role->name === 'manager';
    }

    public function isSupervisor()
    {
        return $this->role && $this->role->name === 'supervisor';
    }

    public function isStaff()
    {
        return $this->role && $this->role->name === 'staff';
    }

    public function holidayRequests()
    {
        return $this->hasMany(HolidayRequest::class);
    }

    public function rotaShifts()
    {
        return $this->hasMany(RotaShift::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new MobileResetPasswordNotification($token));
    }
}