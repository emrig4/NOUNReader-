<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Services\AutoCreditService;
use App\Modules\Setting\Models\Setting;

class AutoCreditController extends Controller
{
    protected $autoCreditService;

    public function __construct(AutoCreditService $autoCreditService)
    {
        $this->autoCreditService = $autoCreditService;
    }

    /**
     * Display auto credit settings page
     */
    public function index()
    {
        $settings = [
            'enabled' => $this->autoCreditService->isAutoCreditEnabled(),
            'amount' => $this->autoCreditService->getAutoCreditAmount(),
            'message' => Setting::get('auto_credit_message', 'Welcome! You\'ve received {amount} RANC as a first-time user bonus!')
        ];

        return view('admin.auto-credit', compact('settings'));
    }

    /**
     * Update auto credit settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'enabled' => 'boolean',
            'amount' => 'required|numeric|min:0',
            'message' => 'required|string|max:500'
        ]);

        try {
            // Update auto credit enabled status
            if ($request->boolean('enabled')) {
                $result = $this->autoCreditService->enableAutoCredit();
            } else {
                $result = $this->autoCreditService->disableAutoCredit();
            }

            if (!$result) {
                Session::flash('error', 'Failed to update auto credit status');
                return redirect()->route('admin.auto-credit.index');
            }

            // Update auto credit amount
            $amountResult = $this->autoCreditService->setAutoCreditAmount($request->input('amount'));
            
            if (!$amountResult) {
                Session::flash('error', 'Failed to update auto credit amount');
                return redirect()->route('admin.auto-credit.index');
            }

            // Update welcome message
            Setting::set('auto_credit_message', $request->input('message'));

            Session::flash('success', 'Auto credit settings updated successfully!');
            return redirect()->route('admin.auto-credit.index');

        } catch (\Exception $e) {
            Log::error("AutoCreditController: Error updating settings", [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            Session::flash('error', 'Error updating settings: ' . $e->getMessage());
            return redirect()->route('admin.auto-credit.index');
        }
    }

    /**
     * Test auto credit for a specific user (for admin testing)
     */
    public function testAutoCredit(Request $request, $userId)
    {
        try {
            $user = \App\Models\User::find($userId);
            
            if (!$user) {
                Session::flash('error', 'User not found');
                return redirect()->route('admin.auto-credit.index');
            }

            $result = $this->autoCreditService->grantAutoCredit($user);
            
            if ($result) {
                Session::flash('success', "Auto credit granted successfully to {$user->email}");
            } else {
                $reason = 'Auto credit may be disabled or user has already received it';
                Session::flash('warning', "Auto credit not granted: {$reason}");
            }

        } catch (\Exception $e) {
            Log::error("AutoCreditController: Error testing auto credit", [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            Session::flash('error', 'Error testing auto credit: ' . $e->getMessage());
        }

        return redirect()->route('admin.auto-credit.index');
    }

    /**
     * Get statistics about auto credit usage
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_credits_granted' => \DB::table('wallet_transactions')
                    ->where('description', 'LIKE', '%First-time user auto credit%')
                    ->count(),
                'total_amount_credited' => \DB::table('wallet_transactions')
                    ->where('description', 'LIKE', '%First-time user auto credit%')
                    ->sum('amount'),
                'recent_credits' => \DB::table('wallet_transactions')
                    ->where('description', 'LIKE', '%First-time user auto credit%')
                    ->join('users', 'wallet_transactions.user_id', '=', 'users.id')
                    ->select(
                        'wallet_transactions.*',
                        'users.email',
                        'users.first_name',
                        'users.last_name'
                    )
                    ->orderBy('wallet_transactions.created_at', 'desc')
                    ->limit(10)
                    ->get()
            ];

            return view('admin.auto-credit-stats', compact('stats'));

        } catch (\Exception $e) {
            Log::error("AutoCreditController: Error loading statistics", [
                'error' => $e->getMessage()
            ]);
            
            Session::flash('error', 'Error loading statistics: ' . $e->getMessage());
            return redirect()->route('admin.auto-credit.index');
        }
    }
}