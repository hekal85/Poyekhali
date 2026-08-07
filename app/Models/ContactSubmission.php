<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactSubmission extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'country_interest', 'message', 'read_at',
    ];

    protected $casts = ['read_at' => 'datetime'];

    public function attachments(): HasMany
    {
        return $this->hasMany(SubmissionAttachment::class);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}
