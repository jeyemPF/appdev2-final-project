<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $fillable = ['name', 'price', 'is_available'];
// Menu.php

public function getPriceAttribute($value)
{
    return $value; 
}

public function orderItems()
{
    return $this->hasMany(OrderItem::class, 'items_id');
}

    

    
}
