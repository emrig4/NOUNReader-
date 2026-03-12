<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SimpleBulkMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $emailSubject;
    public $emailMessage;
    public $senderName;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $subject, $message, $adminName = 'Admin')
    {
        // Safely extract user name
        $this->userName = $user->first_name ?? $user->email ?? 'User';
        $this->emailSubject = $subject;
        $this->emailMessage = $message;
        $this->senderName = $adminName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->emailSubject)
                    ->view('emails.simple-bulk-message');
    }
}