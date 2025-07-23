<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'help_id',
        'user_id',
        'response',
        'response_image',
    ];
}
