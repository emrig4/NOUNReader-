<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Review Update - Needs Revision</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f7fa;
        }
        .container {
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .title {
            font-size: 32px;
            font-weight: bold;
            margin: 0;
            color: white;
        }
        .subtitle {
            font-size: 16px;
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #374151;
        }
        .update-box {
            background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
            border: 3px solid #DC2626;
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
        }
        .update-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .document-details {
            background-color: #F9FAFB;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }
        .document-details h3 {
            color: #374151;
            margin-bottom: 20px;
            text-align: center;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 12px 0;
            color: #374151;
        }
        .detail-label {
            font-weight: 600;
            color: #6B7280;
        }
        .detail-value {
            color: #374151;
        }
        .status-badge {
            display: inline-block;
            background-color: #DC2626;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin: 10px 0;
        }
        .feedback-section {
            background-color: #FEF3C7;
            border: 2px solid #F59E0B;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }
        .feedback-section h3 {
            color: #92400E;
            margin-bottom: 15px;
        }
        .feedback-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #F59E0B;
            margin: 15px 0;
            font-style: italic;
            color: #374151;
        }
        .next-steps {
            background-color: #EFF6FF;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }
        .next-steps h3 {
            color: #1E40AF;
            margin-bottom: 15px;
        }
        .next-steps ol {
            color: #374151;
            padding-left: 20px;
        }
        .next-steps li {
            margin: 8px 0;
        }
        .footer {
            background-color: #1F2937;
            color: white;
            padding: 30px;
            text-align: center;
        }
        @media (max-width: 600px) {
            body { padding: 10px; }
            .content { padding: 20px; }
            .header { padding: 30px 20px; }
            .title { font-size: 24px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">📋 Document Review Update</h1>
            <p class="subtitle">Action Required - Needs Revision</p>
        </div>
        
        <div class="content">
            <p class="greeting">Hello {{ $resource->user->name ?? 'User' }},</p>
            
            <p>Thank you for submitting your document for review. After careful consideration, our admin team was not able to approve your submission at this time.</p>

            <!-- Update Box -->
            <div class="update-box">
                <div class="update-icon">📝</div>
                <h3 style="color: #DC2626; margin: 0 0 15px 0;">Document Needs Revision</h3>
                <p style="margin: 0;">Please review the feedback below and make necessary improvements.</p>
            </div>

            <!-- Document Details -->
            <div class="document-details">
                <h3>📄 Document Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Title:</span>
                    <span class="detail-value">{{ $resource->title }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Type:</span>
                    <span class="detail-value">{{ ucfirst($resource->type ?? 'Document') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Field:</span>
                    <span class="detail-value">{{ ucfirst($resource->field ?? 'General') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Submitted on:</span>
                    <span class="detail-value">{{ $resource->submitted_at ? $resource->submitted_at->format('M j, Y \a\t g:i A') : 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="status-badge">⚠️ Needs Revision</span>
                    </span>
                </div>
            </div>

            <!-- Admin Feedback -->
            <div class="feedback-section">
                <h3>💬 Admin Feedback</h3>
                <p style="margin: 0 0 15px 0; color: #92400E;">
                    <strong>Reason for Decision:</strong>
                </p>
                <div class="feedback-content">
                    @if($reason)
                        {{ $reason }}
                    @else
                        Please contact support for more detailed information about the review feedback.
                    @endif
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>📋 What Can You Do?</h3>
                <ol>
                    <li><strong>Review Feedback:</strong> Carefully read through the admin feedback provided above</li>
                    <li><strong>Make Improvements:</strong> Address the concerns raised in your document</li>
                    <li><strong>Resubmit:</strong> Once you've made the necessary changes, submit your document again for review</li>
                    <li><strong>Get Support:</strong> If you need clarification on the feedback, contact our support team</li>
                </ol>
            </div>

            <p style="margin-top: 30px; color: #6B7280;">
                We appreciate your understanding and look forward to reviewing your improved submission. Our review process ensures high-quality content for all users.
            </p>
        </div>
        
        <div class="footer">
            <p><strong>readprojecttopics Team</strong></p>
            <p style="font-size: 14px; opacity: 0.8;">Your Academic Resource Platform</p>
            <hr style="border: 1px solid #374151; margin: 20px 0;">
            <p style="font-size: 12px; opacity: 0.7;">This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>