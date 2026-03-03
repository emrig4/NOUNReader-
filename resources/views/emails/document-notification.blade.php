<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Document Notification - Readprojecttopics' }}</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .container {
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .header {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 50%, #6d28d9 100%);
            padding: 50px 40px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 7s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translate(-50%, -50%) rotate(0deg); }
            50% { transform: translate(-50%, -50%) rotate(180deg); }
        }
       .logo {
    width: 110px;       /* or adjust as needed */
    height: 110px;      /* or adjust as needed */
    margin: 0 auto 25px auto;
    border-radius: 0;   /* remove white rounded background */
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent; /* remove white background */
    box-shadow: none;         /* remove shadow if not needed */
    position: relative;
    overflow: hidden;
    z-index: 2;
}

.logo img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* use 'cover' if you want it fully fill the box */
}

        }
        .brand-title {
            font-size: 34px;
            font-weight: 800;
            margin: 0;
            text-transform: none;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .subtitle {
            font-size: 18px;
            margin: 15px 0 0 0;
            opacity: 0.95;
            font-weight: 500;
            position: relative;
            z-index: 2;
        }
        .main-content {
            padding: 50px 40px;
        }
        .title {
            font-size: 32px;
            font-weight: 800;
            margin: 0 0 30px 0;
            color: #1e293b;
            text-align: center;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .content {
            margin-bottom: 35px;
        }
        .message {
            font-size: 18px;
            line-height: 1.7;
            margin-bottom: 25px;
            color: #374151;
            font-weight: 500;
        }
        .document-info {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            padding: 30px;
            margin: 30px 0;
            position: relative;
            overflow: hidden;
        }
        .document-info h3 {
            margin: 0 0 25px 0;
            color: #1e293b;
            font-size: 22px;
            font-weight: 700;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 12px;
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .info-label {
            font-weight: 700;
            color: #64748b;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            color: #1e293b;
            font-weight: 600;
            font-size: 16px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .status-pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 2px solid #f59e0b;
        }
        .status-approved {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 2px solid #10b981;
        }
        .status-rejected {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 2px solid #ef4444;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            padding: 18px 35px;
            text-decoration: none;
            border-radius: 15px;
            font-weight: 700;
            margin: 25px 0;
            text-align: center;
            font-size: 16px;
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.4);
        }
        .product-showcase {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 3px solid #0ea5e9;
            border-radius: 24px;
            padding: 40px;
            margin: 40px 0;
            text-align: center;
        }
        .product-showcase img {
            width: 120px;
            height: 120px;
            object-fit: contain;
            margin-bottom: 20px;
        }
        .footer {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            margin-top: 50px;
            padding: 40px;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- HEADER WITH LOGO -->
        <div class="header">
            <div class="logo">
                <img src="https://projectandmaterials.com/themes/airdgereaders/images/logo.png" alt="ReadProjectTopics Logo">
            </div>
            <h1 class="brand-title">Readprojecttopics</h1>
            <p class="subtitle">Document Submission & Review System</p>
        </div>

        <div class="main-content">
            <h2 class="title">{{ $title ?? 'Document Notification' }}</h2>

            <div class="content">
                <div class="message">
                    Hello {{ $user->first_name ?? $user->name ?? 'User' }},
                </div>
                <div class="message">
                    {{ $message ?? 'You have received a document notification.' }}
                </div>
            </div>

            <!-- PRODUCT SHOWCASE WITH IMAGE -->
            <div class="product-showcase">
                <img src="https://projectandmaterials.com/themes/airdgereaders/images/rpt.png" alt="Product Logo">
                <h3 class="product-title">Readprojecttopics</h3>
                <p class="product-description">Professional document submission and review platform</p>
            </div>

            @if(isset($document))
            <div class="document-info">
                <h3>Document Details</h3>
                <div class="info-row">
                    <span class="info-label">Filename:</span>
                    <span class="info-value">{{ $document->original_filename ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ $document->status ?? 'pending' }}">
                            {{ $document->status_name ?? ucfirst($document->status ?? 'pending') }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Submitted:</span>
                    <span class="info-value">
                        @if(isset($document->created_at))
                            {{ $document->created_at->format('M d, Y \a\t g:i A') }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                @if(isset($document->rejection_reason) && $document->rejection_reason)
                <div class="info-row">
                    <span class="info-label">Rejection Reason:</span>
                    <span class="info-value">{{ $document->rejection_reason }}</span>
                </div>
                @endif
            </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ $actionUrl ?? '#' }}" class="action-button">
                    @if($type === 'submitted')
                        View Dashboard
                    @elseif($type === 'approved')
                        View Approved Document
                    @elseif($type === 'rejected')
                        Upload New Document
                    @elseif($type === 'admin_submitted')
                        Review Document
                    @else
                        Go to Dashboard
                    @endif
                </a>
            </div>

            <div class="message">
                @if($type === 'admin_submitted')
                    Please review this document and approve or reject it as appropriate.
                @elseif($type === 'approved')
                    You can now access your approved document from your dashboard.
                @elseif($type === 'rejected')
                    You can upload a new version of your document for review.
                @else
                    Thank you for using our document submission system.
                @endif
            </div>
        </div>

        <div class="footer">
            <p class="footer-text">This is an automated notification. Please do not reply to this email.</p>
            <p class="footer-text">If you have any questions, please contact our support team.</p>
            <p class="footer-text footer-brand">Readprojecttopics - Professional Document Platform</p>
        </div>

    </div>
</body>
</html>
