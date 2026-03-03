<?php

namespace App\Modules\Subscription\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Modules\Subscription\Events\PaystackWebhookEvent;
use Spatie\SlackAlerts\Facades\SlackAlert;
use App\Facades\SubscriptionControllerFacade;
use App\Modules\Subscription\Http\Traits\SubscriptionTrait;




class PaystackWebhookEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        // subscription events [subscription.create, invoice.create, charge.success, invoice.payment_failed, invoice.update, subscription.not_renew, subscription.disable]
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PaystackWebhookEvent $event)
    {
        // Log::emergency($event->data);
        // Log::alert($event->data);

        if($event->data){
          $payload = $event->data;
          $eventName  = $payload['event'];  // charge.succesjs, invoice.create,
          $eventData = $payload['data'];


          switch ($eventName) {
            case 'charge.success' :
              if($eventData && $eventData['plan'] && $eventData['plan']['id']){
                  // charge.success  - on subscription invoice 
                 $this->handleSubscriptionPaid($eventData);
               }else{
                 $this->handleChargeSuccess($eventData);
               }
              break;
            case 'subscription.create':
               $this->handleSubscriptionCreated($eventData);
              break;

            case 'subscription.disable':
               $this->handleSubscriptionDisabled($eventData);
              break;

            case 'subscription.not_renew':
               $this->handleSubscriptionNotRenewing($eventData);
              break;

            case 'invoice.payment_failed':
              $this->handleInvoicePaymentFailed($eventData);
              break;

            case 'invoice.update':
              $this->handleInvoiceUpdated($eventData);
              break;

            case 'invoice.create':
              $this->handleInvoiceCreated($eventData);
              break;

            default:
              // code...
              break;
          }
          
        }
        

        // $payload = json_encode($event->data);
        // SlackAlert::to('subscription')->message(
        //     [
        //         'channel' => 'notify',
        //         'icon_emoji' => ':robot_face:',
        //         'type' => 'mrkdown',

        //         'username' => 'readprojecttopics',
        //         'text' =>  "Webhook event from paystack! {$payload}"

        //     ]
        // );

    }

    public function handleChargeSuccess($payload)
    {

      $data = json_encode($payload);
      SlackAlert::to('subscription')->message(
            [
                'channel' => 'subscription',
                'icon_emoji' => ':robot_face:',
                'type' => 'mrkdown',

                'username' => 'readprojecttopics [NO ACTION]',
                'text' =>  "Handle handleChargeSuccess Charge on paystack {$data}"

            ]
        );
      
    } 

    public function handleSubscriptionPaid($payload)
    {
      
      
      $plan  = (object) $payload['plan'];
      $customer = (object) $payload['customer'];
      $subscription =  SubscriptionTrait::storeSubscription($plan, $customer);
      $data = json_encode($payload);

      SlackAlert::to('subscription')->message(
        [
            'channel' => 'subscription',
            'icon_emoji' => ':robot_face:',
            'type' => 'mrkdown',

            'username' => 'readprojecttopics',
            'text' =>  "handleSubscriptionPaid invoice paid! {$data}"

        ]
      ); 

    }

    public function handleSubscriptionCreated($payload)
    {
      $plan  = json_encode($payload['plan']);
      $customer = json_encode($payload['customer']);

      // handle directly via sub page
      // $subscription =  SubscriptionTrait::storeSubscription($plan, $customer);
      // $data = $subscription;

      SlackAlert::to('subscription')->message(
        [
            'channel' => 'subscription',
            'icon_emoji' => ':robot_face:',
            'type' => 'mrkdown',

            'username' => 'readprojecttopics',
            'text' =>  "handleSubscriptionCreated new sub created [NO ACTION] {$customer}, {$plan}"

        ]
      ); 
    }

    public function handleSubscriptionDisabled($payload)
    {

      $data = json_encode($payload);

      $plan  = (object) $payload['plan'];
      $customer = (object) $payload['customer'];
      $subscription =  SubscriptionTrait::storeSubscription($plan, $customer);
      
      SlackAlert::to('subscription')->message(
            [
                'channel' => 'subscription',
                'icon_emoji' => ':robot_face:',
                'type' => 'mrkdown',

                'username' => 'readprojecttopics',
                'text' =>  "Handle handleSubscriptionDisabled on paystack [UPDATE SUB] {$data}"

            ]
        );
      
    }

    public function handleSubscriptionNotRenewing($payload)
    {

      $data = json_encode($payload);
      $plan  = (object) $payload['plan'];
      $customer = (object) $payload['customer'];
      $subscription =  SubscriptionTrait::storeSubscription($plan, $customer);

      SlackAlert::to('subscription')->message(
            [
                'channel' => 'subscription',
                'icon_emoji' => ':robot_face:',
                'type' => 'mrkdown',

                'username' => 'readprojecttopics',
                'text' =>  "Handle handleSubscriptionNotRenewing on paystack [UPDATE SUB] {$data} "

            ]
        );
    }

    public function handleInvoiceCreated($payload)
    {
      $data = json_encode($payload);
      SlackAlert::to('subscription')->message(
            [
                'channel' => 'subscription',
                'icon_emoji' => ':robot_face:',
                'type' => 'mrkdown',

                'username' => 'readprojecttopics',
                'text' =>  "Handle handleInvoiceCreated on paystack [NO ACTION]  {$data}"

            ]
        );
    }

    public function handleInvoiceUpdated($payload)
    {
      $data = json_encode($payload);
      SlackAlert::to('subscription')->message(
            [
                'channel' => 'subscription',
                'icon_emoji' => ':robot_face:',
                'type' => 'mrkdown',

                'username' => 'readprojecttopics',
                'text' =>  "Handle handleInvoiceUpdated on paystack [NO ACTION] {$data}"

            ]
        );
    }

    public function handleInvoicePaymentFailed($payload)
    {
      $data = json_encode($payload);
      SlackAlert::to('subscription')->message(
            [
                'channel' => 'subscription',
                'icon_emoji' => ':robot_face:',
                'type' => 'mrkdown',

                'username' => 'readprojecttopics',
                'text' =>  "Handle handleInvoicePaymentFailed on paystack [NO ACTION] {$data}"

            ]
        );
      
    }

}




