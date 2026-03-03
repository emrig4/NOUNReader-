<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Credit Settings - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
        }
        .btn-success {
            background: linear-gradient(45deg, #28a745, #1e7e34);
            border: none;
        }
        .stats-card {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h2><i class="bi bi-gift"></i> Auto Credit Settings</h2>
                <p class="text-muted">Configure automatic subscription credits for first-time users</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('admin.auto-credit.statistics') }}" class="btn btn-outline-info">
                    <i class="bi bi-bar-chart"></i> View Statistics
                </a>
                <a href="{{ route('admin.credit.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Credit Management
                </a>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Main Settings Card -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-gear"></i> Auto Credit Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.auto-credit.update') }}">
                            @csrf
                            
                            <!-- Enable/Disable Auto Credit -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="enabled" value="1" 
                                           {{ $settings['enabled'] ? 'checked' : '' }} id="autoCreditSwitch">
                                    <label class="form-check-label" for="autoCreditSwitch">
                                        <h6>Enable Auto Credit for First-Time Users</h6>
                                        <small class="text-muted">Automatically grant credits to newly registered users</small>
                                    </label>
                                </div>
                            </div>

                            <!-- Credit Amount -->
                            <div class="mb-4">
                                <label for="amount" class="form-label">
                                    <h6>Credit Amount (RANC)</h6>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                                    <input type="number" class="form-control" name="amount" id="amount" 
                                           value="{{ $settings['amount'] }}" step="0.01" min="0" required>
                                    <span class="input-group-text">RANC</span>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i> Amount to automatically credit to first-time users
                                </div>
                            </div>

                            <!-- Welcome Message -->
                            <div class="mb-4">
                                <label for="message" class="form-label">
                                    <h6>Welcome Message Template</h6>
                                </label>
                                <textarea class="form-control" name="message" id="message" rows="3" 
                                          placeholder="Welcome! You've received {amount} RANC as a first-time user bonus!" required>{{ $settings['message'] }}</textarea>
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i> Use {amount} as placeholder for the credit amount
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-secondary me-md-2" onclick="resetForm()">
                                    <i class="bi bi-arrow-clockwise"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Settings Summary Card -->
            <div class="col-md-4">
                <div class="card stats-card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-list-ul"></i> Current Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-8">
                                <small class="text-muted">Auto Credit Status</small>
                            </div>
                            <div class="col-4 text-end">
                                @if($settings['enabled'])
                                    <span class="badge bg-success"><i class="bi bi-check"></i> Enabled</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x"></i> Disabled</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-8">
                                <small class="text-muted">Credit Amount</small>
                            </div>
                            <div class="col-4 text-end">
                                <strong>{{ number_format($settings['amount'], 2) }} RANC</strong>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <small class="text-muted">How it works:</small>
                                <ul class="small mt-2">
                                    <li>New users get auto credit on first registration</li>
                                    <li>Works alongside manual credit assignment</li>
                                    <li>Doesn't interfere with existing credit purchases</li>
                                    <li>Admin can adjust amount anytime</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.auto-credit.statistics') }}" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-bar-chart"></i> View Statistics
                            </a>
                            <a href="{{ route('admin.credit.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-hand-thumbs-up"></i> Manual Credit Assignment
                            </a>
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="previewCredit()">
                                <i class="bi bi-eye"></i> Preview Credit Amount
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function resetForm() {
            if (confirm('Are you sure you want to reset the form to default values?')) {
                document.getElementById('autoCreditSwitch').checked = true;
                document.getElementById('amount').value = '100.00';
                document.getElementById('message').value = 'Welcome! You\'ve received {amount} RANC as a first-time user bonus!';
            }
        }

        function previewCredit() {
            const amount = document.getElementById('amount').value;
            const message = document.getElementById('message').value;
            const previewMessage = message.replace('{amount}', amount + ' RANC');
            
            alert('Preview Message:\n\n' + previewMessage);
        }

        // Auto-save draft (optional enhancement)
        let draftTimer;
        document.addEventListener('input', function() {
            clearTimeout(draftTimer);
            draftTimer = setTimeout(function() {
                // Save draft to localStorage (optional)
                const formData = {
                    enabled: document.getElementById('autoCreditSwitch').checked,
                    amount: document.getElementById('amount').value,
                    message: document.getElementById('message').value
                };
                localStorage.setItem('autoCreditDraft', JSON.stringify(formData));
            }, 1000);
        });

        // Load draft on page load (optional enhancement)
        window.addEventListener('load', function() {
            const draft = localStorage.getItem('autoCreditDraft');
            if (draft && confirm('Restore unsaved changes from draft?')) {
                const formData = JSON.parse(draft);
                document.getElementById('autoCreditSwitch').checked = formData.enabled;
                document.getElementById('amount').value = formData.amount;
                document.getElementById('message').value = formData.message;
            }
        });
    </script>
</body>
</html>

