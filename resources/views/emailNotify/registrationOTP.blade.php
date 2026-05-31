<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration OTP</title>
</head>
<body>
<p>Thank you for registering with <strong>{{ $store_name }}</strong>! To complete your registration, please use the
    One-Time Password (OTP) below:</p>
<h3 style="color: #2d89ef;">OTP: {{ $otp }}</h3>
<p>If you need assistance or have any questions, please feel free to contact our support team at
    <strong>{{ $help_number }}</strong>.</p>
<p>If you didn’t request this, you can safely ignore this email.</p>
<p>Best regards,</p>
<p><strong>{{ $store_name }}</strong></p>
<p><a href="https://{{ $app_url }}" target="_blank">{{ $app_url }}</a></p>
</body>
</html>
