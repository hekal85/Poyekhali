<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Services\ApplicationNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApplyController extends Controller
{
    public function store(Request $request, ApplicationNotifier $notifier): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'passport_number' => ['required', 'string', 'max:50'],
            'country_id' => ['required', 'exists:countries,id'],
            'visa_type_id' => ['required', 'exists:visa_types,id'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:3000'],
            'payment_receipt' => ['nullable', 'image', 'max:5120'],

            'documents' => ['nullable', 'array', 'max:10'],
            'documents.*.type' => ['required_with:documents', Rule::in(ApplicationDocument::TYPES)],
            'documents.*.file' => ['required_with:documents', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $receiptPath = null;
        if ($request->hasFile('payment_receipt')) {
            $receiptPath = $request->file('payment_receipt')->store('receipts', 'public');
        }

        $application = Application::create([
            'order_number' => Application::generateOrderNumber(),
            'user_id' => $request->user()?->id,
            'name' => $validated['name'],
            'passport_number' => $validated['passport_number'],
            'country_id' => $validated['country_id'],
            'visa_type_id' => $validated['visa_type_id'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'payment_receipt_path' => $receiptPath,
            'status' => 'under_review',
        ]);

        foreach ($request->file('documents', []) as $i => $docFields) {
            if (! isset($docFields['file'])) {
                continue;
            }
            $file = $docFields['file'];
            $path = $file->store('application-documents', 'public');

            $application->documents()->create([
                'document_type' => $request->input("documents.$i.type", 'other'),
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        $notifier->notify($application->fresh(['country', 'visaType']));

        return back()->with('success', [
            'order_number' => $application->order_number,
        ]);
    }
}
