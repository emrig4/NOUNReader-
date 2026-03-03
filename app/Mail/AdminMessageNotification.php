<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $message;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $message, $user)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.admin-message')
                    ->with([
                        'user' => $this->user,
                        'messageContent' => $this->message,
                        'subject' => $this->subject
                    ]);
    }
}