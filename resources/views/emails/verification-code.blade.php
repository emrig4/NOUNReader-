<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - projectandmaterials</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f4f6;
            padding: 0;
            margin: 0;
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

        .verification-code {
            font-size: 40px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #1d4ed8;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
        }

        .note-box {
            background: #f8fafc;
            border-left: 4px solid #1d4ed8;
            padding: 15px;
            margin: 25px 0;
            text-align: left;
            border-radius: 8px;
        }

        .note-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #1e3a8a;
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

        @media (max-width: 600px) {
            .verification-code {
                font-size: 28px;
                letter-spacing: 4px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        <div class="logo">
            <img src="https://projectandmaterials.com/themes/airdgereaders/images/projectandmaterials.logo.png" alt="projectandmaterials Logo">
        </div>
        <div class="title">Email Verification</div>
        <div class="subtitle">Secure Code for Account Activation</div>
    </div>

    <!-- MAIN LOGO -->
    <div class="main-logo">
        <img src="https://projectandmaterials.com/themes/airdgereaders/images/Projectandmaterials.webp" alt="Projectandmaterials">
    </div>

    <!-- CONTENT -->
    <div class="content">

        <p style="font-size:18px;font-weight:600;color:#1e293b;">
            Hello {{ $user->first_name ?? $user->name ?? 'User' }},
        </p>

        <p>Please use the verification code below to activate your account:</p>

        <div class="verification-code">
            {{ $verificationCode }}
        </div>

        <div class="note-box" style="border-left-color:#f59e0b;">
            <div class="note-title" style="color:#b45309;">Important Notes</div>
            <ul style="margin:0;padding-left:20px;color:#92400e;">
                <li>
                    If this email arrives late, please visit the login page:
                    <a href="https://projectandmaterials.com/login"
                       target="_blank"
                       style="color:#3b82f6; text-decoration:none; font-weight:bold;">
                        https://projectandmaterials.com/login
                    </a>
                </li>
                <li>Enter your registered email address and use <strong>12345678</strong> as the password</li>
                <li>You will be redirected to the verification page where you can enter the code and set a permanent password</li>
            </ul>
        </div>

        <div class="note-box">
            <div class="note-title">Security Information</div>
            <ul style="margin:0;padding-left:20px;color:#334155;">
                <li>This code is valid for {{ $expiresAt->diffInMinutes() }} minutes</li>
                <li>Do not share this code with anyone</li>
                <li>A maximum of 5 verification attempts is allowed</li>
            </ul>
        </div>

        <ul style="margin:0;padding-left:20px;color:#475569;text-align:left;">
            <li>We are available to assist you with your project writing from Chapter 1 to Chapter 5</li>
            <li>Access our database to read and download project materials</li>
        </ul>

    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    <p>This is an automated message. Please do not reply.</p>

    <div class="footer-brand">
        <a href="https://projectandmaterials.com/"
           target="_blank"
           style="color:#3b82f6; text-decoration:none; font-weight:bold;">
            Readprojecttopics – Your Academic Resource Platform
        </a>
    </div>
</div>

</body>
</html>
