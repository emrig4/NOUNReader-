<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .email-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .greeting {
            font-size: 18px;
            color: #4b5563;
            margin-bottom: 20px;
        }
        .subject-line {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin: 20px 0;
            font-size: 18px;
            font-weight: 600;
            text-align: center;
        }
        .message-content {
            background: #f8fafc;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #2563eb;
            margin: 20px 0;
            font-size: 16px;
            line-height: 1.8;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">📚 ReadProjectTopics</div>
            <div style="color: #6b7280; font-size: 14px;">Your Academic Resource Hub</div>
        </div>

        <div class="greeting">
            Dear {{ $user->name }},
        </div>

        <div class="subject-line">
            {{ $subject }}
        </div>

        <div class="message-content">
            {!! nl2br(e($messageContent)) !!}
        </div>

        <div class="divider"></div>

        <div style="text-align: center;">
            <a href="{{ url('/') }}" class="button">Visit Our Platform</a>
        </div>

        <div class="footer">
            <p>Best regards,<br>
            <strong>The ReadProjectTopics Team</strong></p>
            
            <p style="margin-top: 15px; font-size: 12px;">
                This is an automated message from ReadProjectTopics.com<br>
                If you have any questions, please contact our support team.
            </p>
        </div>
    </div>
</body>
</html>