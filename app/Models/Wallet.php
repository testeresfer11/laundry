<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Wallet extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'wallet_id',
        'user_id',
        'amount'
    ];
 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function walletHistory(): HasMany
    {
        return $this->hasMany(WalletHistory::class)->orderBy('id','desc');
    }

    protected static function booted()
    {
        parent::boot();

        self::creating(function($wallet){
            $wallet->wallet_id = "WALLET".Auth::id()."-".date("Ymd")."-".strtoupper(Str::random(8));
        });
    }
}