/*  

// invoice.create

{
  "event": "invoice.create",
  "data": {
    "domain": "test",
    "invoice_code": "INV_thy2vkmirn2urwv",
    "amount": 50000,
    "period_start": "2018-12-20T15:00:00.000Z",
    "period_end": "2018-12-19T23:59:59.000Z",
    "status": "success",
    "paid": true,
    "paid_at": "2018-12-20T15:00:06.000Z",
    "description": null,
    "authorization": {
      "authorization_code": "AUTH_9246d0h9kl",
      "bin": "408408",
      "last4": "4081",
      "exp_month": "12",
      "exp_year": "2020",
      "channel": "card",
      "card_type": "visa DEBIT",
      "bank": "Test Bank",
      "country_code": "NG",
      "brand": "visa",
      "reusable": true,
      "signature": "SIG_iCw3p0rsG7LUiQwlsR3t",
      "account_name": "BoJack Horseman"
    },
    "subscription": {
      "status": "active",
      "subscription_code": "SUB_fq7dbe8tju0i1v8",
      "email_token": "3a1h7bcu8zxhm8k",
      "amount": 50000,
      "cron_expression": "0 * * * *",
      "next_payment_date": "2018-12-20T00:00:00.000Z",
      "open_invoice": null
    },
    "customer": {
      "id": 46,
      "first_name": "Asample",
      "last_name": "Personpaying",
      "email": "asam@ple.com",
      "customer_code": "CUS_00w4ath3e2ukno4",
      "phone": "",
      "metadata": null,
      "risk_action": "default"
    },
    "transaction": {
      "reference": "9cfbae6e-bbf3-5b41-8aef-d72c1a17650g",
      "status": "success",
      "amount": 50000,
      "currency": "NGN"
    },
    "created_at": "2018-12-20T15:00:02.000Z"
  }
}


*/



