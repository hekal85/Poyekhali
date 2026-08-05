<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisaDocument extends Model
{
    protected $fillable = ['visa_type_id', 'text_ar', 'text_en', 'order'];

    public function visaType(): BelongsTo
    {
        return $this->belongsTo(VisaType::class);
    }
}
