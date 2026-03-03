<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WalletCreditMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $amount;
    public $type;
    public $balance_before;
    public $balance_after;

    public function __construct($user, $amount, $type, $balance_before, $balance_after)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->type = $type;
        $this->balance_before = $balance_before;
        $this->balance_after = $balance_after;
    }

    public function build()
    {
        return $this->subject('💰 Credits Added - Your Wallet Has Been Updated!')
                    ->view('emails.wallet-credit-email');
    }
}