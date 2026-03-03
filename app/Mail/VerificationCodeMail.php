<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationCode;
    public $expiresAt;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $verificationCode, $expiresAt = null)
    {
        $this->user = $user;
        $this->verificationCode = $verificationCode;
        $this->expiresAt = $expiresAt ?? now()->addMinutes(15);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Verification Code - ' . config('app.name'))
                    ->markdown('emails.verification-code', [
                        'user' => $this->user,
                        'verificationCode' => $this->verificationCode,
                        'expiresAt' => $this->expiresAt
                    ]);
    }
}