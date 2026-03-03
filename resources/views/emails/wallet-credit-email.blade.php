<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credits Added - Wallet Updated</title>
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
        .logo {
            max-height: 80px;
            margin-bottom: 20px;
        }
        .logo img {
            max-height: 60px;
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
        .main-logo {
            text-align: center;
            margin: 30px 0;
        }
        .main-logo img {
            width: 160px;
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
        .credit-box {
            background: linear-gradient(135deg, #ECFDF5 0%, #F0FDF4 100%);
            border: 3px solid #059669;
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
        }
        .credit-amount {
            font-size: 48px;
            font-weight: bold;
            color: #059669;
            margin: 20px 0;
        }
        .credit-label {
            font-size: 18px;
            color: #059669;
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
            .main-logo img { width: 120px; }
            .credit-amount { font-size: 36px; }
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <div class="logo">
                <img src="https://projectandmaterials.com/themes/airdgereaders/images/logo.png" alt="ReadProjectTopics Logo">
            </div>
            <h1 class="title">💰 Credits Added!</h1>
            <p class="subtitle">Wallet Credit Notification</p>
        </div>

        <!-- MAIN LOGO (CENTERED) -->
        <div class="main-logo">
            <img src="https://projectandmaterials.com/themes/airdgereaders/images/rpt.png" alt="ReadProjectTopics">
        </div>

        <!-- CONTENT -->
        <div class="content">
            <p class="greeting">Hello {{ $user->first_name ?? $user->name ?? 'User' }},</p>
            
            <p>Great news! Your wallet has been credited with credits. Here's what happened:</p>

            <!-- Credit Notification -->
            <div class="credit-box">
                <h3 style="color: #059669; margin: 0 0 15px 0;">Credits Added to Wallet</h3>
                
                <div class="credit-amount">
                    + {{ number_format($amount, 2) }} Credits
                </div>
                
                <p class="credit-label">Transaction Type: {{ ucfirst($type) }}</p>
                
                <p>Your wallet has been successfully credited. You can now use these credits to access premium resources and features.</p>
            </div>

            <!-- Balance Details -->
            <div class="transaction-details">
                <h3>📊 Transaction Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Amount Added:</span>
                    <span class="detail-value">+ {{ number_format($amount, 2) }} Credits</span>
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
                    <span class="detail-value">{{ ucfirst($type) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ now()->format('M j, Y \a\t g:i A') }}</span>
                </div>
            </div>

            <!-- Current Balance -->
            <div class="balance-box">
                <div class="balance-label">Current Wallet Balance</div>
                <div class="balance-amount">
                    {{ number_format($balance_after, 2) }} Credits
                </div>
            </div>
        </div>
        
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
