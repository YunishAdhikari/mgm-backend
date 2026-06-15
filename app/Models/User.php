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


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role_id',
        'image',
        'department_id',
        'fcm_token',

    ];


        protected $appends = ['image_url'];

    public function getImageUrlAttribute()
{
    if (!$this->image) {
        return null;
    }

    return asset('storage/' . $this->image);
}
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
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
    return $this->hasMany(\App\Models\HolidayRequest::class);
}

public function rotaShifts()
{
    return $this->hasMany(\App\Models\RotaShift::class);
}


public function sendPasswordResetNotification($token): void
{
    $this->notify(new MobileResetPasswordNotification($token));
}
}
