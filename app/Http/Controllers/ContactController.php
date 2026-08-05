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
            'country_interest' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:3000'],
            // مستندات مسموحة: صور أو PDF لغاية 5 ميجا
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $documentPath = null;
        $documentName = null;

        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('submissions', 'public');
            $documentName = $request->file('document')->getClientOriginalName();
        }

        $submission = ContactSubmission::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'country_interest' => $validated['country_interest'] ?? null,
            'message' => $validated['message'] ?? null,
            'document_path' => $documentPath,
            'document_name' => $documentName,
        ]);

        $adminEmail = config('site.admin_email');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new NewContactSubmission($submission));
            } catch (\Throwable $e) {
                // ما نوقفش الطلب لو الإيميل فشل (مثلاً إعدادات SMTP لسه مش مظبوطة) -
                // الرسالة اتحفظت في قاعدة البيانات وهتلاقيها في لوحة التحكم على أي حال
                report($e);
            }
        }

        return back()->with('success', 'تم إرسال طلبك بنجاح، هيتم التواصل معاك قريبًا.');
    }
}
