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

        if ($this->submission->document_path) {
            $mail->attach(storage_path('app/public/' . $this->submission->document_path), [
                'as' => $this->submission->document_name ?? 'document',
            ]);
        }

        return $mail;
    }
}
