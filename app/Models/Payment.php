<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'payment_type',
        'payment_id',
        'amount',
        'client_secret',
        'latest_charge',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
    
}
