<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'New Hire a Writer Inquiry - ' . ($this->data['name'] ?? 'Unknown');

        return $this->subject($subject)
                    ->view('emails.contact-form-notification')
                    ->with([
                        'data' => $this->data
                    ]);
    }
}
