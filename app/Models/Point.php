<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Point extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'offer_name',
        'description',
        'points',
        'offer_type',
        'start_date',
        'end_date',
        'max_order_amount',
        'status',
    ];

}
