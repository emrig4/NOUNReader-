<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Modules\Wallet\Http\Traits\WalletTrait;
use Illuminate\Support\Facades\Log;

class AdminCreditController extends \Illuminate\Routing\Controller
{
    use WalletTrait;

    /**
     * Display the admin manual credit interface
     */
    public function index()
    {
        try {
            // Get all users with their wallet balances
            $users = DB::table('users')
                ->leftJoin('subscription_wallets', 'users.id', '=', 'subscription_wallets.user_id')
                ->select(
                    'users.id',
                    'users.first_name', 
                    'users.last_name', 
                    'users.username', 
                    'users.email',
                    DB::raw('COALESCE(subscription_wallets.ranc, 0) as ranc'),
                    DB::raw('COALESCE(subscription_wallets.active, 0) as active')
                )
                ->orderBy('users.last_name')
                ->orderBy('users.first_name')
                ->get();

            return view('admin-credit', compact('users'));

        } catch (\Exception $e) {
            Session::flash('error', 'Error loading users: ' . $e->getMessage());
            return view('admin-credit', ['users' => collect([])]);
        }
    }

    /**
     * Process manual credit request
     * Uses WalletTrait::creditSubscriptionWallet() for proper notifications and transaction logging
     */
    public function processCredit(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:255'
        ]);

        $userId = (int) $request->input('user_id');
        $amount = (float) $request->input('amount');
        $reason = $request->input('reason');

        try {
            // Find user by ID
            $user = DB::table('users')->where('id', $userId)->first();
            
            if (!$user) {
                Session::flash('error', 'User not found');
                return redirect()->route('admin.credit.index');
            }

            // Get user's current wallet balance before credit
            $subscriptionWallet = DB::table('subscription_wallets')->where('user_id', $userId)->first();
            $balanceBefore = $subscriptionWallet->ranc ?? 0;

            // ✅ CORRECT WAY: Use WalletTrait method instead of direct database increment
            Log::info('AdminCreditController: Starting manual credit process', [
                'user_id' => $userId,
                'amount' => $amount,
                'reason' => $reason,
                'balance_before' => $balanceBefore
            ]);

            // This method will:
            // 1. Credit the subscription wallet
            // 2. Log the transaction automatically
            // 3. Send wallet credit email notification automatically
            self::creditSubscriptionWallet($amount, 'credit', $userId); // ✅ FIXED: 'credit' instead of 'admin_manual'

            // Get the new balance after credit
            $wallet = DB::table('subscription_wallets')->where('user_id', $userId)->first();
            $newBalance = $wallet->ranc ?? 0;

            Log::info('AdminCreditController: Manual credit completed successfully', [
                'user_id' => $userId,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $newBalance
            ]);

            // Format user name
            $userName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            if (empty($userName)) $userName = $user->username ?? $user->email;

            // Success message
            Session::flash('success', 
                "✅ Successfully credited {$amount} RANC to {$userName} ({$user->email})!<br>" .
                "💰 Balance changed: {$balanceBefore} → {$newBalance} RANC<br>" .
                "📝 Reason: {$reason}<br>" .
                "📧 Email notification sent automatically"
            );

            return redirect()->route('admin.credit.index');

        } catch (\Exception $e) {
            Log::error('AdminCreditController: Manual credit failed', [
                'user_id' => $userId,
                'amount' => $amount,
                'reason' => $reason,
                'error' => $e->getMessage()
            ]);
            Session::flash('error', '❌ Error: ' . $e->getMessage());
            return redirect()->route('admin.credit.index');
        }
    }

    /**
     * Quick credit via AJAX
     */
    public function quickCredit(Request $request)
    {
        $userId = $request->input('user_id');
        $amount = (float) $request->input('amount');
        $reason = $request->input('reason', 'Quick Credit');

        try {
            $user = DB::table('users')->where('id', $userId)->first();
            
            if (!$user) {
                return response()->json(['success' => false, 'error' => 'User not found']);
            }

            // ✅ CORRECT WAY: Use WalletTrait method instead of direct database increment
            Log::info('AdminCreditController: Starting quick credit process', [
                'user_id' => $userId,
                'amount' => $amount,
                'reason' => $reason
            ]);

            // This method will:
            // 1. Credit the subscription wallet
            // 2. Log the transaction automatically
            // 3. Send wallet credit email notification automatically
            self::creditSubscriptionWallet($amount, 'credit', $userId); // ✅ FIXED: 'credit' instead of 'quick_credit'

            $wallet = DB::table('subscription_wallets')->where('user_id', $userId)->first();
            $newBalance = $wallet->ranc ?? 0;

            Log::info('AdminCreditController: Quick credit completed successfully', [
                'user_id' => $userId,
                'amount' => $amount,
                'new_balance' => $newBalance
            ]);

            return response()->json([
                'success' => true,
                'message' => "Credited {$amount} RANC successfully. Email notification sent!",
                'new_balance' => $newBalance,
                'user_email' => $user->email
            ]);

        } catch (\Exception $e) {
            Log::error('AdminCreditController: Quick credit failed', [
                'user_id' => $userId,
                'amount' => $amount,
                'reason' => $reason,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}