<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// اسمها UserNotification مش Notification عشان متتعارضش مع نظام الإشعارات المدمج في Laravel
class UserNotification extends Model
{
    protected $table = 'notifications';

    protected $fillable = ['user_id', 'title', 'message', 'link', 'read_at'];

    protected $casts = ['read_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function notify(?int $userId, string $title, string $message, ?string $link = null): void
    {
        if (! $userId) {
            return; // الطلب مش مرتبط بحساب مسجل - مفيش حد نبعتله إشعار داخل الموقع
        }

        static::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'link' => $link,
        ]);
    }
}
