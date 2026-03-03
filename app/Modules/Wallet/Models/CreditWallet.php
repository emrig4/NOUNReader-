<?php

namespace App\Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Models\User;

class CreditWallet extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function user(){
        return $this->belongsTo(User::class);
    }
    
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
        return \App\Modules\Wallet\Database\factories\CreditWalletFactory::new();
    }
}
