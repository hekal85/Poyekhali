<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ContactSubmission;
use App\Models\Country;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'countries' => Country::count(),
                'submissions' => ContactSubmission::count(),
                'unread' => ContactSubmission::whereNull('read_at')->count(),
                'with_documents' => ContactSubmission::has('attachments')->count(),
                'applications' => Application::count(),
                'applications_under_review' => Application::where('status', 'under_review')->count(),
            ],
            'latestSubmissions' => ContactSubmission::latest()->take(5)->get(),
            'latestApplications' => Application::with(['country', 'visaType'])->latest()->take(5)->get(),
        ]);
    }
}
