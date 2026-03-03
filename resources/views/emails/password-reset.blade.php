<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - Readprojecttopics</title>

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
            background: #dc2626;
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

        .action-button {
            display: inline-block;
            background: #dc2626;
            color: #ffffff;
            padding: 15px 25px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            font-size: 16px;
            margin: 20px 0;
        }

        .note-box {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin: 25px 0;
            text-align: left;
            border-radius: 8px;
        }

        .footer {
            background: #0f172a;
            padding: 25px;
            text-align: center;
            color: #fff;
            font-size: 14px;
        }

        .footer-brand {
            margin-top: 8px;
            font-weight: bold;
            color: #ef4444;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- HEADER WITH TOP LOGO -->
    <div class="header">
        <div class="logo">
            <img src="https://projectandmaterials.com/themes/airdgereaders/images/logo.png" alt="Readprojecttopics Logo">
        </div>
        <div class="title">Password Reset</div>
        <div class="subtitle">Secure Account Recovery</div>
    </div>

    <!-- SECOND MAIN LOGO -->
    <div class="main-logo">
        <img src="https://projectandmaterials.com/themes/airdgereaders/images/rpt.png" alt="ReadProjectTopics">
    </div>

    <!-- CONTENT -->
    <div class="content">

        <p style="font-size:18px;font-weight:600;color:#1e293b;">
            Hello!
        </p>

        <p>You are receiving this email because you requested a password reset for your account.</p>

        <a href="{{ $actionUrl }}" class="action-button">Reset Password</a>

        <div class="note-box">
            <strong>Important:</strong>
            <p>This reset link will expire in 60 minutes.</p>
        </div>

        <div class="note-box" style="border-left-color:#f59e0b;background:#fffbeb;">
            <strong style="color:#b45309;">If you did not request this:</strong>
            <p style="color:#92400e;">Please ignore this email. Your account remains secure.</p>
        </div>

        <p style="margin-top:25px;font-size:14px;color:#374151;">
            If you cannot click the button, copy this link:<br><br>
            <span style="font-family: monospace; word-break: break-all; color:#1e3a8a;">
                {{ $actionUrl }}
            </span>
        </p>

    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>This is an automated security message from ReadProjectTopics.</p>
        <div class="footer-brand">Readprojecttopics - Your Academic Resource Platform</div>
    </div>

</div>

</body>
</html>
