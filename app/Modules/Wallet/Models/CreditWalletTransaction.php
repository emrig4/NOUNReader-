<?php

namespace App\Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Carbon;

class CreditWalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'reference',
        'remark',
        'amount',
        'currency',
        'type',
        'status', // processed, pending, rejected
        'ranc',
        'credit_wallet_id'

    ];


    public function walletHolding(){
        return $this->hasOne(CreditWalletHolding::class, 'reference', 'reference' );
    }


    public function getAmountAttribute(){
        $currency = $this->currency();
        $result = fiat_equivalent($this->ranc, $currency);
        return $result;
    }

    public function getUpdatedAtAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->getAttributes()['updated_at'])->format('Y-m-d H:i:s');
    }

    public function getCurrencyAttribute(){
        return $this->currency();
    }

    public function currency(){
        return session('currency') ? session('currency') : setting('default_currency');
    }

    /**
     *  Setup model event hooks
    */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->reference = random_int(00000000000, 99999999999); //(string) Uuid::generate(4);
        });

        // self::retrieved(function ($model) {
        //     $model->attributes['amount'] =  $model->attributes['ranc'];
        // });
    }
    
    protected static function newFactory()
    {
        return \App\Modules\Wallet\Database\factories\CreditWalletTransactionFactory::new();
    }
}
