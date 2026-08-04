<?php

// بيانات الدول والتأشيرات - يمكنك لاحقًا نقلها لقاعدة بيانات (جدول countries + visa_types)
// كل دولة فيها: slug, flag (كود ISO للعلم), أسماء بالعربي/الإنجليزي, أنواع التأشيرات, المستندات, الرسوم, المدة

return [

    'saudi-arabia' => [
        'slug' => 'saudi-arabia',
        'flag' => 'sa',
        'name' => ['ar' => 'المملكة العربية السعودية', 'en' => 'Saudi Arabia'],
        'region' => 'gulf',
        'processing_time' => ['ar' => '7 - 14 يوم عمل', 'en' => '7 - 14 business days'],
        'visa_types' => [
            ['key' => 'work', 'name' => ['ar' => 'تأشيرة عمل (عمالة)', 'en' => 'Employment Visa'], 'fee' => 3200],
            ['key' => 'umrah', 'name' => ['ar' => 'تأشيرة عمرة', 'en' => 'Umrah Visa'], 'fee' => 1800],
            ['key' => 'visit', 'name' => ['ar' => 'تأشيرة زيارة', 'en' => 'Visit Visa'], 'fee' => 2100],
        ],
        'documents' => [
            ['ar' => 'جواز سفر ساري لمدة 6 أشهر على الأقل', 'en' => 'Passport valid for at least 6 months'],
            ['ar' => 'عقد عمل موثّق من الجهة الكفيلة', 'en' => 'Attested employment contract from sponsor'],
            ['ar' => 'شهادة فحص طبي معتمدة (GAMCA)', 'en' => 'Approved medical fitness certificate (GAMCA)'],
            ['ar' => 'صحيفة حالة جنائية', 'en' => 'Criminal record certificate'],
            ['ar' => '4 صور شخصية بخلفية بيضاء', 'en' => '4 personal photos, white background'],
        ],
    ],

    'uae' => [
        'slug' => 'uae',
        'flag' => 'ae',
        'name' => ['ar' => 'الإمارات العربية المتحدة', 'en' => 'United Arab Emirates'],
        'region' => 'gulf',
        'processing_time' => ['ar' => '5 - 10 أيام عمل', 'en' => '5 - 10 business days'],
        'visa_types' => [
            ['key' => 'work', 'name' => ['ar' => 'تأشيرة عمل مقيم', 'en' => 'Employment Residence Visa'], 'fee' => 3600],
            ['key' => 'visit', 'name' => ['ar' => 'تأشيرة زيارة سياحية', 'en' => 'Tourist Visit Visa'], 'fee' => 1500],
            ['key' => 'freelance', 'name' => ['ar' => 'تأشيرة عمل حر', 'en' => 'Freelance Permit Visa'], 'fee' => 4200],
        ],
        'documents' => [
            ['ar' => 'جواز سفر ساري لمدة 6 أشهر على الأقل', 'en' => 'Passport valid for at least 6 months'],
            ['ar' => 'عرض عمل / عقد موقّع من الكفيل', 'en' => 'Signed offer letter / sponsor contract'],
            ['ar' => 'فحص طبي بعد الوصول', 'en' => 'Medical fitness test after arrival'],
            ['ar' => 'صورة شخصية حديثة', 'en' => 'Recent personal photo'],
        ],
    ],

    'qatar' => [
        'slug' => 'qatar',
        'flag' => 'qa',
        'name' => ['ar' => 'قطر', 'en' => 'Qatar'],
        'region' => 'gulf',
        'processing_time' => ['ar' => '10 - 15 يوم عمل', 'en' => '10 - 15 business days'],
        'visa_types' => [
            ['key' => 'work', 'name' => ['ar' => 'تأشيرة عمل', 'en' => 'Work Visa'], 'fee' => 3400],
            ['key' => 'visit', 'name' => ['ar' => 'تأشيرة زيارة عائلية', 'en' => 'Family Visit Visa'], 'fee' => 1900],
        ],
        'documents' => [
            ['ar' => 'جواز سفر ساري', 'en' => 'Valid passport'],
            ['ar' => 'عقد عمل موثّق من وزارة العمل القطرية', 'en' => 'Employment contract attested by Qatari MOL'],
            ['ar' => 'شهادة خلو من السوابق', 'en' => 'Clean criminal record certificate'],
            ['ar' => 'تقرير فحص طبي', 'en' => 'Medical examination report'],
        ],
    ],

    'kuwait' => [
        'slug' => 'kuwait',
        'flag' => 'kw',
        'name' => ['ar' => 'الكويت', 'en' => 'Kuwait'],
        'region' => 'gulf',
        'processing_time' => ['ar' => '10 - 20 يوم عمل', 'en' => '10 - 20 business days'],
        'visa_types' => [
            ['key' => 'work', 'name' => ['ar' => 'تأشيرة عمل (المادة 18)', 'en' => 'Work Visa (Article 18)'], 'fee' => 3500],
            ['key' => 'visit', 'name' => ['ar' => 'تأشيرة زيارة', 'en' => 'Visit Visa'], 'fee' => 2000],
        ],
        'documents' => [
            ['ar' => 'جواز سفر ساري لمدة سنة على الأقل', 'en' => 'Passport valid for at least 1 year'],
            ['ar' => 'عقد عمل من الكفيل الكويتي', 'en' => 'Employment contract from Kuwaiti sponsor'],
            ['ar' => 'مؤهل دراسي معتمد', 'en' => 'Attested educational qualification'],
            ['ar' => 'فحص طبي وبصمة', 'en' => 'Medical test and fingerprinting'],
        ],
    ],

    'bahrain' => [
        'slug' => 'bahrain',
        'flag' => 'bh',
        'name' => ['ar' => 'البحرين', 'en' => 'Bahrain'],
        'region' => 'gulf',
        'processing_time' => ['ar' => '5 - 12 يوم عمل', 'en' => '5 - 12 business days'],
        'visa_types' => [
            ['key' => 'work', 'name' => ['ar' => 'تأشيرة عمل', 'en' => 'Work Visa'], 'fee' => 3000],
            ['key' => 'visit', 'name' => ['ar' => 'تأشيرة زيارة', 'en' => 'Visit Visa'], 'fee' => 1600],
        ],
        'documents' => [
            ['ar' => 'جواز سفر ساري', 'en' => 'Valid passport'],
            ['ar' => 'عقد عمل موثّق (LMRA)', 'en' => 'LMRA-attested employment contract'],
            ['ar' => 'فحص طبي', 'en' => 'Medical fitness test'],
        ],
    ],

    'oman' => [
        'slug' => 'oman',
        'flag' => 'om',
        'name' => ['ar' => 'سلطنة عُمان', 'en' => 'Oman'],
        'region' => 'gulf',
        'processing_time' => ['ar' => '10 - 15 يوم عمل', 'en' => '10 - 15 business days'],
        'visa_types' => [
            ['key' => 'work', 'name' => ['ar' => 'تأشيرة عمل', 'en' => 'Work Visa'], 'fee' => 3300],
            ['key' => 'visit', 'name' => ['ar' => 'تأشيرة زيارة', 'en' => 'Visit Visa'], 'fee' => 1700],
        ],
        'documents' => [
            ['ar' => 'جواز سفر ساري لمدة 6 أشهر', 'en' => 'Passport valid for 6 months'],
            ['ar' => 'عقد عمل موثّق من وزارة العمل العُمانية', 'en' => 'Contract attested by Omani MOL'],
            ['ar' => 'شهادة خلو سوابق', 'en' => 'Clean record certificate'],
        ],
    ],

    'jordan' => [
        'slug' => 'jordan',
        'flag' => 'jo',
        'name' => ['ar' => 'الأردن', 'en' => 'Jordan'],
        'region' => 'other',
        'processing_time' => ['ar' => '5 - 10 أيام عمل', 'en' => '5 - 10 business days'],
        'visa_types' => [
            ['key' => 'work', 'name' => ['ar' => 'تأشيرة عمل', 'en' => 'Work Visa'], 'fee' => 2600],
            ['key' => 'visit', 'name' => ['ar' => 'تأشيرة سياحية', 'en' => 'Tourist Visa'], 'fee' => 1200],
        ],
        'documents' => [
            ['ar' => 'جواز سفر ساري', 'en' => 'Valid passport'],
            ['ar' => 'عقد عمل / خطاب دعوة', 'en' => 'Employment contract / invitation letter'],
        ],
    ],

    'malaysia' => [
        'slug' => 'malaysia',
        'flag' => 'my',
        'name' => ['ar' => 'ماليزيا', 'en' => 'Malaysia'],
        'region' => 'other',
        'processing_time' => ['ar' => '10 - 20 يوم عمل', 'en' => '10 - 20 business days'],
        'visa_types' => [
            ['key' => 'work', 'name' => ['ar' => 'تصريح عمل', 'en' => 'Employment Pass'], 'fee' => 3900],
            ['key' => 'visit', 'name' => ['ar' => 'تأشيرة زيارة', 'en' => 'Visit Visa'], 'fee' => 1400],
        ],
        'documents' => [
            ['ar' => 'جواز سفر ساري لمدة سنة', 'en' => 'Passport valid for 1 year'],
            ['ar' => 'عرض عمل من صاحب العمل الماليزي', 'en' => 'Job offer from Malaysian employer'],
            ['ar' => 'مؤهل دراسي مصدّق', 'en' => 'Attested educational certificate'],
        ],
    ],

];
