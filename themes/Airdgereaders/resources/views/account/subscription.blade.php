@extends('layouts.account')
@push('css')
    <link href="{{ asset('themes/airdgereaders/css/stats.css') }}" rel="stylesheet">
@endpush

@php
    $subscriptionWallet = account()->SubscriptionWallet;
    $subscriptionBalance = $subscriptionWallet ? $subscriptionWallet->ranc : 0;
@endphp

@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-counterfull">
        
        <!-- dashboard nav -->
        @include('partials.usermenu')

        <div class="container" style="width: 100%">
            <div class="row">
                <!-- Main Subscription Wallet Balance Card -->
                <div class="col-md-12">
                    <div class="ereaders-shop-detail">
                        <div class="flex justify-center relative -mt-24" id="alert-holder">
                            <div class="alert text-center my-5 border-2 shadow-lg rounded-xl p-8 max-w-md" style="background: #481904ff; border-color: #23A455;">
                                <img class="mx-auto h-12 mb-4" src="{{ theme_asset('images/ranc.jpg') }}">
                                <h2 class="text-2xl font-bold text-white mb-2">Credit Balance</h2>
                                <p class="text-4xl font-bold mb-4" style="color: white;">{{ $subscriptionBalance }} Credits</p>
                                
                                <!-- Prominent Buy More Credits Button -->
                                <a href="https://projectandmaterials.com/pricings" target="_blank" class="inline-block w-full text-white font-bold py-4 px-6 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg" style="background: #23A455;" onmouseover="this.style.background='#1e8f49'" onmouseout="this.style.background='#23A455'">
                                    <i class="icon ereaders-shopping-bag mr-2"></i>
                                    Buy More Credits
                                </a>
                            </div>  
                        </div>
                    </div>
                </div>

                <!-- Credit Usage Information -->
                <div class="col-md-12">
                    <div class="ereaders-shop-detail">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8 ms-md-auto">
                                <div class="ereaders-detail-thumb-text">
                                    <h2 class="uppercase text-bold">How Credits Work</h2>
                                    
                                    <div class="mt-4 space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 text-white rounded-full flex items-center justify-center text-sm font-bold" style="background: #23A455;">1</div>
                                            <div>
                                                <h4 class="font-semibold text-lg">Buy Credits</h4>
                                                <p class="text-gray-600">Purchase credit packages using secure payment methods. Credits never expire. <strong>Minimum purchase: 4900 credits</strong></p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 text-white rounded-full flex items-center justify-center text-sm font-bold" style="background: #23A455;">2</div>
                                            <div>
                                                <h4 class="font-semibold text-lg">Use Credits</h4>
                                                <p class="text-gray-600">Spend credits to access premium content, downloads, and features.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 text-white rounded-full flex items-center justify-center text-sm font-bold" style="background: #23A455;">3</div>
                                            <div>
                                                <h4 class="font-semibold text-lg">Track Usage</h4>
                                                <p class="text-gray-600">Monitor your credit balance and transaction history in your dashboard.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction History -->
                <div class="col-md-12">
                    <div class="ereaders-shop-detail">
                        <div class="row">
                            <div class="col-md-12 ms-md-auto" style="overflow-x: scroll;">
                                <div class="ereaders-detail-thumb-text">
                                    <h2 class="uppercase text-bold">Credit Transaction History</h2>

                                    <div class="clearfix"></div>
                                    
                                    @php
                                        // ✅ FIXED: Handle null SubscriptionWallet safely
                                        $transactions = collect(); // Default to empty collection
                                        
                                        // Only query transactions if SubscriptionWallet exists
                                        if ($subscriptionWallet && $subscriptionWallet->id) {
                                            try {
                                                $transactions = \App\Modules\Wallet\Models\CreditWalletTransaction::where('credit_wallet_id', $subscriptionWallet->id)
                                                    ->latest()
                                                    ->limit(20)
                                                    ->get();
                                            } catch (\Exception $e) {
                                                // Log the error but don't crash the page
                                                \Log::warning('Failed to load SubscriptionWallet transactions', [
                                                    'subscription_wallet_id' => $subscriptionWallet->id,
                                                    'error' => $e->getMessage()
                                                ]);
                                                $transactions = collect(); // Empty collection on error
                                            }
                                        } else {
                                            // Log that SubscriptionWallet is missing for debugging
                                            \Log::info('SubscriptionWallet not found for user', [
                                                'user_id' => auth()->id(),
                                                'has_account' => !!account(),
                                                'account_id' => account() ? account()->id : null
                                            ]);
                                        }
                                    @endphp
                                    
                                    @if($transactions->count() > 0)
                                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                            <table class="table table-responsive">
                                                <thead class="bg-gray-50">
                                                    <th class="text-left uppercase px-4 py-3 text-sm font-semibold text-gray-600">Date</th>
                                                    <th class="text-left uppercase px-4 py-3 text-sm font-semibold text-gray-600">Type</th>
                                                    <th class="text-left uppercase px-4 py-3 text-sm font-semibold text-gray-600">Credits</th>
                                                    <th class="text-left uppercase px-4 py-3 text-sm font-semibold text-gray-600">Amount</th>
                                                    <th class="text-left uppercase px-4 py-3 text-sm font-semibold text-gray-600">Status</th>
                                                    <th class="text-left uppercase px-4 py-3 text-sm font-semibold text-gray-600">Reference</th>
                                                </thead>
                                                <tbody>
                                                    @foreach($transactions as $transaction)
                                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                            <td class="px-4 py-3 text-sm">{{ $transaction->created_at->format('M j, Y H:i') }}</td>
                                                            <td class="px-4 py-3">
                                                                @if($transaction->type == 'credit_purchase')
                                                                    <span class="px-3 py-1 rounded-full text-xs font-medium" style="background: #23A455; color: white;">Credit Purchase</span>
                                                                @elseif($transaction->type == 'earning')
                                                                    <span class="px-3 py-1 rounded-full text-xs font-medium" style="background: #23A455; color: white;">Earning</span>
                                                                @else
                                                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">{{ ucfirst($transaction->type) }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="px-4 py-3 font-semibold" style="color: #23A455;">{{ $transaction->ranc }}</td>
                                                            <td class="px-4 py-3 text-sm">
                                                                @if($transaction->amount)
                                                                    ₦{{ number_format($transaction->amount, 2) }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td class="px-4 py-3">
                                                                @if($transaction->status == 'processed')
                                                                    <span class="px-3 py-1 rounded-full text-xs font-medium" style="background: #23A455; color: white;">Completed</span>
                                                                @elseif($transaction->status == 'pending')
                                                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Pending</span>
                                                                @else
                                                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">{{ ucfirst($transaction->status) }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="px-4 py-3 text-sm text-gray-500">{{ $transaction->reference ?? 'N/A' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                                            <div class="mb-4">
                                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <p class="text-lg text-gray-600 mb-4">No transactions yet. Buy some credits to get started!</p>
                                            <a href="https://projectandmaterials.com/pricings" target="_blank" class="inline-block text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105" style="background: #23A455;" onmouseover="this.style.background='#1e8f49'" onmouseout="this.style.background='#23A455'">
                                                Buy Credits Now
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--// Main Section \\-->


@endsection

@push('js')
@endpush