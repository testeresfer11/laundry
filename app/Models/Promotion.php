<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable = [
        'discount',
        'title',
        'exp_date',
        'min_order',
        'max_discount',
        'image',
        'description',
        'status',
    ];
}
