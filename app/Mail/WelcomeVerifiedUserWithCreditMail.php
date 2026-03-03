<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeVerifiedUserWithCreditMail extends Mailable
{
    use SerializesModels;

    public $user;
    public $autoCreditAmount;
    public $currentBalance;
    public $verificationDate;

    /**
     * Create a new message instance.
     *
     * @param  User  $user
     * @param  float  $autoCreditAmount
     * @param  float  $currentBalance
     * @param  \Carbon\Carbon  $verificationDate
     * @return void
     */
    public function __construct($user, $autoCreditAmount = 100.00, $currentBalance = null, $verificationDate = null)
    {
        $this->user = $user;
        $this->autoCreditAmount = $autoCreditAmount;
        $this->currentBalance = $currentBalance ?? $autoCreditAmount;
        $this->verificationDate = $verificationDate ?? now();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('🎉 Welcome! Email Verified + Credits Added')
                    ->view('emails.welcome-verified-user-with-credit');
    }
}