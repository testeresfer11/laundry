<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Middleware\Customer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes,Notifiable,HasApiTokens,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $appends = ['full_name'];
    protected $fillable = [
        'role_id',
        'uuid',
        'first_name',
        'last_name',
        'email',
        'password',
        'provider',
        'provider_id',
        'customer_id',
        'live_latitude',
        'live_longitude',
        'is_email_verified',
        'lang',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getFullNameAttribute()
    {
        return ucwords("{$this->first_name} {$this->last_name}");
    }
    public function role(): BelongsTo
    {
        return $this->BelongsTo(Role::class);
    }

    public function userDetail(): HasOne
    {
        return $this->HasOne(UserDetail::class);
    }

    public function wallet(): HasOne
    {
        return $this->HasOne(Wallet::class);
    }

    public function addressDetail(): HasOne
    {
        return $this->HasOne(CustomerAddress::class);
    }

    public function userAddress(): HasOne
    {
        return $this->HasOne(CustomerAddress::class)->where('default',1);
    }

    public function driverDetail(): HasOne
    {
        return $this->HasOne(DriverDetail::class);
    }

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $user_type_id = 2;
            
            $latestUser = user::orderBy('id', 'desc')->first();
    
            if ($latestUser) {
                $latestId = intval(substr($latestUser->uuid, -5));
                $newId = $latestId + 1;
                $model->uuid = $user_type_id . '00' . date('y') . str_pad($newId, 5, '0', STR_PAD_LEFT);
            } else {
                $model->uuid = $user_type_id . '00' . date('y') . '00001'; 
            }
        });
    }
}
