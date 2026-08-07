<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Application $application)
    {
    }

    public function build()
    {
        return $this->subject("تم استلام طلبك رقم {$this->application->order_number} - بيخالي")
            ->view('emails.application-received')
            ->with(['application' => $this->application]);
    }
}
