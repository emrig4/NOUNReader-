@php
    // Get dashboard stats from controller (only for non-AJAX requests)
    if (!request()->ajax()) {
        $statsController = new \App\Http\Controllers\Admin\StatsController();
        $stats = $statsController->getDashboardStats();
    } else {
        $stats = [
            'totalUsers' => 0,
            'verifiedUsers' => 0,
            'totalAccounts' => 0,
            'subscriptionCount' => 0,
            'paystackStatus' => true,
            'recentUsers' => collect()
        ];
    }
@endphp

<div class="row">
    {{-- Total Users Card --}}
    <div class="col-lg-3 col-md-6">
        <div class="card bg-primary text-white" data-stat="totalUsers">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0" data-number="true">{{ number_format($stats['totalUsers']) }}</h3>
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
        <div class="card bg-success text-white" data-stat="verifiedUsers">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0" data-number="true">{{ number_format($stats['verifiedUsers']) }}</h3>
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
        <div class="card bg-info text-white" data-stat="totalAccounts">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0" data-number="true">{{ number_format($stats['totalAccounts']) }}</h3>
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
        <div class="card bg-warning text-white" data-stat="subscriptionCount">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0" data-number="true">{{ number_format($stats['subscriptionCount']) }}</h3>
                        <p class="mb-0">
                            Subscriptions
                            @if(isset($stats['paystackStatus']) && !$stats['paystackStatus'])
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
@if(isset($stats['paystackStatus']) && !$stats['paystackStatus'])
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
@if(isset($stats['recentUsers']) && $stats['recentUsers']->count() > 0)
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
                                        <strong>{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}</strong>
                                    </td>
                                    <td>{{ $user->email ?? '' }}</td>
                                    <td>
                                        @if(isset($user->email_verified_at) && $user->email_verified_at)
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
                                        @if(isset($user->created_at))
                                            {{ $user->created_at->format('M d, Y H:i') }}
                                            <br>
                                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                        @endif
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

{{-- Include Session-Based Loader --}}
@include('admin.partials.session_loader')

{{-- Enhanced JavaScript for Interactive Features --}}
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Add fade-in animation to cards after loading
    const cards = document.querySelectorAll('.card[data-stat]');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 100);
    });
    
    // Add click handlers for cards to refresh individual stats
    cards.forEach(card => {
        card.addEventListener('dblclick', function() {
            const statName = this.getAttribute('data-stat');
            refreshIndividualStat(statName, this);
        });
    });
    
    console.log('Enhanced dashboard stats loaded successfully');
});

function refreshIndividualStat(statName, cardElement) {
    // Remove any existing error states
    cardElement.classList.remove('card-error');
    const retryOverlay = cardElement.querySelector('.retry-overlay');
    if (retryOverlay) {
        retryOverlay.remove();
    }
    
    // Show loading state
    const numberElement = cardElement.querySelector('h3');
    if (numberElement) {
        numberElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    }
    
    // Fetch individual stat
    fetch(`/admin/api/stats/${statName}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (numberElement) {
            numberElement.textContent = data.value ? data.value.toLocaleString() : '0';
        }
        cardElement.classList.add('fade-in');
        
        // Show success feedback
        showTemporaryNotification(`Updated ${statName}`, 'success');
    })
    .catch(error => {
        console.error(`Failed to refresh ${statName}:`, error);
        if (numberElement) {
            numberElement.textContent = 'Error';
        }
        showTemporaryNotification(`Failed to update ${statName}`, 'error');
    });
}

function showTemporaryNotification(message, type = 'info') {
    // Create temporary notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}
</script>

@endpush
