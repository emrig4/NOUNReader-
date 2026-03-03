<?php

namespace App\Modules\Payment\Http\Traits;

use App\Models\User;
use Illuminate\Support\Carbon;
use App\Modules\Payment\Models\Payment;

trait PaymentTrait
{


    
    /**
     * Create Subscription for user.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
    */
    public static function createPayment($payload)
    {
        return Payment::updateOrCreate(
            ['reference' => $payload['reference'] ],
            $payload
        );
    }

}
