<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Manual Credit System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .user-card {
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
            border-radius: 10px;
            height: 100%;
        }
        .user-card:hover {
            border-color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .user-card.selected {
            border-color: #28a745;
            background-color: #f8fff9;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .balance-large {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .quick-buttons button {
            margin: 2px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            box-shadow: 0 0 0 2px rgba(255,255,255,0.8);
        }
        .status-active { 
            background: linear-gradient(45deg, #28a745, #20c997);
            animation: pulse 2s infinite;
        }
        .status-inactive { background-color: #dc3545; }
        .balance-zero { 
            background-color: #f8f9fa;
            color: #6c757d;
        }
        .balance-positive {
            background-color: #d4edda;
            color: #155724;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">
                    <i class="bi bi-wallet-fill text-primary"></i> 
                    Admin Manual Credit System
                </h1>
                <div class="text-muted">
                    <i class="bi bi-people-fill"></i>
                    {{ $users->count() }} Users
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $users->count() }}</h4>
                            <p class="card-text">Total Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $users->where('ranc', '>', 0)->count() }}</h4>
                            <p class="card-text">Active Wallets</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-wallet-fill" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $users->where('ranc', '==', 0)->count() }}</h4>
                            <p class="card-text">Zero Balance</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-wallet" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ number_format($users->sum('ranc'), 0) }}</h4>
                            <p class="card-text">Total RANC</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-coin" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Manual Credit Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle-fill"></i>
                        Manual Credit Form
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.credit.process') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <label for="user_select" class="form-label">Select User</label>
                                <select id="user_select" name="user_id" class="form-select" required>
                                    <option value="">Choose User...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->email }} ({{ number_format($user->ranc, 0) }} RANC)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="amount" class="form-label">Amount (RANC)</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" id="amount" name="amount" required>
                            </div>
                            <div class="col-md-4">
                                <label for="reason" class="form-label">Reason</label>
                                <input type="text" class="form-control" id="reason" name="reason" placeholder="e.g. Reward for participation" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-arrow-up-circle-fill"></i>
                                    Credit RANC
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Grid -->
    <div class="row">
        <div class="col-12">
            <h3 class="mb-3">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                User Wallets
            </h3>
        </div>
    </div>

    <div class="row">
        @foreach($users->chunk(4) as $userChunk)
            @foreach($userChunk as $user)
                @php
                    $balanceClass = $user->ranc > 0 ? 'balance-positive' : 'balance-zero';
                    $statusClass = $user->active ? 'status-active' : 'status-inactive';
                    $userName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                    if (empty($userName)) $userName = $user->username ?? $user->email;
                @endphp
                
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card user-card {{ $balanceClass }}" data-user-id="{{ $user->id }}" data-email="{{ $user->email }}" data-balance="{{ $user->ranc }}">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0 text-truncate" title="{{ $userName }}">
                                    <span class="{{ $statusClass }}"></span>
                                    {{ $userName }}
                                </h6>
                                <small class="text-muted">ID: {{ $user->id }}</small>
                            </div>

                            <!-- Email -->
                            <p class="card-text text-muted small mb-2">{{ $user->email }}</p>

                            <!-- Balance -->
                            <div class="balance-large text-center">
                                <span class="badge bg-primary fs-6">
                                    <i class="bi bi-coin"></i>
                                    {{ number_format($user->ranc, 0) }} RANC
                                </span>
                            </div>

                            <!-- Status -->
                            <div class="text-center mb-3">
                                @if($user->active)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Active Wallet
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Inactive Wallet
                                    </span>
                                @endif
                            </div>

                            <!-- Quick Credit Buttons -->
                            <div class="quick-buttons text-center">
                                <button class="btn btn-outline-success btn-sm quick-credit" 
                                        data-user-id="{{ $user->id }}" 
                                        data-email="{{ $user->email }}"
                                        data-amount="100"
                                        data-reason="Quick +100 RANC">
                                    <i class="bi bi-arrow-up"></i>
                                    +100 RANC
                                </button>
                                
                                <button class="btn btn-success btn-sm quick-credit" 
                                        data-user-id="{{ $user->id }}" 
                                        data-email="{{ $user->email }}"
                                        data-amount="500"
                                        data-reason="Quick +500 RANC">
                                    <i class="bi bi-arrow-up-circle"></i>
                                    +500 RANC
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick credit buttons
    document.querySelectorAll('.quick-credit').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const email = this.dataset.email;
            const amount = this.dataset.amount;
            const reason = this.dataset.reason;

            if (confirm(`Credit ${amount} RANC to ${email}?\n\nReason: ${reason}`)) {
                // Disable button to prevent double-click
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';

                fetch('{{ route("admin.credit.quick") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        amount: amount,
                        reason: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`✅ Success!\n\n${data.message}\n\nNew Balance: ${data.new_balance} RANC`);
                        location.reload(); // Refresh to show updated balance
                    } else {
                        alert('❌ Error: ' + (data.error || 'Unknown error'));
                        // Re-enable button
                        this.disabled = false;
                        this.innerHTML = `<i class="bi bi-arrow-up-circle"></i> +${amount} RANC`;
                    }
                })
                .catch(error => {
                    alert('❌ Network error: ' + error);
                    // Re-enable button
                    this.disabled = false;
                    this.innerHTML = `<i class="bi bi-arrow-up-circle"></i> +${amount} RANC`;
                });
            }
        });
    });

    // Auto-fill manual form when user card is clicked
    document.querySelectorAll('.user-card').forEach(card => {
        card.addEventListener('click', function() {
            const userId = this.dataset.userId;
            document.getElementById('user_select').value = userId;
            
            // Visual feedback
            document.querySelectorAll('.user-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
});
</script>

</body>
</html>
