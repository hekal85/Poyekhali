<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\SubmissionAttachment;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class SubmissionController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Submissions/Index', [
            'submissions' => ContactSubmission::withCount('attachments')->latest()->paginate(15),
        ]);
    }

    public function show(ContactSubmission $submission): Response
    {
        if (! $submission->isRead()) {
            $submission->update(['read_at' => now()]);
        }

        $submission->load('attachments');

        return Inertia::render('admin/Submissions/Show', [
            'submission' => $submission,
        ]);
    }

    public function destroy(ContactSubmission $submission)
    {
        foreach ($submission->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
        }
        $submission->delete();

        return redirect()->route('admin.submissions.index')->with('success', 'تم حذف الرسالة.');
    }

    /**
     * تحميل مباشر عن طريق Laravel نفسه (مش عن طريق رابط storage/ العام) -
     * ده بيضمن إن التحميل يشتغل حتى لو الـ symlink بتاع "php artisan storage:link"
     * ماتعملش صح على وضوز (مشكلة شائعة على Windows من غير صلاحيات Admin).
     */
    public function downloadAttachment(SubmissionAttachment $attachment)
    {
        abort_unless(Storage::disk('public')->exists($attachment->path), 404);

        return Storage::disk('public')->download($attachment->path, $attachment->original_name);
    }

    /**
     * لعرض الصورة inline جوه لوحة التحكم من غير ما نعتمد على storage:link -
     * نفس الحل اللي بيضمن التحميل يشتغل، لكن بـ header يخلي المتصفح يعرضها بدل ما ينزّلها.
     */
    public function viewAttachment(SubmissionAttachment $attachment)
    {
        abort_unless(Storage::disk('public')->exists($attachment->path), 404);

        return Storage::disk('public')->response($attachment->path, $attachment->original_name, [
            'Content-Disposition' => 'inline; filename="' . $attachment->original_name . '"',
        ]);
    }
}
