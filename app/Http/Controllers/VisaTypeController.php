<?php

namespace App\Http\Controllers;

use App\Models\VisaType;
use Inertia\Inertia;
use Inertia\Response;

class VisaTypeController extends Controller
{
    public function show(string $key): Response
    {
        // كل نسخ نوع التأشيرة ده عبر الدول المختلفة (نفس الـ key، كل واحدة بدولتها وسعرها ومدتها)
        $entries = VisaType::with('country')
            ->where('key', $key)
            ->where('is_active', true)
            ->whereHas('country', fn ($q) => $q->where('is_active', true))
            ->get()
            ->filter(fn ($v) => $v->country !== null)
            ->values();

        abort_if($entries->isEmpty(), 404);

        return Inertia::render('VisaTypes/Show', [
            'key' => $key,
            'name' => ['ar' => $entries->first()->name_ar, 'en' => $entries->first()->name_en],
            'entries' => $entries->map(fn (VisaType $v) => [
                'country_slug' => $v->country->slug,
                'country_flag' => $v->country->flag,
                'country_name' => ['ar' => $v->country->name_ar, 'en' => $v->country->name_en],
                'fee' => $v->fee,
                'processing_time_ar' => $v->processing_time_ar,
                'visa_type_id' => $v->id,
            ]),
        ]);
    }
}
