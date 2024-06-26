<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Specify the fillable attributes for mass assignment
    protected $fillable = [
        'payment_date',
        'amount',
        'payment_method',
        'order_id',
    ];

    // Define the relationship between Payment and Order models
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
