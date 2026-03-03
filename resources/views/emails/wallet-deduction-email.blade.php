<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credits Deducted - Transaction Confirmation</title>
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
        .logo {
            max-height: 80px;
            margin-bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 12px;
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
        .deduction-box {
            background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
            border: 3px solid #DC2626;
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
        }
        .deduction-amount {
            font-size: 48px;
            font-weight: bold;
            color: #DC2626;
            margin: 20px 0;
        }
        .deduction-label {
            font-size: 18px;
            color: #DC2626;
            font-weight: 600;
        }
        .balance-box {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .balance-label {
            color: #92400E;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .balance-amount {
            color: #059669;
            font-size: 28px;
            font-weight: bold;
        }
        .wallet-type {
            background: linear-gradient(135deg, #EBF8FF 0%, #DBEAFE 100%);
            border: 2px solid #3B82F6;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .wallet-type-label {
            color: #1E40AF;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .wallet-type-value {
            color: #1E40AF;
            font-size: 20px;
            font-weight: bold;
        }
        .footer {
            background-color: #1F2937;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .transaction-details {
            background-color: #F9FAFB;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }
        .transaction-details h3 {
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
            <h1 class="title">💳 Credits Deducted</h1>
            <p class="subtitle">Transaction Confirmation</p>
        </div>
        
        <div class="content">
            <p class="greeting">Hello {{ $user->first_name ?? $user->name ?? 'User' }},</p>
            
            <p>This is to confirm that credits have been deducted from your <strong>Subscription Wallet</strong> for accessing premium content.</p>

            <!-- Wallet Type Clarification -->
            <div class="wallet-type">
                <div class="wallet-type-label">Wallet Type</div>
                <div class="wallet-type-value">📦 Subscription Wallet</div>
                <p style="margin: 10px 0 0 0; font-size: 14px; color: #1E40AF;">
                    Credits used to purchase resources and premium features
                </p>
            </div>

            <!-- Deduction Notification -->
            <div class="deduction-box">
                <h3 style="color: #DC2626; margin: 0 0 15px 0;">Credits Deducted</h3>
                
                <div class="deduction-amount">
                    - {{ number_format($amount, 2) }} Credits
                </div>
                
                <p class="deduction-label">Transaction Complete</p>
                
                <p>Your subscription wallet credits have been successfully deducted for accessing this resource.</p>
            </div>

            <!-- Balance Details -->
            <div class="transaction-details">
                <h3>📊 Transaction Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Amount Deducted:</span>
                    <span class="detail-value">- {{ number_format($amount, 2) }} Credits</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Wallet Type:</span>
                    <span class="detail-value">Subscription Wallet</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Balance Before:</span>
                    <span class="detail-value">{{ number_format($balance_before, 2) }} Credits</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Balance After:</span>
                    <span class="detail-value">{{ number_format($balance_after, 2) }} Credits</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Transaction Type:</span>
                    <span class="detail-value">Resource Access</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ now()->format('M j, Y \a\t g:i A') }}</span>
                </div>
            </div>

            <!-- Remaining Balance -->
            <div class="balance-box">
                <div class="balance-label">Remaining Subscription Wallet Balance</div>
                <div class="balance-amount">
                    {{ number_format($balance_after, 2) }} Credits
                </div>
            </div>

            <p style="color: #6B7280; font-size: 14px; margin-top: 20px;">
                <strong>Note:</strong> This deduction comes from your Subscription Wallet (used for purchases). 
                You may also have an Earnings Wallet where you receive credits from resource sales.
            </p>
     <!-- FOOTER -->
<div class="footer">
    <p>This is an automated welcome message.</p>

    <div class="footer-brand">
        <a href="https://projectandmaterials.com/"
           target="_blank"
           style="color:#3b82f6; text-decoration:none; font-weight:bold;">
            Projectandmaterials � Your Academic Resource Platform
        </a>
    </div>
</div>
</body>
</html>