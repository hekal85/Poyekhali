<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; background:#F2F1EC; padding:24px; color:#0F2A3D; }
        .card { background:#fff; border-radius:12px; padding:24px; max-width:560px; margin:auto; }
        .order { display:inline-block; background:#145C56; color:#fff; padding:6px 14px; border-radius:999px; font-weight:bold; }
        .status { display:inline-block; background:#B8863B; color:#fff; padding:6px 14px; border-radius:999px; font-weight:bold; margin-top:10px; }
        .footer { text-align:center; font-size:12px; color:#999; margin-top:20px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>تحية طيبة {{ $application->name }}،</h2>
        <p>في تحديث جديد على حالة طلبك.</p>

        <p style="margin-top:20px;">رقم طلبك: <span class="order">{{ $application->order_number }}</span></p>
        <p>الحالة الجديدة: <span class="status">{{ $statusLabel }}</span></p>

        <p style="margin-top:20px; font-size:13px; color:#555;">
            تقدر تشوف كل تفاصيل طلبك في أي وقت من صفحة "تتبع حالة الطلب" على موقعنا.
        </p>
    </div>
    <p class="footer">فريق بيخالي (Poyekhali) — {{ url('/') }}</p>
</body>
</html>
