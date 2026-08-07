<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visa_types', function (Blueprint $table) {
            $table->string('processing_time_ar')->nullable()->after('name_en');
            $table->boolean('is_active')->default(true)->after('fee');
        });

        // processing_time_ar بقت خاصية نوع التأشيرة مش الدولة ككل (كل نوع تأشيرة مدته مختلفة)
        if (Schema::hasColumn('countries', 'processing_time_ar')) {
            Schema::table('countries', function (Blueprint $table) {
                $table->dropColumn('processing_time_ar');
            });
        }
    }

    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->string('processing_time_ar')->nullable()->after('processing_time_en');
        });

        Schema::table('visa_types', function (Blueprint $table) {
            $table->dropColumn(['processing_time_ar', 'is_active']);
        });
    }
};
