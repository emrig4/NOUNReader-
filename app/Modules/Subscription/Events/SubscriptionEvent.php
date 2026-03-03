<?php

namespace App\Modules\Subscription\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;



class SubscriptionEvent
{
    use SerializesModels, Dispatchable, InteractsWithSockets, SerializesModels;

     public $subscription;
     public $type;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($subscription, $type)
    {
       $this->subscription = $subscription;
       $this->type = $type;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
