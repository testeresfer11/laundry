<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'profile',
        'phone_number',
        'address',
        'country_code',
        'country_short_code',
        'gender',
        'dob',
        'address2',
        'zip_code2'
    ];
}
