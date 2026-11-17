<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>SMTP Configuration Test</title></head>
<body style="margin:0;padding:24px 12px;background:#f3f5f8;font-family:Arial,sans-serif;color:#243246">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;margin:auto;background:#ffffff;border:1px solid #e4e9f0;border-radius:10px">
<tr><td style="padding:24px;background:#F15A29;color:#ffffff;font-size:22px;font-weight:bold">{{ $senderName }}</td></tr>
<tr><td style="padding:28px;line-height:1.8"><h1 style="margin:0 0 16px;font-size:24px">Your test email has arrived!</h1>
<p>Your website successfully sent this message using the saved Email Settings.</p>
<p style="padding:16px;background:#f6f8fb;border-radius:6px"><strong>Sent at:</strong> {{ $sentAt }} (Bangladesh time)<br><strong>Purpose:</strong> SMTP configuration check</p>
<p>Order notification emails also require the database queue worker to be running and email notifications to be enabled.</p>
<p style="color:#64748b;font-size:12px">This test was requested from your admin Email Settings page.</p></td></tr>
</table></body></html>