/*

// invoice.payment_failed
{
  "event": "invoice.payment_failed",
  "data": {
   "domain": "test",
   "invoice_code": "INV_3kfmqw48ca7b48k",
   "amount": 10000,
   "period_start": "2019-03-25T14:00:00.000Z",
   "period_end": "2019-03-24T23:59:59.000Z",
   "status": "pending",
   "paid": false,
   "paid_at": null,
   "description": null,
   "authorization": {
     "authorization_code": "AUTH_fmmpvpvphp",
     "bin": "506066",
     "last4": "6666",
     "exp_month": "03",
     "exp_year": "2033",
     "channel": "card",
     "card_type": "verve ",
     "bank": "TEST BANK",
     "country_code": "NG",
     "brand": "verve",
     "reusable": true,
     "signature": "SIG_bx0C6uIiqFHnoGOxTDWr",
     "account_name": "BoJack Horseman"
   },
   "subscription": {
     "status": "active",
     "subscription_code": "SUB_f7ct8g01mtcjf78",
     "email_token": "gptk4apuohyyjsg",
     "amount": 10000,
     "cron_expression": "0 * * * *",
     "next_payment_date": "2019-03-25T00:00:00.000Z",
     "open_invoice": "INV_3kfmqw48ca7b48k"
   },
   "customer": {
     "id": 6910995,
     "first_name": null,
     "last_name": null,
     "email": "xxx@gmail.com",
     "customer_code": "CUS_3p3ylxyf07605kx",
     "phone": null,
     "metadata": null,
     "risk_action": "default"
   },
   "transaction": {},
   "created_at": "2019-03-25T14:00:03.000Z"
  }
}

*/

/*

{
  "event": "invoice.update",
  "data": {
    "domain": "test",
    "invoice_code": "INV_kmhuaaur5c9ruh2",
    "amount": 50000,
    "period_start": "2016-04-19T07:00:00.000Z",
    "period_end": "2016-05-19T07:00:00.000Z",
    "status": "success",
    "paid": true,
    "paid_at": "2016-04-19T06:00:09.000Z",
    "description": null,
    "authorization": {
      "authorization_code": "AUTH_jhbldlt1",
      "bin": "539923",
      "last4": "2071",
      "exp_month": "10",
      "exp_year": "2017",
      "card_type": "MASTERCARD DEBIT",
      "bank": "FIRST BANK OF NIGERIA PLC",
      "country_code": "NG",
      "brand": "MASTERCARD",
      "account_name": "BoJack Horseman"
    },
    "subscription": {
      "status": "active",
      "subscription_code": "SUB_l07i1s6s39nmytr",
      "amount": 50000,
      "cron_expression": "0 0 19 * *",
      "next_payment_date": "2016-05-19T07:00:00.000Z",
      "open_invoice": null
    },
    "customer": {
      "first_name": "BoJack",
      "last_name": "Horseman",
      "email": "bojack@horsinaround.com",
      "customer_code": "CUS_xnxdt6s1zg1f4nx",
      "phone": "",
      "metadata": {},
      "risk_action": "default"
    },
    "transaction": {
      "reference": "rdtmivs7zf",
      "status": "success",
      "amount": 50000,
      "currency": "NGN"
    },
    "created_at": "2016-04-16T13:45:03.000Z"
  }
}

*/



