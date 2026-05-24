<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryWastage extends Model
{
    // app/Models/InventoryWastage.php

protected $fillable = [
    'inventory_item_id',
    'user_id',
    'quantity',
    'reason',
    'note',
];

public function item()
{
    return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
}

public function user()
{
    return $this->belongsTo(User::class);
}
}
