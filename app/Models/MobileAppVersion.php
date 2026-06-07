<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileAppVersion extends Model
{
    protected $fillable = [
        'platform',
        'version_name',
        'version_code',
        'apk_path',
        'release_notes',
        'is_latest',
        'uploaded_by',
    ];

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}