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
    protected $isVerification;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $actionUrl, $isVerification = false)
    {
        $this->user = $user;
        $this->actionUrl = $actionUrl;
        $this->isVerification = $isVerification;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Determine subject based on whether this is a verification or password reset
        $subject = $this->isVerification 
            ? 'Verify Your Email - readprojecttopics' 
            : 'Password Reset - readprojecttopics';

        return $this->subject($subject)
                    ->view('emails.password-reset')
                    ->with([
                        'user' => $this->user,
                        'actionUrl' => $this->actionUrl,
                        'isVerification' => $this->isVerification,
                    ]);
    }
}