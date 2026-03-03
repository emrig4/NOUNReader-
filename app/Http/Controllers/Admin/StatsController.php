<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StatsController extends Controller
{
    public function getDashboardStats()
    {
        try {
            // Get basic stats (always available)
            $basicStats = [
                'totalUsers' => \App\Models\User::count(),
                'verifiedUsers' => \App\Models\User::whereNotNull('email_verified_at')->count(),
                'totalAccounts' => \App\Modules\Account\Models\Account::count(),
                'recentUsers' => \App\Models\User::latest()->take(5)->get(),
            ];

            // Try to get Paystack data with error handling
            $paystackData = $this->getPaystackSubscriptionData();
            $basicStats['subscriptionCount'] = $paystackData['count'] ?? 0;
            $basicStats['paystackStatus'] = $paystackData['status'] ?? false;

            return $basicStats;

        } catch (\Exception $e) {
            Log::warning('Dashboard stats error: ' . $e->getMessage());
            
            // Fallback when everything fails
            return [
                'totalUsers' => 0,
                'verifiedUsers' => 0,
                'totalAccounts' => 0,
                'recentUsers' => collect([]),
                'subscriptionCount' => 0,
                'paystackStatus' => false,
            ];
        }
    }

    private function getPaystackSubscriptionData()
    {
        try {
            $response = Http::timeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . config('services.paystack.secret', env('PAYSTACK_SECRET_KEY')),
                'Accept' => 'application/json',
            ])->get('https://api.paystack.co/subscription');

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'status' => true,
                    'count' => $data['meta']['total'] ?? 0,
                    'data' => $data['data'] ?? [],
                ];
            }

            return ['status' => false, 'count' => 0];

        } catch (\Exception $e) {
            Log::warning('Paystack API error: ' . $e->getMessage());
            return ['status' => false, 'count' => 0];
        }
    }
}