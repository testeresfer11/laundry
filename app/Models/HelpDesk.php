<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpDesk extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'ticket_id',
        'user_id',
        'title',
        'description',
        'priority',
        'status',
    ];

    protected static function booted()
    {
        parent::boot();

        self::creating(function($model){
            do {
                $ticket_id = mt_rand(100000, 999999);
            } while (self::where('ticket_id', $ticket_id)->exists());

            $model->ticket_id = $ticket_id;
        });
    }

    public function response(): HasMany
    {
        return $this->HasMany(QueryResponse::class,'help_id','id');
    }

}
