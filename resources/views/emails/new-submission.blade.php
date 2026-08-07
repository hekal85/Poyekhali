<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; background:#F2F1EC; padding:24px; color:#0F2A3D; }
        .card { background:#fff; border-radius:12px; padding:24px; max-width:560px; margin:auto; }
        .row { margin-bottom:12px; }
        .label { font-weight:bold; color:#145C56; display:block; font-size:13px; }
        .footer { text-align:center; font-size:12px; color:#999; margin-top:20px; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="color:#0F2A3D;">طلب تواصل جديد من موقع بيخالي</h2>

        <div class="row"><span class="label">الاسم</span>{{ $submission->name }}</div>
        <div class="row"><span class="label">رقم الموبايل</span>{{ $submission->phone }}</div>
        @if($submission->email)
        <div class="row"><span class="label">الإيميل</span>{{ $submission->email }}</div>
        @endif
        @if($submission->country_interest)
        <div class="row"><span class="label">الدولة المهتم بيها</span>{{ $submission->country_interest }}</div>
        @endif
        @if($submission->message)
        <div class="row"><span class="label">الرسالة</span>{{ $submission->message }}</div>
        @endif
        @if($submission->attachments->count())
        <div class="row">
            <span class="label">المرفقات ({{ $submission->attachments->count() }})</span>
            @foreach($submission->attachments as $a)
                {{ $a->original_name }}@if(!$loop->last), @endif
            @endforeach
            (مرفقين مع الإيميل ده)
        </div>
        @endif
    </div>
    <p class="footer">تقدر تشوف كل الرسائل من لوحة التحكم: {{ url('/admin/submissions') }}</p>
</body>
</html>
