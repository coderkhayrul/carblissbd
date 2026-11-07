<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>{{ $subject }}</title></head>
<body style="margin:0;background:#f4f6f8;font-family:Arial,sans-serif;color:#263238"><div style="max-width:680px;margin:24px auto;padding:24px;background:#fff;border-radius:8px">
    <h2>{{ $company?->name ?: config('app.name') }}</h2><h1 style="font-size:22px;overflow-wrap:anywhere">{{ $subject }}</h1>
    <p>Hello {{ $customerName }},</p><div style="white-space:pre-line;overflow-wrap:anywhere;line-height:1.75">{{ $body }}</div>
    <hr style="border:0;border-top:1px solid #eee;margin-top:28px"><p style="font-size:13px;color:#64748b">{{ $company?->name ?: config('app.name') }}<br>{{ $company?->hotline ?: $company?->phone }}<br>{{ $company?->email }}</p>
</div></body></html>
