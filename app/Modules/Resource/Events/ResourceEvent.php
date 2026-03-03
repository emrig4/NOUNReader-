<?php

namespace App\Modules\Resource\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;



class ResourceEvent
{
    use SerializesModels, Dispatchable, InteractsWithSockets, SerializesModels;

     public $resource;
     public $type;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($resource, $type)
    {
       $this->resource = $resource;
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
