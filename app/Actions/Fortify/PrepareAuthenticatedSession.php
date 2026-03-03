<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\LoginRateLimiter;
use App\Modules\Subscription\Http\Traits\SubscriptionTrait;
use Digikraaft\Paystack\Subscription;
use App\Modules\Wallet\Models\SubscriptionWallet;
use App\Modules\Wallet\Models\CreditWallet;

class PrepareAuthenticatedSession
{
    protected $limiter;

    public function __construct(LoginRateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle($request, $next)
    {
        $request->session()->regenerate();
        $this->limiter->clear($request);

        // ADD YOUR CODE HERE (inside handle method)
        $user = $request->user();

        // Auto-create account if it doesn't exist
        if (!$user->account) {
            $account = new \App\Modules\Account\Models\Account();
            $account->user_id = $user->id;
            $account->save();
        }

        // Your existing subscription/credit wallet code...
        SubscriptionWallet::updateOrCreate([
            'user_id' => auth()->user()->id,
        ]);

        CreditWallet::updateOrCreate([
            'user_id' => auth()->user()->id,
        ]);

        return $next($request);
    }
}