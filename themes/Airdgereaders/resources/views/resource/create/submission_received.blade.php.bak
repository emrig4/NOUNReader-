@extends('layouts.public', ['title' => 'Submission Received'])

@push('css')
<style>
    .success-container {
        max-width: 700px;
        margin: 50px auto;
        text-align: center;
        padding: 50px 30px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }
    .success-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #10b981, #059669);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        animation: pulse 2s infinite;
    }
    .success-icon i {
        font-size: 50px;
        color: white;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 20px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .success-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 15px;
    }
    .success-message {
        font-size: 16px;
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 30px;
    }
    .submission-details {
        background: #f9fafb;
        border-radius: 12px;
        padding: 25px;
        text-align: left;
        margin-bottom: 30px;
    }
    .submission-details h4 {
        font-size: 16px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    .submission-details h4 i {
        margin-right: 10px;
        color: #3b82f6;
    }
    .detail-row {
        display: flex;
        padding: 10px 0;
        border-bottom: 1px solid #e5e7eb;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .detail-label {
        flex: 0 0 120px;
        font-weight: 500;
        color: #6b7280;
    }
    .detail-value {
        flex: 1;
        color: #1f2937;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        background: #fef3c7;
        color: #92400e;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    .status-badge i {
        margin-right: 6px;
    }
    .next-steps {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: left;
    }
    .next-steps h4 {
        font-size: 16px;
        font-weight: 600;
        color: #1e40af;
        margin-bottom: 12px;
    }
    .next-steps ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .next-steps li {
        display: flex;
        align-items: flex-start;
        padding: 8px 0;
        color: #1e3a8a;
    }
    .next-steps li i {
        margin-right: 10px;
        margin-top: 4px;
        color: #3b82f6;
    }
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        padding: 14px 28px;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }
    .btn-secondary-custom {
        display: inline-flex;
        align-items: center;
        padding: 14px 28px;
        background: #f3f4f6;
        color: #374151;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-secondary-custom:hover {
        background: #e5e7eb;
        color: #1f2937;
        text-decoration: none;
    }
    .btn-primary-custom i, .btn-secondary-custom i {
        margin-right: 8px;
    }
    .timeline {
        display: flex;
        justify-content: space-between;
        margin: 30px 0;
        position: relative;
    }
    .timeline::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 60px;
        right: 60px;
        height: 3px;
        background: #e5e7eb;
    }
    .timeline-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
    }
    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
    }
    .timeline-icon.completed {
        background: #10b981;
        color: white;
    }
    .timeline-icon.current {
        background: #fbbf24;
        color: white;
        animation: pulse 2s infinite;
    }
    .timeline-icon.pending {
        background: #e5e7eb;
        color: #9ca3af;
    }
    .timeline-label {
        font-size: 13px;
        color: #6b7280;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="ereaders-main-section ereaders-counterfull">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="success-container">
                    <!-- Success Icon -->
                    <div class="success-icon">
                        <i class="fas fa-check"></i>
                    </div>

                    <!-- Title & Message -->
                    <h1 class="success-title">Submission Received!</h1>
                    <p class="success-message">
                        Thank you for submitting your work. Your document has been received and is now 
                        pending review by our admin team. You will receive an email notification once 
                        the review is complete.
                    </p>

                    <!-- Timeline -->
                    <div class="timeline">
                        <div class="timeline-step">
                            <div class="timeline-icon completed">
                                <i class="fas fa-upload"></i>
                            </div>
                            <span class="timeline-label">Submitted</span>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-icon current">
                                <i class="fas fa-search"></i>
                            </div>
                            <span class="timeline-label">Under Review</span>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-icon pending">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <span class="timeline-label">Approved</span>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-icon pending">
                                <i class="fas fa-globe"></i>
                            </div>
                            <span class="timeline-label">Published</span>
                        </div>
                    </div>

                    <!-- Submission Details -->
                    @if(isset($resource))
                    <div class="submission-details">
                        <h4><i class="fas fa-file-alt"></i> Submission Details</h4>
                        <div class="detail-row">
                            <span class="detail-label">Title:</span>
                            <span class="detail-value">{{ $resource->title }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Submitted:</span>
                            <span class="detail-value">{{ $resource->submitted_at ? $resource->submitted_at->format('F d, Y \a\t g:i A') : now()->format('F d, Y \a\t g:i A') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span class="detail-value">
                                <span class="status-badge">
                                    <i class="fas fa-clock"></i> Pending Review
                                </span>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Reference:</span>
                            <span class="detail-value">#{{ $resource->id }}</span>
                        </div>
                    </div>
                    @endif

                    <!-- Next Steps -->
                    <div class="next-steps">
                        <h4><i class="fas fa-list-check"></i> What happens next?</h4>
                        <ul>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Our admin team will review your submission within 24-48 hours</span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span>You will receive an email notification about the review result</span>
                            </li>
                            <li>
                                <i class="fas fa-globe"></i>
                                <span>Once approved, your document will be published and visible to all users</span>
                            </li>
                            <li>
                                <i class="fas fa-edit"></i>
                                <span>If changes are needed, we will provide specific feedback</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="{{ route('resources.submit') }}" class="btn-primary-custom">
                            <i class="fas fa-plus"></i> Submit Another
                        </a>
                        <a href="{{ route('account.index') }}" class="btn-secondary-custom">
                            <i class="fas fa-user"></i> My Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

