<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingReview extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'driver_id',
        'type',
        'rating',
        'review',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
