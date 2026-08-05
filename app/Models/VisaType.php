<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VisaType extends Model
{
    protected $fillable = ['country_id', 'key', 'name_ar', 'name_en', 'fee', 'order'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VisaDocument::class)->orderBy('order');
    }
}
