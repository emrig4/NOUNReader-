<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReferralBonusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $referrer;
    public $bonusAmount;

    /**
     * Create a new message instance.
     */
    public function __construct(User $referrer, float $bonusAmount)
    {
        $this->referrer = $referrer;
        $this->bonusAmount = $bonusAmount;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('You Received a Referral Bonus! - ' . config('app.name'))
                    ->markdown('emails.referral-bonus', [
                        'referrer' => $this->referrer,
                        'bonusAmount' => $this->bonusAmount
                    ]);
    }
}