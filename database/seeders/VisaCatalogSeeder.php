<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class VisaCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['slug' => 'egypt', 'flag' => 'eg', 'name_ar' => 'مصر', 'name_en' => 'Egypt', 'region' => 'other'],
            ['slug' => 'russia', 'flag' => 'ru', 'name_ar' => 'روسيا', 'name_en' => 'Russia', 'region' => 'other'],
            ['slug' => 'iraq', 'flag' => 'iq', 'name_ar' => 'العراق', 'name_en' => 'Iraq', 'region' => 'other'],
            ['slug' => 'uae', 'flag' => 'ae', 'name_ar' => 'الإمارات العربية المتحدة', 'name_en' => 'United Arab Emirates', 'region' => 'gulf'],
            ['slug' => 'kuwait', 'flag' => 'kw', 'name_ar' => 'الكويت', 'name_en' => 'Kuwait', 'region' => 'gulf'],
            ['slug' => 'oman', 'flag' => 'om', 'name_ar' => 'سلطنة عُمان', 'name_en' => 'Oman', 'region' => 'gulf'],
            ['slug' => 'qatar', 'flag' => 'qa', 'name_ar' => 'قطر', 'name_en' => 'Qatar', 'region' => 'gulf'],
            ['slug' => 'saudi-arabia', 'flag' => 'sa', 'name_ar' => 'المملكة العربية السعودية', 'name_en' => 'Saudi Arabia', 'region' => 'gulf'],
            ['slug' => 'bahrain', 'flag' => 'bh', 'name_ar' => 'البحرين', 'name_en' => 'Bahrain', 'region' => 'gulf'],
            ['slug' => 'libya', 'flag' => 'ly', 'name_ar' => 'ليبيا', 'name_en' => 'Libya', 'region' => 'other'],
        ];

        // نفس نوعيات التأشيرة والمستندات بتتكرر لكل الدول - عدّل الرسوم والمدة لكل دولة لاحقًا من لوحة التحكم
        $visaTypeTemplate = [
            [
                'key' => 'tourist', 'name_ar' => 'تأشيرة سياحية', 'name_en' => 'Tourist Visa',
                'fee' => 1500, 'processing_time_ar' => '5 - 7 أيام عمل',
                'documents' => [
                    ['ar' => 'جواز سفر ساري لمدة 6 أشهر على الأقل', 'en' => 'Valid passport (minimum 6 months validity)'],
                    ['ar' => 'صورة شخصية حديثة', 'en' => 'Recent passport-sized photo'],
                    ['ar' => 'حجز فندق مؤكد', 'en' => 'Confirmed hotel reservation'],
                    ['ar' => 'تذكرة طيران ذهاب وعودة', 'en' => 'Round-trip flight ticket'],
                    ['ar' => 'كشف حساب بنكي لآخر 3 أشهر', 'en' => 'Bank statement for the last 3 months'],
                    ['ar' => 'تأمين صحي للسفر', 'en' => 'Travel health insurance'],
                    ['ar' => 'تعبئة نموذج طلب التأشيرة', 'en' => 'Completed visa application form'],
                ],
            ],
            [
                'key' => 'work', 'name_ar' => 'تأشيرة عمل', 'name_en' => 'Work Visa',
                'fee' => 3200, 'processing_time_ar' => '10 - 15 يوم عمل',
                'documents' => [
                    ['ar' => 'جواز سفر ساري المفعول', 'en' => 'Valid passport'],
                    ['ar' => 'عقد عمل موثق من جهة العمل', 'en' => 'Notarized employment contract'],
                    ['ar' => 'خطاب دعوة من الشركة المستضيفة', 'en' => 'Invitation letter from the sponsoring company'],
                    ['ar' => 'شهادة خبرة أو مؤهلات علمية', 'en' => 'Experience certificate or educational qualifications'],
                    ['ar' => 'شهادة حسن سير وسلوك', 'en' => 'Police clearance certificate'],
                    ['ar' => 'فحص طبي شامل', 'en' => 'Comprehensive medical examination'],
                    ['ar' => 'صورة شخصية حديثة', 'en' => 'Recent passport-sized photo'],
                    ['ar' => 'ترجمة معتمدة للمستندات', 'en' => 'Certified translation of documents'],
                ],
            ],
            [
                'key' => 'student', 'name_ar' => 'تأشيرة دراسة', 'name_en' => 'Student Visa',
                'fee' => 2000, 'processing_time_ar' => '7 - 10 أيام عمل',
                'documents' => [
                    ['ar' => 'جواز سفر ساري المفعول', 'en' => 'Valid passport'],
                    ['ar' => 'خطاب قبول من المؤسسة التعليمية', 'en' => 'Acceptance letter from the educational institution'],
                    ['ar' => 'إثبات القدرة المالية لتغطية التكاليف', 'en' => 'Proof of financial capability to cover expenses'],
                    ['ar' => 'شهادات دراسية سابقة مترجمة', 'en' => 'Translated previous academic certificates'],
                    ['ar' => 'تأمين صحي للطلاب', 'en' => 'Student health insurance'],
                    ['ar' => 'صورة شخصية حديثة', 'en' => 'Recent passport-sized photo'],
                    ['ar' => 'إثبات دفع الرسوم الدراسية', 'en' => 'Proof of tuition fee payment'],
                    ['ar' => 'شهادة إتقان اللغة', 'en' => 'Language proficiency certificate'],
                ],
            ],
            [
                'key' => 'medical', 'name_ar' => 'تأشيرة علاج', 'name_en' => 'Medical Visa',
                'fee' => 1800, 'processing_time_ar' => '3 - 5 أيام عمل',
                'documents' => [
                    ['ar' => 'جواز سفر ساري المفعول', 'en' => 'Valid passport'],
                    ['ar' => 'تقرير طبي مفصل من المستشفى المحلي', 'en' => 'Detailed medical report from local hospital'],
                    ['ar' => 'خطاب من المستشفى المستضيف', 'en' => 'Letter from the host hospital'],
                    ['ar' => 'تأكيد حجز موعد علاجي', 'en' => 'Confirmed medical appointment'],
                    ['ar' => 'إثبات القدرة المالية لتغطية تكاليف العلاج', 'en' => 'Proof of financial capability for treatment costs'],
                    ['ar' => 'تأمين صحي للسفر', 'en' => 'Travel health insurance'],
                    ['ar' => 'صورة شخصية حديثة', 'en' => 'Recent passport-sized photo'],
                    ['ar' => 'كشف حساب بنكي', 'en' => 'Bank statement'],
                ],
            ],
            [
                'key' => 'family', 'name_ar' => 'تأشيرة عائلية', 'name_en' => 'Family Visa',
                'fee' => 2200, 'processing_time_ar' => '7 - 12 يوم عمل',
                'documents' => [
                    ['ar' => 'جواز سفر ساري المفعول', 'en' => 'Valid passport'],
                    ['ar' => 'عقد زواج موثق ومترجم', 'en' => 'Notarized and translated marriage certificate'],
                    ['ar' => 'شهادات ميلاد الأطفال (إن وجدت)', 'en' => 'Birth certificates of children (if applicable)'],
                    ['ar' => 'خطاب ضمان من الكفيل', 'en' => 'Sponsorship letter from the guarantor'],
                    ['ar' => 'إثبات القدرة المالية', 'en' => 'Proof of financial capability'],
                    ['ar' => 'صورة شخصية حديثة', 'en' => 'Recent passport-sized photo'],
                    ['ar' => 'عقد إيجار أو ملكية سكن', 'en' => 'Lease or property ownership contract'],
                    ['ar' => 'تأمين صحي للعائلة', 'en' => 'Family health insurance'],
                ],
            ],
            [
                'key' => 'business', 'name_ar' => 'تأشيرة رجال أعمال', 'name_en' => 'Business Visa',
                'fee' => 2500, 'processing_time_ar' => '5 - 10 أيام عمل',
                'documents' => [
                    ['ar' => 'جواز سفر ساري المفعول', 'en' => 'Valid passport'],
                    ['ar' => 'خطاب دعوة من الشركة المستضيفة', 'en' => 'Invitation letter from the host company'],
                    ['ar' => 'خطاب من الشركة المرسلة', 'en' => 'Letter from the sending company'],
                    ['ar' => 'سجل تجاري أو ترخيص شركة', 'en' => 'Commercial registration or company license'],
                    ['ar' => 'إثبات التعاملات التجارية السابقة', 'en' => 'Proof of previous business dealings'],
                    ['ar' => 'تأمين صحي للسفر', 'en' => 'Travel health insurance'],
                    ['ar' => 'صورة شخصية حديثة', 'en' => 'Recent passport-sized photo'],
                    ['ar' => 'كشف حساب بنكي للشركة', 'en' => 'Company bank statement'],
                ],
            ],
            [
                'key' => 'family_visit', 'name_ar' => 'تأشيرة زيارة عائلية', 'name_en' => 'Family Visit Visa',
                'fee' => 1600, 'processing_time_ar' => '5 - 8 أيام عمل',
                'documents' => [
                    ['ar' => 'جواز سفر ساري المفعول', 'en' => 'Valid passport'],
                    ['ar' => 'خطاب دعوة من أحد أفراد العائلة', 'en' => 'Invitation letter from a family member'],
                    ['ar' => 'إثبات صلة القرابة (شهادة ميلاد أو زواج)', 'en' => 'Proof of relationship (birth or marriage certificate)'],
                    ['ar' => 'جواز سفر الداعي', 'en' => "Inviter's passport copy"],
                    ['ar' => 'إقامة الداعي أو هويته', 'en' => "Inviter's residency or ID card"],
                    ['ar' => 'تأمين صحي للسفر', 'en' => 'Travel health insurance'],
                    ['ar' => 'صورة شخصية حديثة', 'en' => 'Recent passport-sized photo'],
                    ['ar' => 'تذكرة طيران ذهاب وعودة', 'en' => 'Round-trip flight ticket'],
                ],
            ],
            [
                'key' => 'transit', 'name_ar' => 'تأشيرة عبور', 'name_en' => 'Transit Visa',
                'fee' => 900, 'processing_time_ar' => '1 - 3 أيام عمل',
                'documents' => [
                    ['ar' => 'جواز سفر ساري المفعول', 'en' => 'Valid passport'],
                    ['ar' => 'تذكرة طيران للرحلة التالية', 'en' => 'Flight ticket for the onward journey'],
                    ['ar' => 'تأشيرة الدولة النهائية (إذا كانت مطلوبة)', 'en' => 'Final destination visa (if required)'],
                    ['ar' => 'تأمين صحي للسفر', 'en' => 'Travel health insurance'],
                    ['ar' => 'صورة شخصية حديثة', 'en' => 'Recent passport-sized photo'],
                    ['ar' => 'إثبات حجز فندق (إذا تطلب الأمر)', 'en' => 'Hotel reservation proof (if required)'],
                    ['ar' => 'تعبئة نموذج طلب التأشيرة', 'en' => 'Completed visa application form'],
                    ['ar' => 'تأكيد حجز الرحلة', 'en' => 'Flight booking confirmation'],
                ],
            ],
            [
                'key' => 'freelancer', 'name_ar' => 'تأشيرة عمل حر', 'name_en' => 'Freelancer Visa',
                'fee' => 2800, 'processing_time_ar' => '10 - 14 يوم عمل',
                'documents' => [
                    ['ar' => 'جواز سفر ساري المفعول', 'en' => 'Valid passport'],
                    ['ar' => 'سجل تجاري أو ترخيص عمل حر', 'en' => 'Trade license or freelancer permit'],
                    ['ar' => 'شهادات خبرة ومهارات مهنية', 'en' => 'Professional experience and skills certificates'],
                    ['ar' => 'محفظة أعمال أو مشاريع سابقة', 'en' => 'Portfolio of previous projects'],
                    ['ar' => 'إثبات الدخل (فواتير أو عقود)', 'en' => 'Proof of income (invoices or contracts)'],
                    ['ar' => 'تأمين صحي', 'en' => 'Health insurance'],
                    ['ar' => 'صورة شخصية حديثة', 'en' => 'Recent passport-sized photo'],
                    ['ar' => 'كشف حساب بنكي', 'en' => 'Bank statement'],
                ],
            ],
            [
                'key' => 'permanent_residency', 'name_ar' => 'تأشيرة إقامة دائمة', 'name_en' => 'Permanent Residency Visa',
                'fee' => 6000, 'processing_time_ar' => '20 - 30 يوم عمل',
                'documents' => [
                    ['ar' => 'جواز سفر ساري المفعول', 'en' => 'Valid passport'],
                    ['ar' => 'شهادات ميلاد عائلية', 'en' => 'Family birth certificates'],
                    ['ar' => 'شهادات خلو سوابق', 'en' => 'Police clearance certificates'],
                    ['ar' => 'إثبات الإقامة المستمرة', 'en' => 'Proof of continuous residence'],
                    ['ar' => 'إثبات الدخل الثابت', 'en' => 'Proof of stable income'],
                    ['ar' => 'فحص طبي شامل', 'en' => 'Comprehensive medical examination'],
                    ['ar' => 'شهادات المؤهلات العلمية', 'en' => 'Educational qualification certificates'],
                    ['ar' => 'إثبات معرفة اللغة', 'en' => 'Language proficiency proof'],
                    ['ar' => 'صور شخصية حديثة', 'en' => 'Recent passport-sized photos'],
                    ['ar' => 'رسوم طلب الإقامة', 'en' => 'Residency application fee'],
                ],
            ],
        ];

        foreach ($countries as $order => $countryData) {
            $country = Country::updateOrCreate(
                ['slug' => $countryData['slug']],
                [
                    'flag' => $countryData['flag'],
                    'name_ar' => $countryData['name_ar'],
                    'name_en' => $countryData['name_en'],
                    'region' => $countryData['region'],
                    'processing_time_en' => '5 - 20 business days',
                    'order' => $order,
                    'is_active' => true,
                ]
            );

            $country->visaTypes()->delete(); // هيمسح المستندات المرتبطة تلقائيًا (cascade)

            foreach ($visaTypeTemplate as $vi => $vt) {
                $visaType = $country->visaTypes()->create([
                    'key' => $vt['key'],
                    'name_ar' => $vt['name_ar'],
                    'name_en' => $vt['name_en'],
                    'fee' => $vt['fee'],
                    'processing_time_ar' => $vt['processing_time_ar'],
                    'order' => $vi,
                    'is_active' => true,
                ]);

                foreach ($vt['documents'] as $di => $doc) {
                    $visaType->documents()->create([
                        'text_ar' => $doc['ar'],
                        'text_en' => $doc['en'],
                        'order' => $di,
                    ]);
                }
            }
        }
    }
}
