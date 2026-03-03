<?php

namespace App\Modules\Subscription\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pricing extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    
    protected static function newFactory()
    {
        return \App\Modules\Pricing\Database\factories\PricingFactory::new();
    }


    public function price(){
        
        $currency = $this->currency();
        $result = fiat_equivalent($this->amount, $currency);
    	return $result;
    }

    public function currency(){

        return session('currency') ? session('currency') : setting('default_currency');
    }

    public function features(){
    	return explode(',', $this->features);
    }
}
