<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Application $application)
    {
    }

    public function build()
    {
        $statusLabel = Application::STATUS_LABELS_AR[$this->application->status] ?? $this->application->status;

        return $this->subject("تحديث حالة طلبك {$this->application->order_number} - بيخالي")
            ->view('emails.application-status-updated')
            ->with(['application' => $this->application, 'statusLabel' => $statusLabel]);
    }
}
