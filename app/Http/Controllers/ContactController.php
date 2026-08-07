<?php

namespace App\Http\Controllers;

use App\Mail\NewContactSubmission;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'country_interest' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:3000'],
            // أكتر من ملف - كل واحد لغاية 5 ميجا، صور أو PDF
            'documents' => ['nullable', 'array', 'max:5'],
            'documents.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $submission = ContactSubmission::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'country_interest' => $validated['country_interest'] ?? null,
            'message' => $validated['message'] ?? null,
        ]);

        foreach ($request->file('documents', []) as $file) {
            $path = $file->store('submissions', 'public');
            $submission->attachments()->create([
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
            ]);
        }

        $adminEmail = config('site.admin_email');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new NewContactSubmission($submission->fresh('attachments')));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return back()->with('success', 'تم إرسال طلبك بنجاح، هيتم التواصل معاك قريبًا.');
    }
}
