<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('flag', 2); // ISO 3166-1 alpha-2, e.g. sa, ae
            $table->string('name_ar');
            $table->string('name_en');
            $table->enum('region', ['gulf', 'other'])->default('gulf');
            $table->string('processing_time_ar');
            $table->string('processing_time_en');
            $table->string('image_path')->nullable(); // صورة الدولة (بانر)
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
