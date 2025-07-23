<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddress extends Model
{
    use HasFactory,SoftDeletes; 
    protected $fillable = [
        'user_id',
        'address',
        'landmark',
        'house_no',
        'city',
        'state',
        'country',
        'lat',
        'long',
        'status',
        'default',
        'type'
    ];
}
