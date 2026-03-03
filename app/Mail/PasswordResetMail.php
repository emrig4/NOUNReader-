<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $actionUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $actionUrl)
    {
        $this->user = $user;
        $this->actionUrl = $actionUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Password Reset - readprojecttopics')
                    ->view('emails.password-reset')
                    ->with([
                        'user' => $this->user,
                        'actionUrl' => $this->actionUrl,
                    ]);
    }
}