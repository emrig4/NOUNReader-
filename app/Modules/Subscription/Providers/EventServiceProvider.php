<?php

namespace App\Modules\Subscription\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Modules\Subscription\Listeners\PaystackWebhookEventListener;
use App\Modules\Subscription\Events\PaystackWebhookEvent;

use App\Modules\Subscription\Events\SubscriptionEvent;
use App\Modules\Subscription\Listeners\SubscriptionEventListener;



class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PaystackWebhookEvent::class => [
            PaystackWebhookEventListener::class
        ],

        SubscriptionEvent::class => [
            SubscriptionEventListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
