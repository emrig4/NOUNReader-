<?php

namespace App\Modules\Subscription\Http\Traits;

use App\Models\User;
use App\Modules\Subscription\Models\PaystackSubscription as readprojecttopicsSubscription;
use Digikraaft\PaystackWebhooks\Http\Controllers\WebhooksController as PaystackWebhooksController;
use Digikraaft\Paystack\Paystack;
use Digikraaft\Paystack\Plan;
use Digikraaft\PaystackSubscription\Exceptions\PaymentFailure;
use Digikraaft\PaystackSubscription\Payment;
use Digikraaft\PaystackSubscription\Exceptions\SubscriptionUpdateFailure;
use Illuminate\Support\Carbon;
use Digikraaft\Paystack\Subscription as PaystackSubscription;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;

use App\Modules\Wallet\Http\Traits\WalletTrait;

trait SubscriptionTrait
{
    
    /**
     * Process credit purchase (one-time payment) - REPLACES subscription creation
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function processCreditPurchase($payload)
    {
        try {
            // Validate payload
            $user = User::find($payload->metadata->user_id ?? auth()->id());
            
            if (!$user) {
                throw new \Exception('User not found');
            }

            // Get payment details
            $base_amount = $payload->amount;
            $fiat_currency = $payload->currency;
            $fiat_amount = $base_amount / 100;
            $ranc_amount = ranc_equivalent($fiat_amount, $fiat_currency);

            \Log::info('Processing credit purchase', [
                'user_id' => $user->id,
                'amount' => $fiat_amount,
                'currency' => $fiat_currency,
                'credits' => $ranc_amount,
                'reference' => $payload->reference
            ]);

            // Credit the user's wallet
            WalletTrait::creditSubscriptionWallet($ranc_amount, 'credit_purchase', $user->id);

            \Log::info('Credit purchase processed successfully', [
                'user_id' => $user->id,
                'amount' => $fiat_amount,
                'credits' => $ranc_amount,
                'reference' => $payload->reference
            ]);

            return (object) [
                'success' => true,
                'amount' => $fiat_amount,
                'credits' => $ranc_amount,
                'reference' => $payload->reference
            ];
            
        } catch (\Exception $e) {
            \Log::error('Credit purchase processing failed', [
                'user_id' => $payload->metadata->user_id ?? null,
                'error' => $e->getMessage(),
                'reference' => $payload->reference ?? null
            ]);
            
            throw $e;
        }
    }

    /**
     * Store credit purchase transaction (simplified version)
     */
    public static function storeCreditPurchase($payload)
    {
        try {
            $user = User::find($payload->metadata->user_id ?? auth()->id());
            
            if (!$user) {
                throw new \Exception('User not found for credit purchase');
            }

            // Get payment details
            $base_amount = $payload->amount;
            $fiat_currency = $payload->currency;
            $fiat_amount = $base_amount / 100;
            $ranc_amount = ranc_equivalent($fiat_amount, $fiat_currency);

            // Create transaction record for audit purposes
            $params = [
                'type' => 'credit_purchase',
                'amount' => $fiat_amount,
                'ranc' => $ranc_amount,
                'currency' => $fiat_currency,
                'reference' => $payload->reference,
                'status' => 'completed',
                'user_id' => $user->id,
                'gateway' => 'paystack',
                'transaction_data' => json_encode($payload),
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Create audit log entry
            \App\Modules\Wallet\Models\CreditWalletTransaction::create($params);

            \Log::info('Credit purchase stored successfully', [
                'user_id' => $user->id,
                'amount' => $fiat_amount,
                'credits' => $ranc_amount,
                'reference' => $payload->reference
            ]);

            return true;

        } catch (\Exception $e) {
            \Log::error('Failed to store credit purchase', [
                'user_id' => $payload->metadata->user_id ?? null,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Get user's credit balance
     *
     * @param  int  $user_id
     * @return int
     */
    public static function getUserCreditBalance($user_id = null)
    {
        $user_id = $user_id ?? auth()->id();
        return WalletTrait::subscriptionWalletBalance($user_id);
    }

    /**
     * Check if user has sufficient credits
     *
     * @param  int  $required_amount
     * @param  int  $user_id
     * @return bool
     */
    public static function hasSufficientCredits($required_amount, $user_id = null)
    {
        $user_id = $user_id ?? auth()->id();
        $balance = self::getUserCreditBalance($user_id);
        return $balance >= $required_amount;
    }

    /**
     * Deduct credits from user wallet
     *
     * @param  int  $amount
     * @param  int  $user_id
     * @return bool
     */
    public static function deductCredits($amount, $user_id = null, $reason = 'usage')
    {
        try {
            $user_id = $user_id ?? auth()->id();
            
            if (!self::hasSufficientCredits($amount, $user_id)) {
                throw new \Exception('Insufficient credits');
            }

            WalletTrait::debitSubscriptionWallet($amount, $user_id);

            // Log the deduction
            \Log::info('Credits deducted', [
                'user_id' => $user_id,
                'amount' => $amount,
                'reason' => $reason,
                'new_balance' => self::getUserCreditBalance($user_id)
            ]);

            return true;

        } catch (\Exception $e) {
            \Log::error('Failed to deduct credits', [
                'user_id' => $user_id,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    // ========================================================================
    // LEGACY SUBSCRIPTION METHODS (DEPRECATED - KEPT FOR BACKWARDS COMPATIBILITY)
    // ========================================================================

    /**
     * DEPRECATED: This method is no longer used for subscription creation
     * Kept for backwards compatibility - now just processes as credit purchase
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function createSubscription($payload)
    {
        \Log::warning('Deprecated method called: createSubscription. Processing as credit purchase.', [
            'user_id' => $payload->metadata->user_id ?? null,
            'reference' => $payload->reference ?? null
        ]);

        // Process as credit purchase instead
        return self::processCreditPurchase($payload);
    }

    /**
     * DEPRECATED: This method is no longer used for storing subscriptions
     * Kept for backwards compatibility
     */
    public static function storeSubscription($plan, $customer)
    {
        \Log::warning('Deprecated method called: storeSubscription. Storing as credit purchase.', [
            'customer_email' => $customer->email ?? null,
            'plan_name' => $plan->name ?? null
        ]);

        // For backwards compatibility, create a mock subscription record
        // that represents a credit purchase
        try {
            $user = User::whereEmail($customer->email)->first();
            
            if (!$user) {
                throw new \Exception('User not found for subscription store');
            }

            // Create a mock subscription record for audit purposes
            $params = [
                'name' => 'Credit Purchase - ' . ($plan->name ?? 'Unknown'),
                'subscription_code' => 'CREDIT_' . time(),
                'subscription_id' => time(),
                'paystack_status' => 'completed',
                'paystack_plan' => json_encode($plan),
                'quantity' => '1',
                'email_token' => 'credit_purchase',
                'authorization' => json_encode(['type' => 'credit_purchase']),
                'next_payment_date' => null, // No recurring payments
                'user_id' => $user->id,
                'customer' => json_encode($customer),
                'payments_count' => 1,
                'paystack_id' => $user->paystack_id,
                'updated_at' => now(),
            ];

            // Create subscription record for backwards compatibility
            $subscription = readprojecttopicsSubscription::create($params);

            \Log::info('Credit purchase recorded as subscription for backwards compatibility', [
                'user_id' => $user->id,
                'subscription_code' => $params['subscription_code'],
                'plan_name' => $params['name']
            ]);

            return $subscription;

        } catch (\Exception $e) {
            \Log::error('Failed to store credit purchase as subscription', [
                'customer_email' => $customer->email ?? null,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * DEPRECATED: This method is no longer used for syncing subscriptions
     * Kept for backwards compatibility
     */
    public static function syncSubscriptions($payload)
    {
        \Log::warning('Deprecated method called: syncSubscriptions. Credit purchases do not require syncing.', [
            'count' => count($payload ?? [])
        ]);

        // Credit purchases don't need syncing since they're one-time transactions
        // Return success for backwards compatibility
        return true;
    }

    /**
     * DEPRECATED: This method is no longer used for creating subscription wallets
     * Kept for backwards compatibility
     */
    public static function createSubscriptionWallet($subscription)
    {
        \Log::warning('Deprecated method called: createSubscriptionWallet. Using credit-based wallet.', [
            'user_id' => $subscription->metadata->user_id ?? null
        ]);

        // For backwards compatibility, just return success
        // The wallet is already managed by WalletTrait methods
        return true;
    }

    // ========================================================================
    // UTILITY METHODS FOR CREDIT SYSTEM
    // ========================================================================

    /**
     * Convert credit amount to Naira equivalent
     *
     * @param  int  $credits
     * @return float
     */
    public static function creditsToNaira($credits)
    {
        // Assuming 1 RNC = 1 Naira (adjust if different rate is used)
        return $credits;
    }

    /**
     * Convert Naira amount to credits
     *
     * @param  float  $amount
     * @return int
     */
    public static function nairaToCredits($amount)
    {
        // Assuming 1 RNC = 1 Naira (adjust if different rate is used)
        return (int) $amount;
    }

    /**
     * Get credit purchase history for a user
     *
     * @param  int  $user_id
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCreditPurchaseHistory($user_id = null, $limit = 10)
    {
        $user_id = $user_id ?? auth()->id();
        
        return \App\Modules\Wallet\Models\CreditWalletTransaction::where('credit_wallet_id', function($query) use ($user_id) {
                $query->select('id')
                      ->from('credit_wallets')
                      ->where('user_id', $user_id);
            })
            ->where('type', 'credit_purchase')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get total credits purchased by a user
     *
     * @param  int  $user_id
     * @return int
     */
    public static function getTotalCreditsPurchased($user_id = null)
    {
        $user_id = $user_id ?? auth()->id();
        
        $total = \App\Modules\Wallet\Models\CreditWalletTransaction::where('credit_wallet_id', function($query) use ($user_id) {
                $query->select('id')
                      ->from('credit_wallets')
                      ->where('user_id', $user_id);
            })
            ->where('type', 'credit_purchase')
            ->sum('ranc');
            
        return (int) $total;
    }
}
