<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WalletDeductionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $amount;
    public $balance_before;
    public $balance_after;

    public function __construct($user, $amount, $balance_before, $balance_after)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->balance_before = $balance_before;
        $this->balance_after = $balance_after;
    }

    public function build()
    {
        return $this->subject('💳 Credits Deducted - Transaction Confirmation')
                    ->view('emails.wallet-deduction-email');
    }
}