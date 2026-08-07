<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewContactSubmission extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactSubmission $submission)
    {
    }

    public function build()
    {
        $mail = $this->subject('طلب تواصل جديد - بيخالي')
            ->view('emails.new-submission')
            ->with(['submission' => $this->submission]);

        // يرفق كل ملفات الرسالة (ممكن يكون أكتر من ملف دلوقتي) بدل الملف الواحد القديم
        foreach ($this->submission->attachments as $attachment) {
            $mail->attach(storage_path('app/public/' . $attachment->path), [
                'as' => $attachment->original_name,
            ]);
        }

        return $mail;
    }
}
