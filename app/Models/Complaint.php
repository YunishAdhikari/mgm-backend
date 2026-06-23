<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
    'hotel_id',
    'guest_name',
    'email',
    'phone',
    'room_number',
    'type',
    'category',
    'title',
    'description',
    'image',
    'priority',
    'status',
    'created_by',
    'handled_by',
    'handled_at',
    'internal_note',
];
protected $casts = [
    'handled_at' => 'datetime',
];

    protected $appends = ['image_url'];

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }


    public function hotel()
{
    return $this->belongsTo(Hotel::class);
}


    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return asset('uploads/complaints/' . $this->image);
    }

    public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}


}