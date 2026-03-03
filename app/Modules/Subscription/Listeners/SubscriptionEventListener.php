<?php

namespace App\Modules\Subscription\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Modules\Subscription\Events\SubscriptionEvent;
use Spatie\SlackAlerts\Facades\SlackAlert;
use App\Facades\SubscriptionControllerFacade;
use App\Modules\Subscription\Http\Traits\SubscriptionTrait;

use App\Modules\Subscription\Notifications\SubscriptionNotification;
use Illuminate\Support\Facades\Notification;





class SubscriptionEventListener
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
    public function handle(SubscriptionEvent $event)
    {

      $subscription = $event->subscription;
      $eventType = $event->type;
      // dd($event);
      
      $user = $subscription->user;

      $body = $eventType;
      if($eventType == 'new'){
        $body = 'Your subscription on readprojecttopics is now active';
      }
      if($eventType == 'renew'){
        $body = 'Your subscription on readprojecttopics has been renewed successfully';
      }
      if($eventType == 'cancelled'){
        $body = 'Your subscription on readprojecttopics has been cancelled successfully';
      }
      if($eventType == 'cancelled'){
        $body = 'Your subscription on readprojecttopics has been cancelled successfully';
      }

      $notifyData = [
          'subject' => 'Subscription Notification',
          'greeting' => $user->name,
          'body' => $body,
          'url' => url('https://projectandmaterials.com'),
          'entity' => 'PaystackSubscription',
          'entity_id' => $subscription->id,
          'thanks' => 'Thank you for using our application!',
      ];

      $when = now()->addMinutes(2);
      // Notification::send($user, (new SubscriptionNotification($notifyData))->delay($when)  );
      
      $user->notify( (new SubscriptionNotification($notifyData))->delay($when) );  

    }


}



