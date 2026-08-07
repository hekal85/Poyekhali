<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * لو كان عندك سيدرز تانية مخصوصة في مشروعك قبل كده، ضيفها هنا كمان -
     * السطرين دول بس هما الأساسيين لموقع بيخالي.
     */
    public function run(): void
    {
        $this->call([
            VisaCatalogSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
