<?php

namespace App\Modules\Resource\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Modules\Resource\Events\ResourceEvent;
use Spatie\SlackAlerts\Facades\SlackAlert;
use App\Modules\Resource\Notifications\ResourceNotification;
use Illuminate\Support\Facades\Notification;





class ResourceEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ResourceEvent $event)
    {

      $resource = $event->resource;
      $eventType = $event->type;
      
      
      $user = $resource->user;

      $body = $eventType;
      if($eventType == 'published'){
        $body = 'Your Resource on readprojecttopics has been published, login to our application to view details';
      }
      
      if($eventType == 'reviewed'){
        $body = 'Your Resource on readprojecttopics has recieved a review, login to our application to view details';
      }

      if($eventType == 'cited'){
        $body = 'Your Resource on readprojecttopics has been cited, login to our application to view details';
      }

      $notifyData = [
          'subject' => 'Resource Notification',
          'greeting' => $user->name,
          'body' => $body,
          'url' => url('https://projectandmaterials.com/resources/' . $resource->slug),
          'entity' => 'Resource',
          'entity_id' => $resource->id,
          'thanks' => 'Thank you for using our application!',
      ];

      $when = now()->addMinutes(0);
      $user->notify( (new ResourceNotification($notifyData))->delay($when) );  

    }


}



