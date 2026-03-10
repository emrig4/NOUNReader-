<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Cviebrock\EloquentSluggable\Sluggable;
// use Silber\Bouncer\Database\HasRolesAndAbilities;
use Spatie\Permission\Traits\HasRoles;
use App\Modules\Wallet\Models\SubscriptionWallet;
use App\Modules\Wallet\Models\CreditWallet;
use Digikraaft\PaystackSubscription\Billable;

use App\Modules\Account\Models\AccountFollow;
use App\Modules\Account\Models\AccountFavorite;
use App\Modules\Account\Models\Account;
use App\Modules\Subscription\Models\PaystackSubscription;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Sluggable;
    // use HasRolesAndAbilities;
    use Billable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'title',
        'has_set_permanent_password', // Added for verification code system
        'status', // ✅ ADDED: For retry-friendly registration
        'verification_code', // ✅ ADDED: To store verification code in database
        'referral_code', // Add this
    ];

    /**
     * ✅ PERMANENT FIX: Proper Account and CreditWallet creation
     * This fix ensures Account creation uses empty strings for Account-specific fields
     * instead of trying to access non-existent User fields
     */
    protected static function booted()
    {
        static::created(function ($user) {
            // Auto-create Account record if user doesn't have one
            if (!$user->account) {
                try {
                    $account = \App\Modules\Account\Models\Account::create([
                        'user_id' => $user->id,
                        'first_name' => $user->first_name ?? '',
                        'last_name' => $user->last_name ?? '',
                        'email' => $user->email,
                        // ✅ CRITICAL FIX: Use empty strings for Account-specific fields
                        'address' => '',      // These fields exist on Account model, not User
                        'city' => '',         // User model doesn't have these fields
                        'state' => '',
                        'zip_code' => '',
                        'country' => '',
                        'phone' => '',
                    ]);

                    // Auto-create SubscriptionWallet
                    \App\Modules\Wallet\Models\SubscriptionWallet::create([
                        'user_id' => $user->id,
                        'account_id' => $account->id,
                        'is_active' => true,
                        'ranc' => 0,
                    ]);

                    // Auto-create CreditWallet
                    \App\Modules\Wallet\Models\CreditWallet::create([
                        'user_id' => $user->id,
                        'account_id' => $account->id,
                        'is_active' => true,
                        'ranc' => 0,
                    ]);

                    \Log::info('✅ Auto-created Account and Wallets for user: ' . $user->id);
                    
                } catch (\Exception $e) {
                    \Log::error('❌ Failed to auto-create Account/Wallets for user: ' . $user->id . ' - ' . $e->getMessage());
                    \Log::error('Stack trace: ' . $e->getTraceAsString());
                }
            }
        });
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'username' => [
                'source' => ['first_name', 'last_name']
            ]
        ];
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'has_set_permanent_password' => 'boolean', // ✅ CORRECT cast for verification system
        'status' => 'string', // ✅ ADDED: Cast status as string
        // Note: 'password' should NOT be in casts array for proper hashing
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function getNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }

    // relationships
    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function favoriteResources()
    {
        return $this->hasMany(AccountFavorite::class);
    }

    public function followers()
    {
        return $this->hasMany(AccountFollow::class, 'followee_id', 'id');
    }

    public function followings()
    {
        return $this->hasMany(AccountFollow::class, 'follower_id', 'id');
    }

    public static function whereInMultiple(array $columns, $value)
    {
        return static::query()->whereRaw(
            '(  '. $value .  ') in ('  .  implode(', ', $columns, ) .  ')'
        );
    }

    // interface provided by puysub
    public function paystackEmail(): string {
        return 'user@example.com';
    }

    public function invoiceMailables(): array {
        return [
            'user@example.com'
        ];
    }

    public function SubscriptionWallet(){
        return $this->hasOne(SubscriptionWallet::class);
    }

    public function CreditWallet(){
        return $this->hasOne(CreditWallet::class);
    }

    /**
     * Enter your own logic (e.g. if ($this->id === 1) to
     *   enable this user to be able to add/edit blog posts
     *
     * @return bool - true = they can edit / manage blog posts,
     *        false = they have no access to the blog admin panel
     */
    public function canManageBinshopsBlogPosts()
    {
        if ($this->id == 1 || $this->id == 2){
           return true;
        }
        return false;
    }

    /**
     * Check if user is subscribed to a specific plan
     */
    public function subscribedToPlan($planCode)
    {
        try {
            $subscription = $this->subscriptions()
                ->where('paystack_plan', 'like', '%"plan_code":"' . $planCode . '"%')
                ->where('paystack_status', 'active')
                ->first();
                
            return $subscription ? true : false;
        } catch (\Exception $e) {
            \Log::error('Error checking subscription status', [
                'user_id' => $this->id,
                'plan_code' => $planCode,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get user subscriptions
     */
    public function subscriptions()
    {
        return $this->hasMany(\App\Modules\Subscription\Models\PaystackSubscription::class, 'user_id');
    }

    /**
     * Get active subscription
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('paystack_status', 'active')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Check if user has active subscription
     */
    public function hasActiveSubscription()
    {
        return $this->activeSubscription() ? true : false;
    }

    /**
     * ✅ VERIFICATION SYSTEM HELPER METHODS
     * These methods provide clean interface for verification flow
     */

    /**
     * Check if user is using verification code (first login)
     */
    public function isUsingVerificationCode(): bool
    {
        return !$this->has_set_permanent_password;
    }

    /**
     * Check if user has set permanent password
     */
    public function hasSetPermanentPassword(): bool
    {
        return $this->has_set_permanent_password === true;
    }

    /**
     * Mark permanent password as set
     */
    public function markPermanentPasswordSet(): void
    {
        $this->has_set_permanent_password = true;
        $this->save();
    }

    /**
     * Check if user is pending verification
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if user is active/verified
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Mark user as active
     */
    public function markAsActive(): void
    {
        $this->status = 'active';
        $this->save();
    }

    /**
     * Mark user as pending
     */
    public function markAsPending(): void
    {
        $this->status = 'pending';
        $this->save();
    }

    public function isPremium()
{
    // Customize this based on your subscription logic
    return $this->subscription && $this->subscription->isActive();
}

public function getPlagiarismLimits()
{
    if ($this->isPremium()) {
        return [
            'daily_checks' => 999,
            'daily_words' => 10000,
            'max_words_per_check' => 5000
        ];
    }
    
    return [
        'daily_checks' => $this->isLoggedIn() ? 20 : 5,
        'daily_words' => 1000,
        'max_words_per_check' => 1000
    ];
    
    
}
    /**
     * Get initials-based avatar URL
     * This method ensures all users have an avatar with their initials
     */
    public function getInitialsAvatarUrl()
    {
        // Get the user's full name or fall back to email
        $name = $this->name ?: $this->email;
        
        // Extract initials from name
        $initials = $this->getInitials($name);
        
        // Generate avatar URL using ui-avatars.com
        $encodedName = urlencode($name);
        $backgroundColor = '16A34A'; // Theme green color to match your site branding
        $color = 'ffffff'; // White text
        $size = '100'; // Avatar size
        
        return "https://ui-avatars.com/api/?name={$encodedName}&background={$backgroundColor}&color={$color}&size={$size}&format=png&rounded=true&bold=true";
    }

    /**
     * Extract initials from a full name
     */
    private function getInitials($name)
    {
        if (empty($name)) {
            return 'U'; // Default to 'U' for User if name is empty
        }
        
        $words = explode(' ', trim($name));
        $initials = '';
        
        // Take first letter of each word (max 2 initials)
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
            if (strlen($initials) >= 2) {
                break;
            }
        }
        
        // If we don't have 2 initials, pad with the first letter
        if (strlen($initials) < 2 && !empty($name)) {
            $initials = strtoupper(substr($name, 0, 1)) . $initials;
        }
        
        return $initials ?: 'U';
    }
    
    /**
     * Override profile_photo_url to always return initials avatar if no photo exists
     */
    public function getProfilePhotoUrlAttribute()
    {
        // Return uploaded photo if it exists
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        
        // Return initials avatar if no photo uploaded
        return $this->getInitialsAvatarUrl();
    }
    
    /**
 * Generate referral code for user
 */
public function generateReferralCode(): string
{
    $code = strtoupper(substr($this->first_name, 0, 3) . substr($this->last_name, 0, 3) . $this->id . rand(100, 999));
    $this->referral_code = $code;
    $this->save();
    return $code;
}

/**
 * Get user's referral link
 */
public function getReferralLinkAttribute(): string
{
    if (!$this->referral_code) {
        $this->generateReferralCode();
    }
    return route('register') . '?ref=' . $this->referral_code;
}
}
