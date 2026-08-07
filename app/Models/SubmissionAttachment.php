<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionAttachment extends Model
{
    protected $fillable = ['contact_submission_id', 'path', 'original_name', 'mime_type'];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ContactSubmission::class, 'contact_submission_id');
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }
}
