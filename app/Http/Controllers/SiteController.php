<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Country;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class SiteController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Home', [
            'countries' => $this->activeCountries(),
            'stats' => $this->liveStats(),
        ]);
    }

    public function countriesIndex(): Response
    {
        return Inertia::render('Countries/Index', [
            'countries' => $this->activeCountries(),
        ]);
    }

    public function countryShow(string $country): Response
    {
        $model = Country::where('slug', $country)->where('is_active', true)->firstOrFail();

        return Inertia::render('Countries/Show', [
            'country' => $model->toFrontend(),
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('Contact', [
            'countries' => $this->activeCountries(),
        ]);
    }

    public function apply(): Response
    {
        return Inertia::render('Apply', [
            'countries' => $this->activeCountries(),
        ]);
    }

    private function activeCountries()
    {
        return Country::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map->toFrontend()
            ->values();
    }

    /**
     * إحصائيات الهيرو - محسوبة مباشرة (live) من قاعدة البيانات في كل تحميل صفحة،
     * بدل جدول إحصائيات منفصل محتاج تحديث يدوي. كده الرقم مظبوط 100% دايمًا
     * ومفيش احتمال إنه "يفضل قديم" لو حد نسي يحدّث جدول لوحده.
     */
    private function liveStats(): array
    {
        $startDate = '2023-01-01';

        return [
            'countries' => Country::where('is_active', true)->count(),
            // "طلب اتنفذ" = طلبات وصلت لحالة "التأشيرة جاهزة" فعليًا (مؤشر ثقة حقيقي أدق
            // من مجرد عدد الطلبات المُرسلة اللي ممكن يكون فيها ملغي أو لسه تحت الدراسة)
            'applications' => Application::where('status', 'visa_ready')->count(),
            'clients' => User::where('is_admin', false)->count(),
            'years' => max(1, now()->diffInYears($startDate)),
        ];
    }
}
