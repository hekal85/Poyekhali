<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $applications = Application::with(['country', 'visaType'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        $notifications = UserNotification::where('user_id', $request->user()->id)
            ->latest()
            ->take(30)
            ->get();

        return Inertia::render('Dashboard', [
            'applications' => $applications,
            'notifications' => $notifications,
        ]);
    }
}
