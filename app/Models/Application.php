<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'name', 'passport_number', 'phone', 'email',
        'address', 'notes', 'country_id', 'visa_type_id', 'payment_receipt_path', 'status',
    ];

    // كل الحالات الممكنة - المفتاح ثابت بالإنجليزي في قاعدة البيانات، والترجمة بتتم في الواجهة
    public const STATUSES = [
        'under_review',       // تحت الدراسة
        'approved_processing', // تم قبول الطلب وجارٍ العمل على استخراج التأشيرة
        'visa_ready',          // التأشيرة جاهزة
        'visa_cancelled',      // تأشيرة ملغاة
        'deleted',             // طلب محذوف
        'other',                // حالة أخرى
    ];

    // نسخة عربية جاهزة تُستخدم في نصوص الإشعارات/الإيميل اللي بيولّدها السيرفر (مش مرتبطة بـ vue-i18n)
    public const STATUS_LABELS_AR = [
        'under_review' => 'تحت الدراسة',
        'approved_processing' => 'تم قبول الطلب وجارٍ العمل على استخراج التأشيرة',
        'visa_ready' => 'التأشيرة جاهزة',
        'visa_cancelled' => 'تأشيرة ملغاة',
        'deleted' => 'طلب محذوف',
        'other' => 'حالة أخرى',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function visaType(): BelongsTo
    {
        return $this->belongsTo(VisaType::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public static function generateOrderNumber(): string
    {
        $last = static::query()->orderByDesc('id')->first();
        $next = $last ? ((int) substr($last->order_number, 4)) + 1 : 1;

        return 'PYK-' . str_pad((string) $next, 6, '0', STR_PAD_LEFT);
    }
}
