<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = [
        'name', 'phone', 'country_interest', 'message',
        'document_path', 'document_name', 'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}
