<?php

namespace App\Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

class CreditWalletHolding extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'ranc',
        'user_id',
        'status', // processed, pending, rejected
        'ranc',
        'reference',
        'credit_wallet_id'

    ];

    public function creditWallet (){
        return $this->belongsTo(CreditWalletTransaction::class, 'reference' );
    }


    public function getAmountAttribute(){
        $currency = $this->attributes['currency'];
        $result = fiat_equivalent($this->ranc, $currency);
        return $result;
    }

    /**
     *  Setup model event hooks
    */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            
        });

    }
    
    protected static function newFactory()
    {
        return \App\Modules\Wallet\Database\factories\CreditWalletTransactionFactory::new();
    }
}
