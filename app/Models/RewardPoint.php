<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardPoint extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'order_id',
        'order_date',
        'received_points',
        'received_date',
        'expired_points',
        'expired_date',
        'available_points',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
