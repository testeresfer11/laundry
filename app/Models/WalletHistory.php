<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletHistory extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'wallet_id',
        'message',
        'amount',
        'payment_method',
        'payment_status'
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
