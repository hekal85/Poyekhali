<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // مثال: PYK-000123
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('passport_number');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('country_id')->constrained();
            $table->foreignId('visa_type_id')->constrained();

            $table->string('payment_receipt_path')->nullable();

            // مفتاح إنجليزي ثابت في قاعدة البيانات - يتترجم في الواجهة حسب اللغة المختارة
            // القيم الممكنة: under_review, approved_processing, visa_ready, visa_cancelled, deleted, other
            $table->string('status')->default('under_review');

            $table->timestamps();

            $table->index(['order_number', 'passport_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
