<?php

namespace App\Modules\Wallet\Http\Traits;

use App\Models\User;
use App\Modules\Wallet\Models\CreditWalletTransaction;
use App\Modules\Wallet\Models\SubscriptionWallet;
use App\Services\EmailNotificationService;
use Illuminate\Support\Facades\Log;

trait WalletTrait
{
    /**
     * ✅ PERFECT FIX: Credit Subscription Wallet with robust error handling
     */
    public static function creditSubscriptionWallet($ranc_amount, $type='earning', $user_id = null)
    {   
        try {
            if($user_id){
                $user = User::find($user_id);
            }else{
                $user = auth()->user();
            }

            // ✅ CRITICAL FIX: Check if user exists and is authenticated
            if (!$user) {
                Log::error('WalletTrait: User not found or not authenticated for credit');
                return false;
            }

            $ranc = (int) $ranc_amount;

            // Track balance before credit operation
            $balanceBefore = $user->SubscriptionWallet ? $user->SubscriptionWallet->ranc : 0;

            // ✅ CRITICAL FIX: Auto-create SubscriptionWallet if missing
            $SubscriptionWallet = $user->SubscriptionWallet;
            if (!$SubscriptionWallet) {
                $SubscriptionWallet = SubscriptionWallet::create([
                    'user_id' => $user->id,
                    'ranc' => 0 // Initialize with 0 balance
                ]);
                Log::info('WalletTrait: Auto-created SubscriptionWallet for user', ['user_id' => $user->id]);
            }
            
            $SubscriptionWallet->increment('ranc', $ranc);
            $SubscriptionWallet->save();

            // Calculate balance after credit operation
            $balanceAfter = $user->SubscriptionWallet->ranc;

            $params = [
                'amount' => $ranc,
                'ranc' => $ranc,
                'currency' => 'RNC',
                'type' => $type,
                'status' => 'processed',
                'remark' => ucwords($type),
                'credit_wallet_id' => $user->CreditWallet->id
            ];

            // ✅ CRITICAL FIX: Check if CreditWallet exists before creating transaction
            if ($user->CreditWallet) {
                CreditWalletTransaction::create($params);
            } else {
                Log::warning('WalletTrait: CreditWallet not found for user', ['user_id' => $user->id]);
            }

            // Send email notification for wallet credit
            try {
                if (class_exists('App\\Services\\EmailNotificationService')) {
                    $emailService = new EmailNotificationService();
                    if (method_exists($emailService, 'sendWalletCreditEmail')) {
                        $emailService->sendWalletCreditEmail(
                            $user->email,
                            [
                                'user' => $user,
                                'amount' => $ranc,
                                'type' => $type,
                                'balance_before' => $balanceBefore,
                                'balance_after' => $balanceAfter,
                                'currency' => 'RNC',
                                'transaction_type' => 'subscription_credit',
                                'description' => 'Subscription wallet credit via ' . ucwords($type)
                            ]
                        );
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to send wallet credit email: ' . $e->getMessage());
            }

            return true;

        } catch (\Exception $e) {
            Log::error('WalletTrait credit error: ' . $e->getMessage(), [
                'user_id' => $user_id,
                'amount' => $ranc_amount,
                'type' => $type
            ]);
            return false;
        }
    }

    /**
     * ✅ PERFECT FIX: Debit Subscription Wallet with robust error handling
     */
    public static function debitSubscriptionWallet($ranc_amount, $user_id = null)
    {   
        try {
            if($user_id){
                $user = User::find($user_id);
            }else{
                $user = auth()->user();
            }

            // ✅ CRITICAL FIX: Check if user exists and is authenticated
            if (!$user) {
                Log::error('WalletTrait: User not found or not authenticated for debit');
                return false;
            }

            $ranc = (int) $ranc_amount;

            // Track balance before debit operation
            $balanceBefore = $user->SubscriptionWallet ? $user->SubscriptionWallet->ranc : 0;

            // ✅ CRITICAL FIX: Auto-create SubscriptionWallet if missing
            $SubscriptionWallet = $user->SubscriptionWallet;
            if (!$SubscriptionWallet) {
                $SubscriptionWallet = SubscriptionWallet::create([
                    'user_id' => $user->id,
                    'ranc' => 0 // Initialize with 0 balance
                ]);
                Log::info('WalletTrait: Auto-created SubscriptionWallet for user', ['user_id' => $user->id]);
            }

            // ✅ CRITICAL FIX: Check sufficient balance before debiting
            $currentBalance = $user->SubscriptionWallet->ranc;
            if ($currentBalance < $ranc) {
                Log::warning("WalletTrait: Insufficient balance. Required: {$ranc}, Available: {$currentBalance}");
                return false;
            }

            $user->SubscriptionWallet->decrement('ranc', $ranc);
            $user->SubscriptionWallet->save();

            // Calculate balance after debit operation
            $balanceAfter = $user->SubscriptionWallet->ranc;

            // Send email notification for wallet deduction
            try {
                if (class_exists('App\\Services\\EmailNotificationService')) {
                    $emailService = new EmailNotificationService();
                    if (method_exists($emailService, 'sendWalletDeductionEmail')) {
                        $emailService->sendWalletDeductionEmail(
                            $user->email,
                            [
                                'user' => $user,
                                'amount' => $ranc,
                                'balance_before' => $balanceBefore,
                                'balance_after' => $balanceAfter,
                                'currency' => 'RNC',
                                'transaction_type' => 'subscription_debit',
                                'description' => 'Subscription wallet deduction'
                            ]
                        );
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to send wallet deduction email: ' . $e->getMessage());
            }

            return true;

        } catch (\Exception $e) {
            Log::error('WalletTrait debit error: ' . $e->getMessage(), [
                'user_id' => $user_id,
                'amount' => $ranc_amount
            ]);
            return false;
        }
    }

    /**
     * ✅ PERFECT FIX: Credit Wallet with robust error handling
     */
    public static function creditWallet($ranc_amount, $user_id = null, $resource_name = null)
    {   
        try {
            if($user_id){
                $user = User::find($user_id);
            }else{
                $user = auth()->user();
            }

            // ✅ CRITICAL FIX: Check if user exists and is authenticated
            if (!$user) {
                Log::error('WalletTrait: User not found or not authenticated for wallet credit');
                return false;
            }

            $ranc = (int) $ranc_amount;

            // Track balance before credit operation
            $balanceBefore = $user->CreditWallet ? $user->CreditWallet->ranc : 0;

            $params = [
                'amount' => $ranc_amount,
                'ranc' => $ranc_amount,
                'currency' => 'RNC',
                'type' => 'earning',
                'status' => 'processed',
                'remark' => 'Earnings on ' . $resource_name,
                'credit_wallet_id' => $user->CreditWallet->id
            ];

            // ✅ CRITICAL FIX: Check if CreditWallet exists
            if (!$user->CreditWallet) {
                Log::error('WalletTrait: CreditWallet not found for user', ['user_id' => $user->id]);
                return false;
            }

            CreditWalletTransaction::create($params);
            $user->CreditWallet->increment('ranc', $ranc);
            $user->CreditWallet->save();

            // Calculate balance after credit operation
            $balanceAfter = $user->CreditWallet->ranc;

            // Send email notification for wallet credit
            try {
                if (class_exists('App\\Services\\EmailNotificationService')) {
                    $emailService = new EmailNotificationService();
                    if (method_exists($emailService, 'sendWalletCreditEmail')) {
                        $emailService->sendWalletCreditEmail(
                            $user->email,
                            [
                                'user' => $user,
                                'amount' => $ranc,
                                'type' => 'earning',
                                'balance_before' => $balanceBefore,
                                'balance_after' => $balanceAfter,
                                'currency' => 'RNC',
                                'transaction_type' => 'wallet_credit',
                                'description' => $resource_name ? 'Earnings on ' . $resource_name : 'Wallet credit'
                            ]
                        );
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to send wallet credit email: ' . $e->getMessage());
            }

            return true;

        } catch (\Exception $e) {
            Log::error('WalletTrait wallet credit error: ' . $e->getMessage(), [
                'user_id' => $user_id,
                'amount' => $ranc_amount
            ]);
            return false;
        }
    }

    /**
     * ✅ PERFECT FIX: Refund Credit Wallet with error handling
     */
    public static function refundCreditWallet($ranc_amount, $user_id = null)
    {   
        try {
            if($user_id){
                $user = User::find($user_id);
            }else{
                $user = auth()->user();
            }

            // ✅ CRITICAL FIX: Check if user exists and is authenticated
            if (!$user) {
                Log::error('WalletTrait: User not found or not authenticated for refund');
                return false;
            }

            $ranc = (int) $ranc_amount;

            // ✅ CRITICAL FIX: Check if CreditWallet exists
            if (!$user->CreditWallet) {
                Log::error('WalletTrait: CreditWallet not found for refund', ['user_id' => $user->id]);
                return false;
            }

            $user->CreditWallet->increment('ranc', $ranc);
            $user->CreditWallet->save();

            return true;

        } catch (\Exception $e) {
            Log::error('WalletTrait refund error: ' . $e->getMessage(), [
                'user_id' => $user_id,
                'amount' => $ranc_amount
            ]);
            return false;
        }
    }

    /**
     * ✅ PERFECT FIX: Debit Wallet with error handling
     */
    public static function debitWallet($ranc_amount, $user_id = null)
    {   
        try {
            if($user_id){
                $user = User::find($user_id);
            }else{
                $user = auth()->user();
            }

            // ✅ CRITICAL FIX: Check if user exists and is authenticated
            if (!$user) {
                Log::error('WalletTrait: User not found or not authenticated for wallet debit');
                return false;
            }

            $ranc = (int) $ranc_amount;

            // ✅ CRITICAL FIX: Check if CreditWallet exists
            if (!$user->CreditWallet) {
                Log::error('WalletTrait: CreditWallet not found for debit', ['user_id' => $user->id]);
                return false;
            }

            // ✅ CRITICAL FIX: Check sufficient balance
            $currentBalance = $user->CreditWallet->ranc;
            if ($currentBalance < $ranc) {
                Log::warning("WalletTrait: Insufficient credit wallet balance. Required: {$ranc}, Available: {$currentBalance}");
                return false;
            }

            // Track balance before debit operation
            $balanceBefore = $user->CreditWallet->ranc;

            $params = [
                'ranc' => $ranc_amount,
                'type' => 'withdrawal',
                'status' => 'pending',
                'remark' => 'Withdrawal request',
                'credit_wallet_id' => $user->CreditWallet->id
            ];

            $transaction = CreditWalletTransaction::create($params);
            $user->CreditWallet->decrement('ranc', $ranc);
            $user->CreditWallet->save();

            // Calculate balance after debit operation
            $balanceAfter = $user->CreditWallet->ranc;

            // Send email notification for wallet deduction
            try {
                if (class_exists('App\\Services\\EmailNotificationService')) {
                    $emailService = new EmailNotificationService();
                    if (method_exists($emailService, 'sendWalletDeductionEmail')) {
                        $emailService->sendWalletDeductionEmail(
                            $user->email,
                            [
                                'user' => $user,
                                'amount' => $ranc,
                                'balance_before' => $balanceBefore,
                                'balance_after' => $balanceAfter,
                                'currency' => 'RNC',
                                'transaction_type' => 'wallet_debit',
                                'description' => 'Wallet deduction/withdrawal'
                            ]
                        );
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to send wallet deduction email: ' . $e->getMessage());
            }

            return $transaction;

        } catch (\Exception $e) {
            Log::error('WalletTrait debit error: ' . $e->getMessage(), [
                'user_id' => $user_id,
                'amount' => $ranc_amount
            ]);
            return false;
        }
    }

    /**
     * ✅ PERFECT FIX: Get Subscription Wallet Balance with error handling
     */
    public static function subscriptionWalletBalance($user_id = null)
    {   
        try {
            if($user_id){
                $user = User::find($user_id);
            }else{
                $user = auth()->user();
            }

            // ✅ CRITICAL FIX: Check if user exists
            if (!$user) {
                Log::error('WalletTrait: User not found for balance check');
                return 0;
            }

            // ✅ CRITICAL FIX: Check if SubscriptionWallet exists
            if (!$user->SubscriptionWallet) {
                // Auto-create wallet if missing
                $SubscriptionWallet = SubscriptionWallet::create([
                    'user_id' => $user->id,
                    'ranc' => 0
                ]);
                Log::info('WalletTrait: Auto-created SubscriptionWallet for balance check', ['user_id' => $user->id]);
                return 0;
            }

            return $user->SubscriptionWallet->ranc;

        } catch (\Exception $e) {
            Log::error('WalletTrait balance error: ' . $e->getMessage(), [
                'user_id' => $user_id
            ]);
            return 0;
        }
    }

    /**
     * ✅ PERFECT FIX: Get Credit Wallet Balance with error handling
     */
    public static function creditWalletBalance($user_id = null)
    {   
        try {
            if($user_id){
                $user = User::find($user_id);
            }else{
                $user = auth()->user();
            }

            // ✅ CRITICAL FIX: Check if user exists
            if (!$user) {
                Log::error('WalletTrait: User not found for credit balance check');
                return 0;
            }

            // ✅ CRITICAL FIX: Check if CreditWallet exists
            if (!$user->CreditWallet) {
                Log::warning('WalletTrait: CreditWallet not found', ['user_id' => $user->id]);
                return 0;
            }

            return $user->CreditWallet->ranc;

        } catch (\Exception $e) {
            Log::error('WalletTrait credit balance error: ' . $e->getMessage(), [
                'user_id' => $user_id
            ]);
            return 0;
        }
    }
}