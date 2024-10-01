<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StudentSubmissionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $submission;

    public function __construct($submission)
    {
        $this->submission = $submission;
    }

    public function build()
    {
        return $this->subject('Your Submission is Received')
                    ->view('emails.student-submission')
                    ->with(['submission' => $this->submission]);
    }
}
