<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    protected $appends = ['service_name'];
    const ORDER_REQUESTED       = "Requested";
    const ORDER_ACCEPTED        = "Accepted";
    const ORDER_CANCELLED       = "Cancelled";
    const ASSIGN_DRIVER         = "Assign Pickup Driver";
    const ORDER_APPROVED        = "Approved";
    const ORDER_PAID            = "Paid";
    const ORDER_IN_PROGRESS     = "In Progress";
    const ORDER_READY           = "Ready";
    const ORDER_DELIVERED       = "Delivered";
    const ORDER_COMPLETED       = "Completed";
    const ASSIGN_DELIVERY_DRIVER = "Assign Delivery Driver";
    const ON_THE_WAY            = "On the way";
    const REACHED               = "Reached";
    const PICKUP                = "Pickup";
    const RECEIVED              = "Received";
    const ORDER_REJECTED        = "Rejected";

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];
    
    protected $fillable = [
        'order_id',
        'user_id',
        'total_amount',
        'pickup_address',
        'delivery_address',
        'order_type',
        'pickup_driver_id',
        'delivery_driver_id',
        'pickup_date',
        'delivery_date',
        'pickup_time',
        'delivery_time',
        'delivery_status',
        'delivery_code',
        'promotion_id',
        'status',
        'redeemed_points',
    ];

    public function service()
    {
        return $this->belongsToMany( Service::class, 'order_services', 'order_id', 'service_id');
    }

    public function getServiceNameAttribute()
    {
        return $this->service->pluck('name')->unique()->values()->toArray();
    }

    public function user(): BelongsTo
    {
        return $this->BelongsTo(user::class,'user_id')->withTrashed();
    }

    public function pickupDriver(): BelongsTo
    {
        return $this->BelongsTo(user::class,'pickup_driver_id')
        ->with(['driverDetail' => function ($query) {
            $query->select('user_id', 'phone_number');
        }])
        ->with(['driverDetail'])->withTrashed();
    }

    public function deliveryDriver(): BelongsTo
    {
        return $this->BelongsTo(user::class,'delivery_driver_id')
        ->with(['driverDetail' => function ($query) {
            $query->select('user_id', 'phone_number');
        }])
        ->with(['driverDetail'])->withTrashed();
    }
    public function payment(): HasOne
    {
        return $this->HasOne(Payment::class);
    }

    public function declineReason(): HasOne
    {
        return $this->HasOne(OrderDeclineReason::class);
    }

    public function services(): HasMany
    {
        return $this->HasMany(OrderService::class);
    }

    public function taxes(): HasMany
    {
        return $this->HasMany(OrderTax::class);
    }

    protected static function booted()
    {
        parent::boot();

        self::creating(function($order){
            $order->order_id = "ORD".Auth::id()."-".date("Ymd")."-".strtoupper(Str::random(8));
        });
    }
}
