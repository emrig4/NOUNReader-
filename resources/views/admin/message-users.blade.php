<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message to Users - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f7fa; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .card-header { background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); border-radius: 12px 12px 0 0 !important; padding: 20px 25px; }
        .form-label { font-weight: 600; color: #374151; margin-bottom: 8px; }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25); }
        .btn-send { background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); border: none; padding: 12px 30px; font-weight: 600; }
        .btn-send:hover { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); }
        .user-count { background: #dbeafe; color: #1e40af; padding: 8px 16px; border-radius: 20px; font-weight: 600; }
        .info-box { background: #f0f9ff; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        textarea { resize: vertical; min-height: 200px; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="mb-3">
                    <a href="{{ url('/dashboard') }}" class="text-decoration-none">← Back to Dashboard</a>
                </div>

                <div class="card">
                    <div class="card-header text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">📧 Send Message to Verified Users</h4>
                                <small class="text-white-50">Compose and send bulk messages to all verified users</small>
                            </div>
                            <div class="user-count">{{ $verifiedUsersCount }} Users</div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>✓ Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>⚠ Warning!</strong> {{ session('warning') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>✕ Error!</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="info-box">
                            <h6 class="mb-2">📋 Instructions:</h6>
                            <ul class="mb-0 small">
                                <li>This message will be sent to ALL verified users (users who have confirmed their email)</li>
                                <li>Currently there are <strong>{{ $verifiedUsersCount }}</strong> verified users in the system</li>
                                <li>The message will appear as a personalized email from the admin team</li>
                                <li>Please keep the message professional and relevant</li>
                            </ul>
                        </div>

                        <form method="POST" action="{{ route('admin.message-users.send') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="subject" class="form-label">📝 Email Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" placeholder="Enter the email subject..." value="{{ old('subject') }}" required maxlength="255">
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maximum 255 characters</small>
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label">💬 Message <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="8" placeholder="Enter your message to all verified users..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimum 10 characters. This message will be sent to all verified users.</small>
                            </div>

                            <div class="alert alert-secondary">
                                <h6 class="alert-heading">📤 Email Preview:</h6>
                                <p class="mb-0">The email will be sent with the following format:</p>
                                <ul class="mb-0 mt-2">
                                    <li><strong>From:</strong> ReadProjectTopics Admin Team</li>
                                    <li><strong>Reply-to:</strong> noreply@projectandmaterials.com</li>
                                    <li><strong>Recipients:</strong> All {{ $verifiedUsersCount }} verified users</li>
                                    <li><strong>Type:</strong> Custom message (blue theme)</li>
                                </ul>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-send text-white" onclick="return confirm('Are you sure you want to send this message to all {{ $verifiedUsersCount }} verified users?')">
                                    🚀 Send Message to All Users
                                </button>
                                <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h6 class="card-title">💡 Tips for Effective Admin Messages:</h6>
                        <ul class="mb-0">
                            <li>Keep the subject line clear and concise</li>
                            <li>Make the message personalized and relevant to your users</li>
                            <li>Include a clear call-to-action if needed</li>
                            <li>Add your name at the end for a personal touch</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>