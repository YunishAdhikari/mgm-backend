<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuffetSale extends Model
{
    // BuffetSale.php
protected $fillable = [
    'buffet_menu_id',
    'user_id',
    'sale_date',
    'pax',
    'note',
];

public function buffetMenu()
{
    return $this->belongsTo(BuffetMenu::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}
}
