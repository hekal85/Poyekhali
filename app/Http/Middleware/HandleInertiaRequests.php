<?php

namespace App\Http\Middleware;

use App\Models\Country;
use App\Models\UserNotification;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * بيانات مشتركة في كل صفحات الموقع (public + admin + عميل).
     *
     * ملحوظة: لو عندك بالفعل ملف HandleInertiaRequests.php (غالبًا موجود لو مشروعك
     * اتعمل بستارتر كيت Laravel الرسمي لـ Inertia+Vue)، متستبدلش الملف كله -
     * بس ضيف مفتاحي 'footerCountries' و 'unreadNotificationsCount' جوه array الـ share بتاعك.
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        // 4 دول بالظبط في الفوتر، حسب طلبك - لو عايز تغيّرهم عدّل مصفوفة الـ slugs دي
        $footerCountries = Country::where('is_active', true)
            ->whereIn('slug', ['saudi-arabia', 'uae', 'qatar', 'kuwait'])
            ->orderBy('order')
            ->get(['id', 'slug', 'name_ar', 'name_en']);

        // أنواع التأشيرات المتاحة (فريدة بالـ key) عشان عمود "أنواع التأشيرات" في الفوتر
        $footerVisaTypes = VisaType::where('is_active', true)
            ->whereHas('country', fn ($q) => $q->where('is_active', true))
            ->get(['key', 'name_ar', 'name_en'])
            ->unique('key')
            ->values();

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_admin' => (bool) $user->is_admin,
                ] : null,
            ],
            'footerCountries' => $footerCountries,
            'footerVisaTypes' => $footerVisaTypes,
            'unreadNotificationsCount' => $user
                ? UserNotification::where('user_id', $user->id)->whereNull('read_at')->count()
                : 0,
        ]);
    }
}
