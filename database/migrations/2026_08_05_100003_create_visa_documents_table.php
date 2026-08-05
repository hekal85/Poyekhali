<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_documents', function (Blueprint $table) {
            $table->id();
            // المستندات دلوقتي مرتبطة بنوع التأشيرة نفسه، مش بالدولة ككل -
            // عشان كل نوع تأشيرة (عمل / زيارة / عمرة...) يكون له قائمة مستندات مختلفة
            $table->foreignId('visa_type_id')->constrained()->cascadeOnDelete();
            $table->string('text_ar');
            $table->string('text_en');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_documents');
    }
};
