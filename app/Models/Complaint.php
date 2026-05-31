<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
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
        'handled_by',
        'internal_note',
    ];

    protected $appends = ['image_url'];

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
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