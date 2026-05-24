<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuffetMenuItem extends Model
{
    // BuffetMenuItem.php
protected $fillable = [
    'buffet_menu_id',
    'menu_item_id',
];

public function buffetMenu()
{
    return $this->belongsTo(BuffetMenu::class);
}

public function menuItem()
{
    return $this->belongsTo(MenuItem::class);
}
}
