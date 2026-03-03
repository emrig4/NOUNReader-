<?php

namespace App\Modules\Subscription\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Digikraaft\Paystack\Plan;

// use Digikraaft\PaystackWebhooks\Http\Controllers\WebhooksController as PaystackWebhooksController;
use App\Modules\Subscription\Events\PaystackWebhookEvent;

use Digikraaft\Paystack\Paystack;
use Spatie\SlackAlerts\Facades\SlackAlert;
use Digikraaft\PaystackSubscription\Payment;
use App\Modules\Subscription\Http\Traits\SubscriptionTrait;
use Digikraaft\Paystack\Subscription as PaystackSubscription;
use App\Modules\Wallet\Http\Traits\WalletTrait;

use App\Modules\Payment\Models\Payment as readprojecttopicsPayment;

class SubscriptionController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
        Paystack::setApiKey(config('paystacksubscription.secret', env('PAYSTACK_SECRET')));
    }

    /**
     * Handle WebhookEvent.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhookEvent(){}

    /**
     * Handle Paystack payment verification for credit purchases
     * 
     * @throws \Digikraaft\PaystackSubscription\Exceptions\PaymentFailure
     */
    public function verifyPaystack(Request $request)
    {
        try {
            // get transaction reference returned by Paystack
            $transactionRef = $request->reference;

            if (!$transactionRef) {
                throw new \Exception('Transaction reference is required');
            }

            // verify the transaction is valid
            $transaction = Payment::hasValidTransaction($transactionRef);

            if ($transaction) {
                return $this->handleCreditPurchaseSuccess($transaction);
            }
            
            throw new \Exception('Invalid or failed transaction');

        } catch (\Exception $e) {
            \Log::error('Paystack verification failed: ' . $e->getMessage(), [
                'reference' => $request->reference,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->header('Content-Type') == 'application/json') {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Payment verification failed: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    /**
     * Handle successful credit purchase (one-time payment)
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleCreditPurchaseSuccess($payload)
    {
        try {
            // Store payment record
            $transaction = $this->storePayment($payload->data);

            // Convert payment amount to credits and credit wallet
            if ($payload->data->metadata->txntype == 'buycredit') {
                $base_amount = $transaction->amount;
                $fiat_currency = $transaction->currency;
                $fiat_amount = $base_amount / 100;
                $ranc_amount = ranc_equivalent($fiat_amount, $fiat_currency);
                
                // Credit the user's subscription wallet with purchased credits
                WalletTrait::creditSubscriptionWallet($ranc_amount);
                $response = $transaction;

            } else {
                // Handle any other transaction types (for backwards compatibility)
                \Log::warning('Unexpected transaction type received', [
                    'txntype' => $payload->data->metadata->txntype ?? 'unknown',
                    'reference' => $transaction->reference
                ]);
                
                // If not a credit purchase, treat as credit purchase anyway
                $base_amount = $transaction->amount;
                $fiat_currency = $transaction->currency;
                $fiat_amount = $base_amount / 100;
                $ranc_amount = ranc_equivalent($fiat_amount, $fiat_currency);
                
                WalletTrait::creditSubscriptionWallet($ranc_amount);
                $response = $transaction;
            }

            // Log successful credit purchase
            \Log::info('Credit purchase processed successfully', [
                'user_id' => auth()->id(),
                'transaction_reference' => $transaction->reference,
                'amount' => $fiat_amount,
                'credits_added' => $ranc_amount
            ]);

            if (request()->header('Content-Type') == 'application/json') {
                return response()->json([
                    'status' => 'success', 
                    'data' => [
                        'transaction_reference' => $transaction->reference,
                        'amount' => $fiat_amount,
                        'credits_added' => $ranc_amount,
                        'message' => 'Credits purchased successfully'
                    ]
                ], 201);
            }

            // For web requests, redirect to subscription page (which shows wallet info)
            return redirect('/account/subscription')->with('success', 'Credits purchased successfully! Your wallet has been credited with ' . $ranc_amount . ' credits.');

        } catch (\Exception $e) {
            \Log::error('Credit purchase processing failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'payload' => $payload,
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->header('Content-Type') == 'application/json') {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Credit purchase processing failed: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Credit purchase processing failed. Please contact support.');
        }
    }

    /**
     * Show view that displays available credit packages
     */
    public function showBillingPlans()
    {
        Paystack::setApiKey(env('PAYSTACK_SECRET'));
        $plans = Plan::list()->data;

        return view('billing_plans', compact('plans'));
    }

    /**
     * Store Payment record
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function storePayment($payload)
    {
        // Store payment
        $paymentMeta = $payload->metadata;
        $verifiedChargeAmount = $payload->amount;
        $verifiedChargeCurrency = $payload->currency;
        $userId = $paymentMeta->user_id ?? auth()->id();

        $paymentData = [
          'status' => $payload->status,
          'reference' => $payload->reference,
          'txntype' => $paymentMeta->txntype ?? 'buycredit', // Default to buycredit for one-time purchases
          'paid_at' => $payload->paid_at ?? now(),
          'gateway' => 'paystack',
          'channel' => 'online',
          'amount' => $verifiedChargeAmount,
          'currency' => $verifiedChargeCurrency,
          'meta' => json_encode($paymentMeta),
          'user_id' => $userId
        ];

        return readprojecttopicsPayment::updateOrCreate(
            ['reference' => $paymentData['reference'] ],
            $paymentData
        );
    }

    /**
     * Credit User Subscription wallet - one-time credit purchase
     *
     * @param  int  $credits
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createSubscriptionWallet($credits)
    {
        // Use WalletTrait for direct wallet crediting
        WalletTrait::creditSubscriptionWallet($credits, 'credit_purchase');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Wallet credited successfully with ' . $credits . ' credits'
        ]);
    }

    /**
     * Show user's wallet and credit balance
     */
    public function showWallet()
    {
        $user = auth()->user();
        $subscriptionWallet = $user->SubscriptionWallet;
        $creditWallet = $user->CreditWallet;
        
        // Get recent credit purchase transactions
        $recentTransactions = \App\Modules\Wallet\Models\CreditWalletTransaction::where('credit_wallet_id', $creditWallet->id)
            ->latest()
            ->limit(10)
            ->get();
            
        return view('account.wallet', compact('subscriptionWallet', 'creditWallet', 'recentTransactions'));
    }

    /**
     * Get wallet balance via API
     */
    public function getWalletBalance()
    {
        $user = auth()->user();
        $subscriptionBalance = WalletTrait::subscriptionWalletBalance($user->id);
        $creditBalance = WalletTrait::creditWalletBalance($user->id);
        
        return response()->json([
            'subscription_balance' => $subscriptionBalance,
            'credit_balance' => $creditBalance,
            'total_balance' => $subscriptionBalance + $creditBalance
        ]);
    }

    // ========================================================================
    // LEGACY SUBSCRIPTION METHODS (DEPRECATED - KEPT FOR BACKWARDS COMPATIBILITY)
    // ========================================================================

    /**
     * This method is deprecated - subscriptions are no longer created
     * Users now purchase credits directly instead of subscriptions
     */
    public function createSubscription($payload)
    {
        \Log::warning('Deprecated method called: createSubscription. Credits are now purchased directly.', [
            'user_id' => auth()->id(),
            'payload' => $payload
        ]);
        
        // For backwards compatibility, just return success without creating subscription
        return (object) ['data' => (object) ['subscription_code' => 'CREDIT_PURCHASE_' . time()]];
    }

    /**
     * This method is deprecated - subscriptions are no longer stored
     */
    public function storeSubscription($payload)
    {
        \Log::warning('Deprecated method called: storeSubscription. Credits are now purchased directly.', [
            'user_id' => auth()->id(),
            'payload' => $payload
        ]);
        
        // For backwards compatibility, return a mock subscription object
        return (object) [
            'subscription_code' => 'CREDIT_PURCHASE_' . time(),
            'id' => time(),
            'name' => 'Credit Purchase',
            'updated_at' => now(),
            'next_payment_date' => null
        ];
    }

    /**
     * This method is deprecated - no subscriptions to cancel
     */
    public function cancelSubscription()
    {   
        \Log::info('Cancel subscription requested (no longer applicable)', [
            'user_id' => auth()->id(),
            'message' => 'Credit purchases are one-time transactions and cannot be cancelled'
        ]);
        
        return redirect()->back()->with('info', 'Credit purchases are one-time transactions and cannot be cancelled. Your purchased credits remain in your wallet permanently.');
    }

    /**
     * This method is deprecated - no subscriptions to restart
     */
    public function restartSubscription()
    {
        \Log::info('Restart subscription requested (no longer applicable)', [
            'user_id' => auth()->id(),
            'message' => 'Credit purchases are one-time transactions and cannot be restarted'
        ]);
        
        return redirect()->back()->with('info', 'Credit purchases are one-time transactions and cannot be restarted. Purchase more credits when needed.');
    }

    /**
     * This method is deprecated - no subscriptions to refresh
     */
    public function refreshSubscriptions()
    {
        \Log::info('Refresh subscriptions requested (no longer applicable)', [
            'user_id' => auth()->id(),
            'message' => 'Credit purchases are one-time transactions and cannot be refreshed'
        ]);
        
        return redirect()->back()->with('info', 'Credit purchases are one-time transactions and do not require refreshing.');
    }
}
