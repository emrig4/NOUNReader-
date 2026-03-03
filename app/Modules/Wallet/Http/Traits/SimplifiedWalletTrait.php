<?php

namespace App\Modules\Wallet\Http\Traits;

use App\Models\User;
use App\Modules\Wallet\Models\SubscriptionWallet;
use App\Modules\Wallet\Models\SubscriptionWalletTransaction;
use App\Services\EmailNotificationService;
use Illuminate\Support\Facades\Log;

trait SimplifiedWalletTrait
{
    /**
     * Credit Subscription Wallet (for both user earnings and platform commission)
     * 
     * @param float $ranc_amount Amount to credit
     * @param string $type Type of transaction: 'earning', 'refund', 'platform_commission'
     * @param int|null $user_id User ID (null for authenticated user)
     * @param string $resource_name Resource name for transaction remark
     * @return bool Success status
     */
    public static function creditSubscriptionWallet($ranc_amount, $type='earning', $user_id = null, $resource_name = null)
    {   
        try {
            // Get user - either by provided ID or authenticated user
            if ($user_id) {
                $user = User::find($user_id);
            } else {
                $user = auth()->user();
            }

            // Check if user exists
            if (!$user) {
                Log::error('Credit subscription wallet failed: User not found', [
                    'user_id' => $user_id,
                    'type' => $type,
                    'resource_name' => $resource_name,
                    'amount' => $ranc_amount
                ]);
                return false;
            }

            $ranc = (int) $ranc_amount;

            // Get or create subscription wallet
            $subscriptionWallet = $user->SubscriptionWallet;
            if (!$subscriptionWallet) {
                $subscriptionWallet = SubscriptionWallet::create([
                    'user_id' => $user->id,
                    'ranc' => 0
                ]);
                Log::info('Created missing subscription wallet for user', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'wallet_id' => $subscriptionWallet->id
                ]);
            }
            
            // Track balance before credit operation
            $balanceBefore = $subscriptionWallet->ranc;
            
            // Create transaction record
            $params = [
                'subscription_wallet_id' => $subscriptionWallet->id,
                'ranc' => $ranc_amount,
                'currency' => 'RNC',
                'type' => $type,
                'status' => 'processed',
                'remark' => $type === 'platform_commission' 
                    ? 'Platform commission on ' . $resource_name
                    : ($resource_name ? 'Earnings on ' . $resource_name : 'Credit transaction'),
            ];

            $subscriptionWalletTransaction = SubscriptionWalletTransaction::create($params);
            
            // Update wallet balance
            $subscriptionWallet->increment('ranc', $ranc);
            $subscriptionWallet->save();

            // Calculate balance after credit operation
            $balanceAfter = $subscriptionWallet->ranc;

            // Send email notification for wallet credit
            if ($subscriptionWalletTransaction && method_exists(new EmailNotificationService(), 'sendWalletCreditEmail')) {
                app(EmailNotificationService::class)->sendWalletCreditEmail(
                    $user, 
                    $ranc, 
                    $balanceBefore, 
                    $balanceAfter, 
                    $params['remark']
                );
            }

            Log::info('Credit subscription wallet successful', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'amount' => $ranc,
                'type' => $type,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'resource' => $resource_name,
                'transaction_id' => $subscriptionWalletTransaction->id
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Credit subscription wallet operation failed', [
                'user_id' => $user_id,
                'type' => $type,
                'amount' => $ranc_amount,
                'resource' => $resource_name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }

    /**
     * Debit Subscription Wallet (for user spending)
     * 
     * @param float $ranc_amount Amount to debit
     * @param int|null $user_id User ID (null for authenticated user)
     * @param string $resource_name Resource name for transaction remark
     * @return bool Success status
     */
    public static function debitSubscriptionWallet($ranc_amount, $user_id = null, $resource_name = null)
    {   
        try {
            // Get user - either by provided ID or authenticated user
            if ($user_id) {
                $user = User::find($user_id);
            } else {
                $user = auth()->user();
            }

            // Check if user exists
            if (!$user) {
                Log::error('Debit subscription wallet failed: User not found', [
                    'user_id' => $user_id,
                    'amount' => $ranc_amount,
                    'resource' => $resource_name
                ]);
                return false;
            }

            $ranc = (int) $ranc_amount;

            // Get or create subscription wallet
            $subscriptionWallet = $user->SubscriptionWallet;
            if (!$subscriptionWallet) {
                $subscriptionWallet = SubscriptionWallet::create([
                    'user_id' => $user->id,
                    'ranc' => 0
                ]);
                Log::info('Created missing subscription wallet for user', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'wallet_id' => $subscriptionWallet->id
                ]);
            }

            // Track balance before debit operation
            $balanceBefore = $subscriptionWallet->ranc;

            // Check sufficient balance
            if ($subscriptionWallet->ranc < $ranc) {
                Log::warning('Insufficient subscription wallet balance', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'required' => $ranc,
                    'available' => $subscriptionWallet->ranc,
                    'resource' => $resource_name
                ]);
                return false;
            }

            // Create transaction record
            $params = [
                'subscription_wallet_id' => $subscriptionWallet->id,
                'ranc' => $ranc_amount,
                'currency' => 'RNC',
                'type' => 'spending',
                'status' => 'processed',
                'remark' => $resource_name ? 'Payment for ' . $resource_name : 'Wallet deduction',
            ];

            $subscriptionWalletTransaction = SubscriptionWalletTransaction::create($params);
            
            // Update wallet balance
            $subscriptionWallet->decrement('ranc', $ranc);
            $subscriptionWallet->save();

            // Calculate balance after debit operation
            $balanceAfter = $subscriptionWallet->ranc;

            // Send email notification for wallet deduction
            if ($subscriptionWalletTransaction && method_exists(new EmailNotificationService(), 'sendWalletDeductionEmail')) {
                app(EmailNotificationService::class)->sendWalletDeductionEmail(
                    $user, 
                    $ranc, 
                    $balanceBefore, 
                    $balanceAfter, 
                    $params['remark']
                );
            }

            Log::info('Debit subscription wallet successful', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'amount' => $ranc,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'resource' => $resource_name,
                'transaction_id' => $subscriptionWalletTransaction->id
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Debit subscription wallet operation failed', [
                'user_id' => $user_id,
                'amount' => $ranc_amount,
                'resource' => $resource_name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }

    /**
     * Refund to Subscription Wallet
     * 
     * @param float $ranc_amount Amount to refund
     * @param string $type Type of refund: 'refund', 'adjustment'
     * @param int|null $user_id User ID (null for authenticated user)
     * @param string $resource_name Resource name for transaction remark
     * @return bool Success status
     */
    public static function refundSubscriptionWallet($ranc_amount, $type='refund', $user_id = null, $resource_name = null)
    {
        try {
            // Get user - either by provided ID or authenticated user
            if ($user_id) {
                $user = User::find($user_id);
            } else {
                $user = auth()->user();
            }

            // Check if user exists
            if (!$user) {
                Log::error('Refund subscription wallet failed: User not found', [
                    'user_id' => $user_id,
                    'amount' => $ranc_amount,
                    'type' => $type
                ]);
                return false;
            }

            $ranc = (int) $ranc_amount;

            // Get or create subscription wallet
            $subscriptionWallet = $user->SubscriptionWallet;
            if (!$subscriptionWallet) {
                $subscriptionWallet = SubscriptionWallet::create([
                    'user_id' => $user->id,
                    'ranc' => 0
                ]);
                Log::info('Created missing subscription wallet for user during refund', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'wallet_id' => $subscriptionWallet->id
                ]);
            }
            
            $subscriptionWallet->increment('ranc', $ranc);
            $subscriptionWallet->save(); 

            Log::info('Refund subscription wallet successful', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'amount' => $ranc,
                'type' => $type,
                'resource' => $resource_name
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Refund subscription wallet failed', [
                'user_id' => $user_id,
                'amount' => $ranc_amount,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get subscription wallet balance
     * 
     * @param int|null $user_id User ID (null for authenticated user)
     * @return int Balance amount
     */
    public static function getSubscriptionWalletBalance($user_id = null)
    {
        try {
            // Get user - either by provided ID or authenticated user
            if ($user_id) {
                $user = User::find($user_id);
            } else {
                $user = auth()->user();
            }

            if (!$user) {
                return 0;
            }

            $subscriptionWallet = $user->SubscriptionWallet;
            return $subscriptionWallet ? $subscriptionWallet->ranc : 0;
            
        } catch (\Exception $e) {
            Log::error('Failed to get subscription wallet balance', [
                'user_id' => $user_id,
                'error' => $e->getMessage()
            ]);
            
            return 0;
        }
    }

    /**
     * Check if user has sufficient balance
     * 
     * @param float $required_amount Required amount
     * @param int|null $user_id User ID (null for authenticated user)
     * @return bool Has sufficient balance
     */
    public static function hasSufficientBalance($required_amount, $user_id = null)
    {
        $balance = self::getSubscriptionWalletBalance($user_id);
        return $balance >= (int) $required_amount;
    }
}