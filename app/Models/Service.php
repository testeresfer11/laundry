<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;
     
    protected $fillable = [
        'name',
        'image',
        'description',
        'status'
    ];

    public function serviceVariant()
    {
        return $this->hasMany(ServiceVariant::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_services', 'service_id', 'order_id');
    }

}
