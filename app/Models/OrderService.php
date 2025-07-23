<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderService extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'service_id',
        'variant_id',
        'amount',
        'qty',
    ];

    public function service(): BelongsTo
    {
        return $this->BelongsTo(Service::class,'service_id');
    }
    public function variant(): BelongsTo
    {
        return $this->BelongsTo(Variant::class,'variant_id');
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
