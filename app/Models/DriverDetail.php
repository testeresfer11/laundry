<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverDetail extends Model
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
        'license_number',
        'dob',
        'vehicle_type_id',
        'description',
        'dob'
    ];

    public function vehicle(): BelongsTo
    {
        return $this->BelongsTo(Vehicle::class,'vehicle_type_id');
    }

    public function user()
    {
        return $this->BelongsTo(User::class); // The inverse of the relationship
    }
}
