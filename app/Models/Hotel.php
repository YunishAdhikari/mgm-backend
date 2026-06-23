<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'postcode',
        'logo',
        'status',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function roomStatusUpdates()
    {
        return $this->hasMany(RoomStatusUpdate::class);
    }

    public function housekeepingSettings()
    {
        return $this->hasMany(HousekeepingSetting::class);
    }

    public function restaurantBookings()
{
    return $this->hasManyThrough(
        RestaurantBooking::class,
        Restaurant::class,
        'hotel_id',
        'restaurant_id',
        'id',
        'id'
    );
}

public function dailyOperations()
{
    return $this->hasMany(DailyOperation::class);
}
    public function housekeepingRoomAllocations()
    {
        return $this->hasMany(HousekeepingRoomAllocation::class);
    }

    public function maintenanceJobs()
    {
        return $this->hasMany(MaintenanceJob::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}