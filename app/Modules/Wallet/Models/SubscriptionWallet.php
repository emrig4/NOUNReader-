<?php

namespace App\Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

class SubscriptionWallet extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->reference = (string) Uuid::generate(4);
        });
    }
    
    protected static function newFactory()
    {
        return \App\Modules\Wallet\Database\factories\SubscriptionWalletFactory::new();
    }
}
