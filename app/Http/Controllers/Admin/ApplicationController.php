<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Country;
use App\Models\UserNotification;
use App\Models\VisaType;
use App\Services\ApplicationNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Application::with(['country', 'visaType'])->latest();

        if ($search = $request->string('q')->trim()->value()) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('passport_number', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->string('status')->value()) {
            $query->where('status', $status);
        }

        if ($countryId = $request->integer('country_id')) {
            $query->where('country_id', $countryId);
        }

        if ($visaTypeKey = $request->string('visa_type_key')->value()) {
            $query->whereHas('visaType', fn ($q) => $q->where('key', $visaTypeKey));
        }

        return Inertia::render('admin/Applications/Index', [
            'applications' => $query->paginate(15)->withQueryString(),
            'filters' => $request->only(['q', 'status', 'country_id', 'visa_type_key']),
            'statuses' => Application::STATUSES,
            'countries' => Country::orderBy('name_ar')->get(['id', 'name_ar']),
            'visaTypesList' => VisaType::select('id', 'name_ar', 'key')->get()->unique('key')->values(),
        ]);
    }

    public function show(Application $application): Response
    {
        $application->load(['country', 'visaType', 'documents']);

        return Inertia::render('admin/Applications/Show', [
            'application' => $application,
            'statuses' => Application::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Application $application, ApplicationNotifier $notifier)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Application::STATUSES)],
        ]);

        $oldStatus = $application->status;
        $application->update($validated);

        if ($oldStatus !== $validated['status']) {
            // إشعار داخل الموقع (الجرس) - بس لو الطلب مرتبط بحساب مسجّل دخول وقت التقديم
            UserNotification::notify(
                $application->user_id,
                'تحديث حالة طلبك ' . $application->order_number,
                'حالة طلبك بقت الآن: ' . (Application::STATUS_LABELS_AR[$validated['status']] ?? $validated['status']),
                "/dashboard#{$application->order_number}"
            );

            // إيميل + تيليجرام/واتساب لو متاح (best-effort، مش بيوقف حفظ التحديث لو فشل)
            $notifier->notifyStatusChange($application->fresh(['country', 'visaType']));
        }

        return back()->with('success', 'تم تحديث حالة الطلب.');
    }

    public function downloadDocument(ApplicationDocument $document)
    {
        abort_unless(Storage::disk('public')->exists($document->path), 404);

        return Storage::disk('public')->download($document->path, $document->original_name);
    }

    public function viewDocument(ApplicationDocument $document)
    {
        abort_unless(Storage::disk('public')->exists($document->path), 404);

        return Storage::disk('public')->response($document->path, $document->original_name, [
            'Content-Disposition' => 'inline; filename="' . $document->original_name . '"',
        ]);
    }

    public function downloadReceipt(Application $application)
    {
        abort_unless($application->payment_receipt_path && Storage::disk('public')->exists($application->payment_receipt_path), 404);

        return Storage::disk('public')->download($application->payment_receipt_path, "receipt-{$application->order_number}");
    }

    public function viewReceipt(Application $application)
    {
        abort_unless($application->payment_receipt_path && Storage::disk('public')->exists($application->payment_receipt_path), 404);

        return Storage::disk('public')->response($application->payment_receipt_path, "receipt-{$application->order_number}", [
            'Content-Disposition' => 'inline; filename="receipt-' . $application->order_number . '"',
        ]);
    }
}
