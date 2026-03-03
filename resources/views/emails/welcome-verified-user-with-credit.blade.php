<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Projectandmaterials!</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
        }

        .header {
            background: #1e3a8a;
            padding: 40px 20px;
            text-align: center;
            color: #fff;
        }

        .logo img {
            width: 140px;
            margin: 0 auto;
            display: block;
        }

        .title {
            font-size: 26px;
            font-weight: bold;
            margin-top: 15px;
        }

        .subtitle {
            margin-top: 5px;
            font-size: 14px;
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
            padding: 30px;
            text-align: center;
        }

        .celebration {
            background: #10b981;
            color: #ffffff;
            padding: 18px;
            font-size: 22px;
            border-radius: 10px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .credit-box {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
            text-align: center;
        }

        .credit-amount {
            font-size: 40px;
            font-weight: bold;
            color: #047857;
            margin: 10px 0;
        }

        .balance-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
            text-align: center;
        }

        .balance-amount {
            font-size: 32px;
            font-weight: bold;
            color: #b45309;
        }

        .features {
            text-align: left;
            background: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            border: 1px solid #e5e7eb;
        }

        .feature-item {
            margin: 10px 0;
            color: #334155;
            font-size: 15px;
            display: flex;
            align-items: center;
        }

        .feature-item span {
            margin-right: 12px;
            font-size: 18px;
        }

        .footer {
            background: #0f172a;
            padding: 25px;
            text-align: center;
            color: #ffffff;
            font-size: 14px;
        }

        .footer-brand {
            margin-top: 8px;
            font-weight: bold;
            color: #3b82f6;
        }
    </style>
</head>

<body>
<div class="container">

    <!-- HEADER WITH TOP LOGO -->
    <div class="header">
        <div class="logo">
            <img src="https://projectandmaterials.com/themes/airdgereaders/images/projectandmaterials.logo.png" alt="projectandmaterials Logo">
        </div>
        <div class="title">Welcome to Readprojecttopics!</div>
        <div class="subtitle">Email Verified • Account Activated</div>
    </div>

    <!-- MAIN CENTER LOGO -->
    <div class="main-logo">
        <img src="https://projectandmaterials.com/themes/airdgereaders/images/Projectandmaterials.webp" alt="ReadProjectTopics">
    </div>

    <!-- CONTENT -->
    <div class="content">

        <p style="font-size:18px;font-weight:600;color:#1e293b;">
            Hello {{ $user->first_name ?? $user->name ?? 'User' }},
        </p>

        <div class="celebration">
            🎉 Your Email Has Been Successfully Verified!
        </div>

        <p>We’re excited to welcome you to the Readprojecttopics community — your home for high-quality academic resources.</p>

        <!-- Credit Notification -->
        <div class="credit-box">
            <div style="color:#047857;font-weight:bold;">Welcome Bonus Credits Added!</div>

            <div class="credit-amount">
                + {{ number_format($autoCreditAmount ?? 190.00, 2) }}
            </div>

            <p style="color:#065f46;">These credits have been added to your wallet.</p>
        </div>

        <!-- Current Balance -->
        <div class="balance-box">
            <div style="font-weight:bold;color:#b45309;">Current Wallet Balance</div>
            <div class="balance-amount">
                {{ number_format($currentBalance ?? ($autoCreditAmount ?? 190.00), 2) }} Credits
            </div>
        </div>

        <!-- Features -->
        <div class="features">
            <h3 style="text-align:center;color:#1e293b;">🚀 What You Can Do Now</h3>
    <li>
                    Visit page:
                    <a href="https://projectandmaterials.com/"
                       target="_blank"
                       style="color:#3b82f6; text-decoration:none; font-weight:bold;">
                        https://projectandmaterials.com
                    </a>
                </li>
            <div class="feature-item"><span>📚</span>Access thousands of Project materials</div>
            <div class="feature-item"><span>💳</span>Use your digital wallet to unlock projects</div>
            <div class="feature-item"><span>🔍</span>Search with advanced filtering tools</div>
            <div class="feature-item"><span>📱</span>Enjoy a mobile-friendly experience</div>
            <div class="feature-item"><span>👥</span>Join discussions and leave reviews</div>
        </div>

    </div>

    <!-- FOOTER -->
<!-- FOOTER -->
<div class="footer">
    <p>This is an automated welcome message.</p>

    <div class="footer-brand">
        <a href="https://projectandmaterials.com/"
           target="_blank"
           style="color:#3b82f6; text-decoration:none; font-weight:bold;">
            Projectandmaterials – Your Academic Resource Platform
        </a>
    </div>
</div>
</body>
</html>
