<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDocument extends Model
{
    protected $fillable = ['application_id', 'document_type', 'path', 'original_name'];

    // أنواع المستندات المتاحة في القائمة المنسدلة بصفحة "ابدأ طلبك"
    public const TYPES = [
        'passport', 'degree', 'personal_photo', 'travel_ticket', 'medical_certificate', 'hotel_booking', 'other',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
