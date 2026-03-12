<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
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
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .header.reminder { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .header.wish { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .header.announcement { background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); }
        .header.custom { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); }
        .header.test { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
        .title { font-size: 28px; font-weight: bold; margin: 0; color: white; }
        .subtitle { font-size: 16px; margin: 10px 0 0 0; opacity: 0.9; }
        .content { padding: 40px 30px; }
        .greeting { font-size: 18px; margin-bottom: 25px; color: #374151; }
        .message-content {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
            border-left: 4px solid;
        }
        .message-content.reminder { border-left-color: #f59e0b; }
        .message-content.wish { border-left-color: #10b981; }
        .message-content.announcement { border-left-color: #3b82f6; }
        .message-content.custom { border-left-color: #6b7280; }
        .message-content.test { border-left-color: #8b5cf6; }
        .personal-note {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .cta-section {
            background-color: #eff6ff;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }
        .cta-section h3 { color: #1e40af; margin-bottom: 15px; }
        .footer {
            background-color: #1f2937;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .signature {
            margin-top: 20px;
            font-style: italic;
            color: #6b7280;
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
        <!-- Header with dynamic color based on message type -->
        <div class="header {{ $type }}">
            @if($type === 'reminder')
                <h1 class="title">⏰ Reminder</h1>
                <p class="subtitle">Important message from ReadProjectTopics</p>
            @elseif($type === 'wish')
                <h1 class="title">🎉 Best Wishes</h1>
                <p class="subtitle">Celebrating with you!</p>
            @elseif($type === 'announcement')
                <h1 class="title">📢 Announcement</h1>
                <p class="subtitle">Important update from our team</p>
            @elseif($type === 'test')
                <h1 class="title">🧪 Test Message</h1>
                <p class="subtitle">Admin message system test</p>
            @else
                <h1 class="title">📧 Message</h1>
                <p class="subtitle">Personal message from ReadProjectTopics</p>
            @endif
        </div>
        
        <div class="content">
            <!-- Personal greeting -->
            @if($personalTouch)
                <div class="personal-note">
                    <strong>👋 Personal Message</strong><br>
                    This message was sent specifically for you!
                </div>
            @endif

            <p class="greeting">
                @if($personalTouch && isset($user->first_name))
                    Hello {{ $user->first_name }},
                @else
                    Hello,
                @endif
            </p>
            
            <!-- Message content -->
            <div class="message-content {{ $type }}">
                <h3 style="margin-top: 0; color: #374151;">{{ $subject }}</h3>
                <div style="white-space: pre-line; color: #374151;">
                    {{ $message }}
                </div>
            </div>

            <!-- Call to action for announcements -->
            @if($type === 'announcement')
                <div class="cta-section">
                    <h3>🚀 Stay Connected</h3>
                    <p>Keep an eye on your dashboard for more updates and exciting features!</p>
                </div>
            @endif

            <!-- Special note for wishes -->
            @if($type === 'wish')
                <div style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: 8px; padding: 20px; text-align: center; margin: 20px 0;">
                    <p style="margin: 0; color: #065f46; font-weight: 600;">
                        🎊 Thank you for being part of our community! 🎊
                    </p>
                </div>
            @endif

            <!-- Personal signature -->
            <div class="signature">
                <p style="margin: 0;">
                    Best regards,<br>
                    <strong>{{ $adminName }}</strong><br>
                    <span style="font-size: 14px;">ReadProjectTopics Admin Team</span>
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>ReadProjectTopics</strong></p>
            <p style="font-size: 14px; opacity: 0.8;">Your Academic Resource Platform</p>
            <hr style="border: 1px solid #374151; margin: 20px 0;">
            <p style="font-size: 12px; opacity: 0.7;">
                This is an administrative message. If you have any questions, please contact us at readprojecttopics@gmail.com
            </p>
        </div>
    </div>
</body>
</html>