<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordSetupMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        $resetUrl = url("/reset-password/{$this->user->id}?token=" . $this->token);
        return $this->subject('Set up your password')
                    ->view('emails.password-setup')
                    ->with([
                        'user' => $this->user,
                        'resetUrl' => $resetUrl,
                    ]);
    }
}
