<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RejectionNoticeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $student;

    /**
     * Create a new message instance.
     *
     * @param $student
     */
    public function __construct($student)
    {
        $this->student = $student;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Student Application was Rejected')
                    ->view('emails.student_rejection')
                    ->with(['studentName' => $this->student->name]);
    }
}
