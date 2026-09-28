<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Verify your email</title>
</head>
<body style="font-family: 'DM Sans', Arial, sans-serif; background:#f9f5f4; margin:0; padding:32px 16px;">
  <div style="max-width:480px; margin:0 auto; background:#fff; border-radius:12px; padding:32px; box-shadow:0 6px 20px rgba(0,0,0,.06);">
    <h2 style="color:#3b0b0d; margin-top:0;">Hi {{ $name }},</h2>
    <p style="color:#333; font-size:15px; line-height:1.6;">
      Use the code below to verify your email address and finish creating your RESQLINK-G2 donor account.
    </p>
    <div style="text-align:center; margin:28px 0;">
      <span style="display:inline-block; font-size:32px; font-weight:700; letter-spacing:8px; color:#3b0b0d; background:#f0f0f0; padding:14px 24px; border-radius:8px;">{{ $code }}</span>
    </div>
    <p style="color:#888; font-size:13px; line-height:1.6;">
      This code expires in 15 minutes. If you didn't request this, you can safely ignore this email.
    </p>
  </div>
</body>
</html>
