<?php

namespace App\Services;

use App\Models\User;
use App\Modules\Wallet\Models\SubscriptionWallet;
use App\Modules\Wallet\Http\Traits\WalletTrait;
use App\Modules\Setting\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoCreditService
{
    use WalletTrait;

    /**
     * Grant auto credit to a newly registered user
     *
     * @param User $user
     * @return bool
     */
    public function grantAutoCredit(User $user)
    {
        try {
            Log::info("AutoCreditService: Starting auto credit process for user", [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            // Check if auto credit is enabled
            if (!$this->isAutoCreditEnabled()) {
                Log::info("AutoCreditService: Auto credit is disabled", ['user_id' => $user->id]);
                return false;
            }

            // Check if this is the user's first time getting auto credit
            if ($this->userHasReceivedAutoCredit($user)) {
                Log::info("AutoCreditService: User already received auto credit", ['user_id' => $user->id]);
                return false;
            }

            // Get the auto credit amount from settings
            $creditAmount = $this->getAutoCreditAmount();
            
            Log::info("AutoCreditService: Credit amount from settings", [
                'user_id' => $user->id,
                'credit_amount' => $creditAmount
            ]);
            
            if ($creditAmount <= 0) {
                Log::warning("AutoCreditService: Credit amount is 0 or negative", [
                    'user_id' => $user->id,
                    'credit_amount' => $creditAmount
                ]);
                return false;
            }

            // Get or create subscription wallet
            $subscriptionWallet = $this->getOrCreateSubscriptionWallet($user);
            
            $balanceBefore = $subscriptionWallet->ranc ?? 0;
            
            Log::info("AutoCreditService: Before increment", [
                'user_id' => $user->id,
                'balance_before' => $balanceBefore,
                'credit_to_grant' => $creditAmount
            ]);

            // Grant the credit using the proper WalletTrait method
            Log::info("AutoCreditService: Using WalletTrait to credit wallet", [
                'user_id' => $user->id,
                'credit_amount' => $creditAmount,
                'balance_before' => $balanceBefore
            ]);

            // Use the same method as Paystack topup system - THIS IS THE KEY FIX
            self::creditSubscriptionWallet($creditAmount, 'auto_signup', $user->id);

            // Get the new balance after credit
            $wallet = DB::table('subscription_wallets')->where('user_id', $user->id)->first();
            $newBalance = $wallet->ranc ?? 0;
            
            Log::info("AutoCreditService: After WalletTrait credit", [
                'user_id' => $user->id,
                'new_balance' => $newBalance
            ]);

            // Transaction is automatically logged by WalletTrait via CreditWalletTransaction
            Log::info("AutoCreditService: Credit granted successfully via WalletTrait", [
                'user_id' => $user->id,
                'amount' => $creditAmount,
                'balance_before' => $balanceBefore,
                'balance_after' => $newBalance
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('AutoCreditService: Exception occurred', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Check if auto credit is enabled in settings
     *
     * @return bool
     */
    public function isAutoCreditEnabled()
    {
        try {
            $enabled = Setting::get('auto_credit_enabled', false);
            $result = (bool) $enabled;
            Log::debug("AutoCreditService: isAutoCreditEnabled check", [
                'raw_setting' => $enabled,
                'result' => $result
            ]);
            return $result;
        } catch (\Exception $e) {
            Log::error("AutoCreditService: Error checking if enabled", [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get the auto credit amount from settings
     *
     * @return float
     */
    public function getAutoCreditAmount()
    {
        try {
            $amount = Setting::get('auto_credit_amount', 0);
            $result = (float) $amount;
            Log::debug("AutoCreditService: getAutoCreditAmount check", [
                'raw_setting' => $amount,
                'result' => $result
            ]);
            return $result;
        } catch (\Exception $e) {
            Log::error("AutoCreditService: Error getting amount", [
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Check if user has already received auto credit
     *
     * @param User $user
     * @return bool
     */
    protected function userHasReceivedAutoCredit(User $user)
    {
        try {
            $exists = DB::table('wallet_transactions')
                ->where('user_id', $user->id)
                ->where('description', 'LIKE', '%First-time user auto credit%')
                ->exists();
                
            Log::debug("AutoCreditService: userHasReceivedAutoCredit check", [
                'user_id' => $user->id,
                'exists' => $exists
            ]);
            
            return $exists;
        } catch (\Exception $e) {
            Log::error("AutoCreditService: Error checking user auto credit history", [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get or create subscription wallet for user
     *
     * @param User $user
     * @return SubscriptionWallet
     */
    protected function getOrCreateSubscriptionWallet(User $user)
    {
        try {
            $subscriptionWallet = $user->SubscriptionWallet;
            
            if (!$subscriptionWallet) {
                Log::info("AutoCreditService: Creating new subscription wallet", ['user_id' => $user->id]);
                $subscriptionWallet = SubscriptionWallet::create([
                    'user_id' => $user->id,
                    'reference' => 'AUTO_CREDIT_' . $user->id . '_' . time()
                ]);
            } else {
                Log::debug("AutoCreditService: Using existing subscription wallet", ['user_id' => $user->id]);
            }

            return $subscriptionWallet;
        } catch (\Exception $e) {
            Log::error("AutoCreditService: Error getting/creating subscription wallet", [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Enable auto credit functionality
     *
     * @return bool
     */
    public function enableAutoCredit()
    {
        try {
            $result = Setting::set('auto_credit_enabled', true);
            Log::info("AutoCreditService: Auto credit enabled", ['result' => $result]);
            return $result;
        } catch (\Exception $e) {
            Log::error("AutoCreditService: Error enabling auto credit", [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Disable auto credit functionality
     *
     * @return bool
     */
    public function disableAutoCredit()
    {
        try {
            $result = Setting::set('auto_credit_enabled', false);
            Log::info("AutoCreditService: Auto credit disabled", ['result' => $result]);
            return $result;
        } catch (\Exception $e) {
            Log::error("AutoCreditService: Error disabling auto credit", [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Set auto credit amount
     *
     * @param float $amount
     * @return bool
     */
    public function setAutoCreditAmount($amount)
    {
        try {
            if (!is_numeric($amount) || $amount < 0) {
                Log::warning("AutoCreditService: Invalid amount provided", ['amount' => $amount]);
                return false;
            }
            
            $result = Setting::set('auto_credit_amount', $amount);
            Log::info("AutoCreditService: Auto credit amount set", [
                'amount' => $amount,
                'result' => $result
            ]);
            return $result;
        } catch (\Exception $e) {
            Log::error("AutoCreditService: Error setting auto credit amount", [
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}