/*





{
  "event": "subscription.disable",
  "data": {
    "domain": "test",
    "status": "complete",
    "subscription_code": "SUB_vsyqdmlzble3uii",
    "email_token": "ctt824k16n34u69",
    "amount": 300000,
    "cron_expression": "0 * * * *",
    "next_payment_date": "2020-11-26T15:00:00.000Z",
    "open_invoice": null,
    "plan": {
        "id": 67572,
        "name": "Monthly retainer",
        "plan_code": "PLN_gx2wn530m0i3w3m",
        "description": null,
        "amount": 50000,
        "interval": "monthly",
        "send_invoices": true,
        "send_sms": true,
        "currency": "NGN"
    },
    "authorization": {
      "authorization_code": "AUTH_96xphygz",
      "bin": "539983",
      "last4": "7357",
      "exp_month": "10",
      "exp_year": "2017",
      "card_type": "MASTERCARD DEBIT",
      "bank": "GTBANK",
      "country_code": "NG",
      "brand": "MASTERCARD",
      "account_name": "BoJack Horseman"
    },
    "customer": {
      "first_name": "BoJack",
      "last_name": "Horseman",
      "email": "bojack@horsinaround.com",
      "customer_code": "CUS_xnxdt6s1zg1f4nx",
      "phone": "",
      "metadata": {},
      "risk_action": "default"
    },
    "created_at": "2020-11-26T14:45:06.000Z"
  }
}


{
  "event": "subscription.not_renew",
  "data": {
    "id": 317617,
    "domain": "test",
    "status": "non-renewing",
    "subscription_code": "SUB_d638sdiWAio7jnl",
    "email_token": "086x99rmqc4qhcw",
    "amount": 120000,
    "cron_expression": "0 0 8 10 *",
    "next_payment_date": null,
    "open_invoice": null,
    "integration": 116430,
    "plan": {
      "id": 103028,
      "name": "(1,200) - annually - [1 - Year]",
      "plan_code": "PLN_tlknnnzfi4w2evu",
      "description": "Subscription not_renewed for sub@notrenew.com",
      "amount": 120000,
      "interval": "annually",
      "send_invoices": true,
      "send_sms": true,
      "currency": "NGN"
    },
    "authorization": {
      "authorization_code": "AUTH_5ftfl9xrl0",
      "bin": "424242",
      "last4": "4081",
      "exp_month": "06",
      "exp_year": "2023",
      "channel": "card",
      "card_type": "mastercard debit",
      "bank": "Guaranty Trust Bank",
      "country_code": "NG",
      "brand": "mastercard",
      "reusable": true,
      "signature": "SIG_biPYZE4PgDCQUJMIT4sE",
      "account_name": null
    },
    "customer": {
      "id": 57199167,
      "first_name": null,
      "last_name": null,
      "email": "sub@notrenew.com",
      "customer_code": "CUS_8gbmdpvn12c67ix",
      "phone": null,
      "metadata": null,
      "risk_action": "default",
      "international_format_phone": null
    },
    "invoices": [],
    "invoices_history": [],
    "invoice_limit": 0,
    "split_code": null,
    "most_recent_invoice": null,
    "created_at": "2021-10-08T14:50:39.000Z"
  }
}

*/


