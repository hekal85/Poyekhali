<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'flag', 'name_ar', 'name_en', 'region',
        'processing_time_en', 'image_path', 'order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function visaTypes(): HasMany
    {
        return $this->hasMany(VisaType::class)->orderBy('order');
    }

    public function activeVisaTypes(): HasMany
    {
        return $this->visaTypes()->where('is_active', true);
    }

    public function toFrontend(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'flag' => $this->flag,
            'name' => ['ar' => $this->name_ar, 'en' => $this->name_en],
            'region' => $this->region,
            'processing_time_en' => $this->processing_time_en,
            'image_url' => $this->image_path ? asset('storage/' . $this->image_path) : null,
            'visa_types' => $this->activeVisaTypes->map(fn (VisaType $v) => [
                'id' => $v->id,
                'key' => $v->key,
                'name' => ['ar' => $v->name_ar, 'en' => $v->name_en],
                'fee' => $v->fee,
                'processing_time_ar' => $v->processing_time_ar,
                'documents' => $v->documents->map(fn (VisaDocument $d) => [
                    'ar' => $d->text_ar,
                    'en' => $d->text_en,
                ]),
            ]),
        ];
    }
}
