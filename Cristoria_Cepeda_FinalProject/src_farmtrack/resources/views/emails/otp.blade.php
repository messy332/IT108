<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <p>Hello,</p>
    
    <p>You requested to reset your password. Your OTP code is:</p>
    
    <p style="font-size: 24px; font-weight: bold; color: #22c55e; letter-spacing: 3px; text-align: center; padding: 20px; background-color: #f0f0f0; border-radius: 5px;">
        {{ $otp }}
    </p>
    
    <p>This code is valid for 10 minutes.</p>
    
    <p>If you didn't request this, please ignore this email.</p>
    
    <p>Thank you,<br>FarmTrack</p>
</body>
</html>
