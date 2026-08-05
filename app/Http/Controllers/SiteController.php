<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SiteController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Home', [
            'countries' => Country::where('is_active', true)
                ->orderBy('order')
                ->get()
                ->map->toFrontend()
                ->values(),
        ]);
    }

    public function countriesIndex(): Response
    {
        return Inertia::render('Countries/Index', [
            'countries' => Country::where('is_active', true)
                ->orderBy('order')
                ->get()
                ->map->toFrontend()
                ->values(),
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
            'countries' => Country::where('is_active', true)->orderBy('order')->get()->map->toFrontend()->values(),
        ]);
    }

    public function setLocale(Request $request)
    {
        $locale = $request->input('locale', 'ar');

        abort_unless(in_array($locale, ['ar', 'en']), 404);

        session(['locale' => $locale]);

        return back();
    }
}
