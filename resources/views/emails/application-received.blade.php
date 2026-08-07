<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; background:#F2F1EC; padding:24px; color:#0F2A3D; }
        .card { background:#fff; border-radius:12px; padding:24px; max-width:560px; margin:auto; }
        .order { display:inline-block; background:#145C56; color:#fff; padding:6px 14px; border-radius:999px; font-weight:bold; }
        .row { margin:14px 0; font-size:14px; }
        .label { font-weight:bold; color:#145C56; display:block; font-size:12px; margin-bottom:2px; }
        .footer { text-align:center; font-size:12px; color:#999; margin-top:20px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>تحية طيبة {{ $application->name }}،</h2>
        <p>تم استلام طلبك بنجاح وهيتم مراجعته من فريقنا في أقرب وقت.</p>

        <p style="margin-top:20px;">رقم طلبك: <span class="order">{{ $application->order_number }}</span></p>

        <div class="row">
            <span class="label">الدولة</span>
            {{ $application->country->name_ar }}
        </div>
        <div class="row">
            <span class="label">نوع التأشيرة</span>
            {{ $application->visaType->name_ar }}
        </div>
        <div class="row">
            <span class="label">حالة الطلب الحالية</span>
            تحت الدراسة
        </div>

        <p style="margin-top:20px; font-size:13px; color:#555;">
            تقدر تتابع حالة طلبك في أي وقت من صفحة "تتبع حالة الطلب" على موقعنا،
            برقم الطلب ده ورقم جواز سفرك.
        </p>
    </div>
    <p class="footer">فريق بيخالي (Poyekhali) — {{ url('/') }}</p>
</body>
</html>