/*
// charge.succes


{  
  "event":"charge.success",
  "data": {  
    "id":302961,
    "domain":"live",
    "status":"success",
    "reference":"qTPrJoy9Bx",
    "amount":10000,
    "message":null,
    "gateway_response":"Approved by Financial Institution",
    "paid_at":"2016-09-30T21:10:19.000Z",
    "created_at":"2016-09-30T21:09:56.000Z",
    "channel":"card",
    "currency":"NGN",
    "ip_address":"41.242.49.37",
    "metadata":0,
    "log":{  
      "time_spent":16,
      "attempts":1,
      "authentication":"pin",
      "errors":0,
      "success":false,
      "mobile":false,
      "input":[],
      "channel":null,
      "history":[  
        {  
          "type":"input",
          "message":"Filled these fields: card number, card expiry, card cvv",
          "time":15
        },
        {  
          "type":"action",
          "message":"Attempted to pay",
          "time":15
        },
        {  
          "type":"auth",
          "message":"Authentication Required: pin",
          "time":16
        }
      ]
    },
    "fees":null,
    "customer":{  
      "id":68324,
      "first_name":"BoJack",
      "last_name":"Horseman",
      "email":"bojack@horseman.com",
      "customer_code":"CUS_qo38as2hpsgk2r0",
      "phone":null,
      "metadata":null,
      "risk_action":"default"
    },
    "authorization":{  
      "authorization_code":"AUTH_f5rnfq9p",
      "bin":"539999",
      "last4":"8877",
      "exp_month":"08",
      "exp_year":"2020",
      "card_type":"mastercard DEBIT",
      "bank":"Guaranty Trust Bank",
      "country_code":"NG",
      "brand":"mastercard",
      "account_name": "BoJack Horseman"
    },
    "plan":{}
  } 
}




{
  "event": "subscription.create",
  "data": {
    "domain": "test",
    "status": "active",
    "subscription_code": "SUB_vsyqdmlzble3uii",
    "amount": 50000,
    "cron_expression": "0 0 28 * *",
    "next_payment_date": "2016-05-19T07:00:00.000Z",
    "open_invoice": null,
    "createdAt": "2016-03-20T00:23:24.000Z",
    "plan": {
      "name": "Monthly retainer",
      "plan_code": "PLN_gx2wn530m0i3w3m",
      "description": null,
      "amount": 50000,
      "interval": "monthly",
      "send_invoices": true,
      "send_sms": true,
      "currency": "NGN"
    },
    "authorization": {
      "authorization_code": "AUTH_96xphygz",
      "bin": "539983",
      "last4": "7357",
      "exp_month": "10",
      "exp_year": "2017",
      "card_type": "MASTERCARD DEBIT",
      "bank": "GTBANK",
      "country_code": "NG",
      "brand": "MASTERCARD",
      "account_name": "BoJack Horseman"
    },
    "customer": {
      "first_name": "BoJack",
      "last_name": "Horseman",
      "email": "bojack@horsinaround.com",
      "customer_code": "CUS_xnxdt6s1zg1f4nx",
      "phone": "",
      "metadata": {},
      "risk_action": "default"
    },
    "created_at": "2016-10-01T10:59:59.000Z"
  }
}



// charge.success  - on subscription invoice 
{
    "event": "charge.success",
    "data": {
        "id": 1740676579,
        "domain": "test",
        "status": "success",
        "reference": "16460e91-e5fa-4ebc-9f73-f9f16e520969",
        "amount": 200000,
        "message": null,
        "gateway_response": "Approved",
        "paid_at": "2022-04-09T20:00:09.000Z",
        "created_at": "2022-04-09T20:00:07.000Z",
        "channel": "card",
        "currency": "NGN",
        "ip_address": null,
        "metadata": {
            "invoice_action": "create"
        },
        "fees_breakdown": null,
        "log": null,
        "fees": 3000,
        "fees_split": null,
        "authorization": {
            "authorization_code": "AUTH_gqxnxc70fi",
            "bin": "408408",
            "last4": "4081",
            "exp_month": "12",
            "exp_year": "2030",
            "channel": "card",
            "card_type": "visa",
            "bank": "TEST BANK",
            "country_code": "NG",
            "brand": "visa",
            "reusable": true,
            "signature": "SIG_XUuKb1r5MZaoscUey6Ra",
            "account_name": null,
            "receiver_bank_account_number": null,
            "receiver_bank": null
        },
        "customer": {
            "id": 22179939,
            "first_name": "Aniebiet",
            "last_name": "Aaron",
            "email": "airondev@gmail.com",
            "customer_code": "CUS_1pb4w230algy350",
            "phone": null,
            "metadata": null,
            "risk_action": "default",
            "international_format_phone": null
        },
        "plan": {
            "id": 209914,
            "name": "BASIC",
            "plan_code": "PLN_eohnjyhr4zvlyyd",
            "description": "readprojecttopics Basic Plan - Upto 10 Reads5 Access to Downloads20 Plagiarism",
            "amount": 200000,
            "interval": "hourly",
            "send_invoices": 1,
            "send_sms": 1,
            "currency": "NGN"
        },
        "subaccount": [],
        "split": [],
        "order_id": null,
        "paidAt": "2022-04-09T20:00:09.000Z",
        "requested_amount": 200000,
        "pos_transaction_data": null,
        "source": {
            "type": "api",
            "source": "merchant_api",
            "entry_point": "charge",
            "identifier": null
        }
    }
}


*/


