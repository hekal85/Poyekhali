<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $data = config('countries', []);

        foreach ($data as $order => $item) {
            $country = Country::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'flag' => $item['flag'],
                    'name_ar' => $item['name']['ar'],
                    'name_en' => $item['name']['en'],
                    'region' => $item['region'],
                    'processing_time_ar' => $item['processing_time']['ar'],
                    'processing_time_en' => $item['processing_time']['en'],
                    'order' => $order,
                    'is_active' => true,
                ]
            );

            $country->visaTypes()->delete();

            foreach ($item['visa_types'] as $i => $visaType) {
                $vt = $country->visaTypes()->create([
                    'key' => $visaType['key'],
                    'name_ar' => $visaType['name']['ar'],
                    'name_en' => $visaType['name']['en'],
                    'fee' => $visaType['fee'],
                    'order' => $i,
                ]);

                // البيانات القديمة في config/countries.php كانت قائمة مستندات واحدة للدولة كلها.
                // بنكررها هنا على كل نوع تأشيرة كنقطة بداية - عدّلها بعد كده من لوحة التحكم
                // لتحديد المستندات الفعلية المختلفة لكل نوع تأشيرة.
                foreach ($item['documents'] as $di => $doc) {
                    $vt->documents()->create([
                        'text_ar' => $doc['ar'],
                        'text_en' => $doc['en'],
                        'order' => $di,
                    ]);
                }
            }
        }
    }
}
