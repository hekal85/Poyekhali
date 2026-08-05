<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Inertia\Inertia;
use Inertia\Response;

class SubmissionController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Submissions/Index', [
            'submissions' => ContactSubmission::latest()->paginate(15),
        ]);
    }

    public function show(ContactSubmission $submission): Response
    {
        if (! $submission->isRead()) {
            $submission->update(['read_at' => now()]);
        }

        return Inertia::render('admin/Submissions/Show', [
            'submission' => $submission,
        ]);
    }

    public function destroy(ContactSubmission $submission)
    {
        if ($submission->document_path) {
            \Storage::disk('public')->delete($submission->document_path);
        }
        $submission->delete();

        return redirect()->route('admin.submissions.index')->with('success', 'تم حذف الرسالة.');
    }
}
