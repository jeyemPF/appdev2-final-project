<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $fillable = [
        'name',
        'description',
        'price',
        'is_available',
    ];

    
    public function getPriceAttribute($value)
    {
        return '₱' . number_format($value, 2);
    }
}
