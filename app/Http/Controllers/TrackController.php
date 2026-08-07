<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TrackController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Track');
    }

    public function lookup(Request $request): Response
    {
        $validated = $request->validate([
            'order_number' => ['required', 'string'],
            'passport_number' => ['required', 'string'],
        ]);

        $application = Application::with(['country', 'visaType'])
            ->where('order_number', $validated['order_number'])
            ->where('passport_number', $validated['passport_number'])
            ->first();

        return Inertia::render('Track', [
            'result' => $application ? [
                'order_number' => $application->order_number,
                'status' => $application->status,
                'country' => ['ar' => $application->country->name_ar, 'en' => $application->country->name_en],
                'visa_type' => ['ar' => $application->visaType->name_ar, 'en' => $application->visaType->name_en],
                'created_at' => $application->created_at?->toDateString(),
            ] : null,
            'notFound' => ! $application,
            'searched' => true,
        ]);
    }
}
