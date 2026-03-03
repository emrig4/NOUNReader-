<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StatsApiController extends Controller
{
    /**
     * Check user session validity
     */
    public function checkSession(): JsonResponse
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'invalid',
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            // Check session timeout
            $lastActivity = Session::get('last_activity');
            if ($lastActivity && (time() - $lastActivity > 1800)) { // 30 minutes
                Session::forget('last_activity');
                Auth::logout();
                
                return response()->json([
                    'status' => 'expired',
                    'message' => 'Session expired'
                ], 401);
            }
            
            // Update last activity
            Session::put('last_activity', time());
            
            return response()->json([
                'status' => 'valid',
                'message' => 'Session valid',
                'user' => [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Session check failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Session validation failed'
            ], 500);
        }
    }
    
    /**
     * Get total users count
     */
    public function totalUsers(): JsonResponse
    {
        try {
            $count = \App\Models\User::count();
            
            return response()->json([
                'value' => $count,
                'label' => 'Total Users',
                'status' => 'success'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to get total users: ' . $e->getMessage());
            
            return response()->json([
                'value' => 0,
                'label' => 'Total Users',
                'status' => 'error',
                'message' => 'Failed to load total users'
            ], 500);
        }
    }
    
    /**
     * Get verified users count
     */
    public function verifiedUsers(): JsonResponse
    {
        try {
            $count = \App\Models\User::whereNotNull('email_verified_at')->count();
            
            return response()->json([
                'value' => $count,
                'label' => 'Verified Users',
                'status' => 'success'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to get verified users: ' . $e->getMessage());
            
            return response()->json([
                'value' => 0,
                'label' => 'Verified Users',
                'status' => 'error',
                'message' => 'Failed to load verified users'
            ], 500);
        }
    }
    
    /**
     * Get active accounts count
     */
    public function activeAccounts(): JsonResponse
    {
        try {
            $count = \App\Models\User::where('status', 'active')->count();
            
            return response()->json([
                'value' => $count,
                'label' => 'Active Accounts',
                'status' => 'success'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to get active accounts: ' . $e->getMessage());
            
            return response()->json([
                'value' => 0,
                'label' => 'Active Accounts',
                'status' => 'error',
                'message' => 'Failed to load active accounts'
            ], 500);
        }
    }
    
    /**
     * Get subscription count
     */
    public function subscriptions(): JsonResponse
    {
        try {
            $count = 0;
            
            // Check if subscription model exists
            if (class_exists(\App\Models\Subscription::class)) {
                $count = \App\Models\Subscription::count();
            } elseif (class_exists(\App\Modules\Subscription\Models\Subscription::class)) {
                $count = \App\Modules\Subscription\Models\Subscription::count();
            }
            
            return response()->json([
                'value' => $count,
                'label' => 'Subscriptions',
                'status' => 'success'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to get subscriptions: ' . $e->getMessage());
            
            return response()->json([
                'value' => 0,
                'label' => 'Subscriptions',
                'status' => 'error',
                'message' => 'Failed to load subscriptions'
            ], 500);
        }
    }
    
    /**
     * Get recent users data
     */
    public function recentUsers(): JsonResponse
    {
        try {
            $users = \App\Models\User::latest()
                ->limit(10)
                ->get(['id', 'first_name', 'last_name', 'email', 'email_verified_at', 'created_at']);
            
            return response()->json([
                'data' => $users,
                'count' => $users->count(),
                'status' => 'success'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to get recent users: ' . $e->getMessage());
            
            return response()->json([
                'data' => [],
                'count' => 0,
                'status' => 'error',
                'message' => 'Failed to load recent users'
            ], 500);
        }
    }
    
    /**
     * Get all dashboard stats at once (fallback method)
     */
    public function allStats(): JsonResponse
    {
        try {
            $stats = [
                'totalUsers' => \App\Models\User::count(),
                'verifiedUsers' => \App\Models\User::whereNotNull('email_verified_at')->count(),
                'totalAccounts' => \App\Models\User::where('status', 'active')->count(),
                'subscriptionCount' => 0
            ];
            
            // Try to get subscription count
            if (class_exists(\App\Models\Subscription::class)) {
                $stats['subscriptionCount'] = \App\Models\Subscription::count();
            } elseif (class_exists(\App\Modules\Subscription\Models\Subscription::class)) {
                $stats['subscriptionCount'] = \App\Modules\Subscription\Models\Subscription::count();
            }
            
            return response()->json([
                'stats' => $stats,
                'status' => 'success'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to get all stats: ' . $e->getMessage());
            
            return response()->json([
                'stats' => [
                    'totalUsers' => 0,
                    'verifiedUsers' => 0,
                    'totalAccounts' => 0,
                    'subscriptionCount' => 0
                ],
                'status' => 'error',
                'message' => 'Failed to load dashboard stats'
            ], 500);
        }
    }
}