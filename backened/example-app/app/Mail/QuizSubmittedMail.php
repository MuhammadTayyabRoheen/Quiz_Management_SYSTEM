<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuizSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $quizTitle;
    public $studentName;
    public $score;

    public function __construct($quizTitle, $studentName, $score)
    {
        $this->quizTitle = $quizTitle;
        $this->studentName = $studentName;
        $this->score = $score;
    }

    public function build()
    {
        return $this->subject('Quiz Submitted Successfully')
                    ->view('emails.quiz-submitted');
    }
}
