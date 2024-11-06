<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #374151;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .content {
            padding: 24px 0;
        }
        .verification-code {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 4px;
            color: #1d4ed8;
            background-color: #eff6ff;
            padding: 16px;
            margin: 24px 0;
            border-radius: 6px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #111827; margin: 0;">Verify Your Email Address</h1>
        </div>
        
        <div class="content">
            <p>Hello!</p>
            
            <p>Please use the verification code below to verify your email address:</p>
            
            <div class="verification-code">
                {{ $verificationCode }}
            </div>
            
            <p>This verification code will expire in 60 minutes.</p>
            
            <p>If you didn't request this verification code, you can safely ignore this email.</p>
        </div>
        
        <div class="footer">
            <p>Thanks,<br>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>