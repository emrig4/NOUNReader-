<?php

namespace App\Modules\Account\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Resource\Models\Resource;
use App\Modules\Subscription\Models\PaystackSubscription;
use App\Modules\Wallet\Models\SubscriptionWallet;
use App\Modules\Wallet\Models\CreditWallet;

use Digikraaft\Paystack\Subscription;





class Account extends Model
{
   protected static function boot() {
        parent::boot();
        static::creating(function ($account) {
            $account->status = 1; //set to active on creating
        });
    }
    protected $guarded = ['id'];
    
    protected $dates = ['last_login'];
    
    protected $appends = ['full_name','url'];
    
    
   
    public static function registered($email)
    {
        // return static::where('email', $email)->exists();
    }

    public static function findByEmail($email)
    {
        // return static::where('email', $email)->first();
    }
    
    public static function findById($id)
    {
        // return static::where('id', $id)->first();
    }

    public function  hasPublished() {

    }

    public function user() {
         return $this->belongsTo(\App\Models\User::class);
    }

    public function creditWallet() {
         return $this->user->hasOne(CreditWallet::class);
    }

    public function subscriptionWallet() {
         return $this->user->hasOne(SubscriptionWallet::class);
    }

    public function activeSubscription() {

        // Get all active subscriptions...
        $subscription = $this->user->subscriptions()->wherePaystackStatus('active')->first();
        return $subscription;

    }

    public function subscriptions() {

        // Get all active subscriptions...
        $subscriptions = $this->user->subscriptions()->get();
        return $subscriptions;

    }

    public function subscriptionWalletBalance() {
        return $this->subscriptionWallet->ranc;
    }


    public function hasActiveSubscription() {
        if ($this->activeSubscription() ) {
            return true;
        }
        return false;
    }

   

    /**
     * Determine if the user is a User.
     *
     * @return bool
     */
    public function isUser()
    {
        
    }
    
    public function isAdmin()
    {
        
    }

    /**
     * Checks if a user belongs to the given Role ID.
     *
     * @param int $roleId
     * @return bool
     */
    public function hasRoleId($roleId)
    {
        // use bouncer
    }

    
    /**
     * Check if the current user is activated.
     *
     * @return bool
     */
    public function isActivated()
    {
        // return Activation::completed($this);
    }

    /**
     * Get the roles of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany 
    {
        // return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
    }
    
    
    /**
     * Get the Favorite of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteLists()
    {
        // return $this->belongsToMany(Ebook::class, 'favorite_lists')->withTimestamps();
    }
   
    
  public function resources()
{
    return $this->user->hasMany(Resource::class)->where('is_published', true);
}
    
    /**
     * Get the full name of the user.
     *
     * @return string
     */
   public function getFullNameAttribute()
{
    // Get name from related User model since accounts table doesn't have first_name/last_name
    if ($this->user) {
        return $this->user->first_name . ' ' . $this->user->last_name;
    }
    return '';
}

    /**
     * Get the account url of the user.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return auth()->user()->username;
    }

    



}
