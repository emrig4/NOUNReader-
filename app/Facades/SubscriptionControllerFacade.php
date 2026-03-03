<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Modules\Subscription\Http\Controllers\SubscriptionController;

class SubscriptionControllerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SubscriptionController::class;
    }
}
