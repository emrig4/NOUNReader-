<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Hire a Writer Inquiry</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .email-container {
            background: #f9f9f9;
            border-radius: 10px;
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #37a5d8 0%, #2c8cb8 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px;
            background: #fff;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .info-table tr {
            border-bottom: 1px solid #eee;
        }
        .info-table th {
            text-align: left;
            padding: 12px 8px;
            font-weight: 600;
            color: #37a5d8;
            width: 40%;
        }
        .info-table td {
            padding: 12px 8px;
            color: #555;
        }
        .message-box {
            background: #f5f5f5;
            border-left: 4px solid #37a5d8;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }
        .message-box h3 {
            margin-top: 0;
            color: #333;
            font-size: 16px;
        }
        .message-box p {
            margin: 0;
            white-space: pre-wrap;
        }
        .email-footer {
            background: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-hire { background: #37a5d8; color: white; }
        .badge-budget { background: #28a745; color: white; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📝 New Hire a Writer Inquiry</h1>
            <p>You have received a new project request</p>
        </div>
        
        <div class="email-body">
            <table class="info-table">
                <tr>
                    <th>Client Name</th>
                    <td>{{ $data['name'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td><a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td>{{ $data['phone'] ?? 'Not provided' }}</td>
                </tr>
                <tr>
                    <th>Inquiry Type</th>
                    <td>
                        @if(isset($data['subject']))
                            @switch($data['subject'])
                                @case('hire_writer')
                                    <span class="badge badge-hire">Hire a Writer</span>
                                    @break
                                @case('buy_credit')
                                    <span class="badge" style="background: #ffc107; color: #333;">Buy Credit</span>
                                    @break
                                @case('inquiry_partnership')
                                    <span class="badge" style="background: #6f42c1; color: white;">Partnership</span>
                                    @break
                                @case('data_analysis')
                                    <span class="badge" style="background: #17a2b8; color: white;">Data Analysis</span>
                                    @break
                                @default
                                    {{ $data['subject'] }}
                            @endswitch
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                
                @if(isset($data['service_type']) && $data['service_type'])
                <tr>
                    <th>Service Type</th>
                    <td>{{ $data['service_type'] }}</td>
                </tr>
                @endif
                
                @if(isset($data['budget']) && $data['budget'])
                <tr>
                    <th>Project Budget</th>
                    <td><span class="badge badge-budget">{{ $data['budget'] }}</span></td>
                </tr>
                @endif
                
                @if(isset($data['deadline']) && $data['deadline'])
                <tr>
                    <th>Deadline</th>
                    <td>{{ $data['deadline'] }}</td>
                </tr>
                @endif
                
                <tr>
                    <th>Submission Date</th>
                    <td>{{ $data['created_at'] ?? now()->format('Y-m-d H:i:s') }}</td>
                </tr>
                
                <tr>
                    <th>IP Address</th>
                    <td>{{ $data['ip_address'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            @if(isset($data['message']) && $data['message'])
            <div class="message-box">
                <h3>Project Details / Message:</h3>
                <p>{{ $data['message'] }}</p>
            </div>
            @endif
        </div>
        
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} ReadProjectTopics.com - All Rights Reserved</p>
            <p>This is an automated notification from your website contact form.</p>
        </div>
    </div>
</body>
</html>
