<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SiteController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Home', [
            'countries' => collect(config('countries'))->values(),
        ]);
    }

    public function countriesIndex(): Response
    {
        return Inertia::render('Countries/Index', [
            'countries' => collect(config('countries'))->values(),
        ]);
    }

    public function countryShow(string $country): Response
    {
        $data = config("countries.$country");

        abort_unless($data, 404);

        return Inertia::render('Countries/Show', [
            'country' => $data,
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('Contact');
    }

    public function setLocale(Request $request)
    {
        $locale = $request->input('locale', 'ar');

        abort_unless(in_array($locale, ['ar', 'en']), 404);

        session(['locale' => $locale]);

        return back();
    }
}
