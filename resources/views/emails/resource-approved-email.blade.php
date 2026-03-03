<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Approved - Congratulations!</title>
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
            background: linear-gradient(135deg, #059669 0%, #10B981 100%);
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
        .congratulations-box {
            background: linear-gradient(135deg, #ECFDF5 0%, #F0FDF4 100%);
            border: 3px solid #059669;
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
        }
        .congratulations-icon {
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
            background-color: #059669;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin: 10px 0;
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
        .next-steps ul {
            color: #374151;
            padding-left: 20px;
        }
        .next-steps li {
            margin: 8px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #059669 0%, #10B981 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
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
            <h1 class="title">🎉 Congratulations!</h1>
            <p class="subtitle">Your Document Has Been Approved</p>
        </div>
        
        <div class="content">
            <p class="greeting">Hello {{ $resource->user->name ?? 'User' }},</p>
            
            <p>Great news! Your document has been reviewed and approved by our admin team. It is now published and visible to all users on the platform.</p>

            <!-- Congratulations Box -->
            <div class="congratulations-box">
                <div class="congratulations-icon">🎉</div>
                <h3 style="color: #059669; margin: 0 0 15px 0;">Document Successfully Approved!</h3>
                <p style="margin: 0;">Your contribution has been reviewed and approved for publication.</p>
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
                    <span class="detail-label">Approved on:</span>
                    <span class="detail-value">{{ $resource->approved_at ? $resource->approved_at->format('M j, Y \a\t g:i A') : now()->format('M j, Y \a\t g:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="status-badge">✅ Published</span>
                    </span>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>🚀 What's Next?</h3>
                <ul>
                    <li><strong>Document Live:</strong> Your document is now available for readers to view, download, or purchase</li>
                    <li><strong>Track Performance:</strong> Monitor views, downloads, and earnings from your dashboard</li>
                    <li><strong>Promote:</strong> Share your document on social media to reach more readers</li>
                    <li><strong>Engage:</strong> Respond to reader reviews and feedback</li>
                </ul>
            </div>

            <p style="margin-top: 30px; color: #6B7280;">
                Thank you for contributing to our platform! Your expertise helps fellow students and researchers access quality academic resources.
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