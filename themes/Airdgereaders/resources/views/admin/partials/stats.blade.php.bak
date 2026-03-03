@php
    // Get dashboard stats from controller
    $statsController = new \App\Http\Controllers\Admin\StatsController();
    $stats = $statsController->getDashboardStats();
@endphp

<div class="row">
    {{-- Total Users Card --}}
    <div class="col-lg-3 col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ number_format($stats['totalUsers']) }}</h3>
                        <p class="mb-0">Total Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Verified Users Card --}}
    <div class="col-lg-3 col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ number_format($stats['verifiedUsers']) }}</h3>
                        <p class="mb-0">Verified Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Accounts Card --}}
    <div class="col-lg-3 col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ number_format($stats['totalAccounts']) }}</h3>
                        <p class="mb-0">Active Accounts</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-wallet fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Subscriptions Card --}}
    <div class="col-lg-3 col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ number_format($stats['subscriptionCount']) }}</h3>
                        <p class="mb-0">
                            Subscriptions
                            @if(!$stats['paystackStatus'])
                                <small><i class="fas fa-exclamation-triangle" title="Payment service offline"></i></small>
                            @endif
                        </p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-credit-card fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Alert if Paystack API is offline --}}
@if(!$stats['paystackStatus'])
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Payment Service Notice:</strong> Paystack API is currently unavailable. 
                Dashboard data may be incomplete but system functionality remains intact.
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
@endif

{{-- Recent Users Section --}}
@if($stats['recentUsers']->count() > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock"></i> Recent Users
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recentUsers'] as $user)
                                <tr>
                                    <td>
                                        <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Verified
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user->created_at->format('M d, Y H:i') }}
                                        <br>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- JavaScript for any interactive features --}}
@push('scripts')

document.addEventListener('DOMContentLoaded', function() {

// Add any interactive features here

console.log('Dashboard stats loaded successfully');

});


@endpush

