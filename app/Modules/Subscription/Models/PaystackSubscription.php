<?php

namespace App\Modules\Subscription\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Subscription\Events\SubscriptionEvent;
use App\Models\User;

class PaystackSubscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'next_payment_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    protected $appends = [
        'status',
    ];

    public function getStatusAttribute(){
        
        // greater than now
        if($this->ends_at > now() ){
            return 'active';
        }

        // less than now
        if($this->ends_at < now() ){
            return 'inactive';
        }

        // in less than 10 days
        if($this->ends_at < now()->addDay(10) ){
            return 'expiring';
        }
       
    }

    
    
    protected static function newFactory()
    {
        return \App\Modules\Subscription\Database\factories\PaystackSubscriptionFactory::new();
    }


    protected static function boot() {
        parent::boot();
       
        static::created(function ($subscription) {
            event(new SubscriptionEvent($subscription, 'new'));
        });

        static::updating(function ($subscription) {
            event(new SubscriptionEvent($subscription, $subscription->paystack_status));
        });
    }


}
           