<?php

return [
    // إيميل استلام إشعارات النماذج والطلبات الجديدة
    'admin_email' => env('ADMIN_NOTIFICATION_EMAIL', 'hekal_85@hotmail.com'),

    // بيانات تواصل تظهر في الهيدر وصفحة "تواصل معنا"
    'contact_phone' => env('SITE_CONTACT_PHONE', '+79936445881'),
    'contact_whatsapp' => env('SITE_CONTACT_WHATSAPP', '79936445881'), // بدون + أو مسافات لاستخدامه في رابط wa.me
    'contact_email' => env('SITE_CONTACT_EMAIL', 'hekal_85@hotmail.com'),
    'contact_telegram' => env('SITE_CONTACT_TELEGRAM', 'hekal_85'), // بدون @
];
