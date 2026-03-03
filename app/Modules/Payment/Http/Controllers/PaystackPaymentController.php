<?php

namespace App\Modules\Payment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Paystack;


class PaystackPaymentController extends Controller
{
    /**
   * Redirect the User to Paystack Payment Page
   * @return Url
   */
    public function redirectToGateway()
    {
        try{
            return Paystack::getAuthorizationUrl()->redirectNow();
        }catch(\Exception $e) {
            // return redirect()->back()->withMessage(['msg'=>'The paystack token has expired. Please refresh the page and try again.', 'type'=>'error']);
            dd($e);
        }        
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
      $paymentDetails = Paystack::getPaymentData();
      if($paymentDetails['status'] == 'true'){
        $paymentData = $paymentDetails['data'];
        $paymentStatus = $paymentDetails['status'];
        $paymentMeta = $paymentData['metadata'];
        $verifiedChargeAmount = $paymentData['amount'];
        $verifiedChargeCurrency = $paymentData['currency'];


        $txMeta = [];
        foreach ($paymentMeta as $value) {
            $txMeta[ $value['metaname'] ] = $value['metavalue'];
        }

        $purchasedEbookData = [
          'ebook_id' => $txMeta['ebook_id'],
          'customer_id' => '',
          'transaction_id' => '', // local reference
          'is_delivered' => 0,
        ];

        $userId = isset($txMeta['user_id']) ? $txMeta['user_id'] : null;

        $customerData = [
          'customer_type' => $txMeta['customer_type'],
          'user_id' => $userId,
          'paystack_account_id' =>$paymentData['customer']['id'],
          'phone' => $paymentData['customer']['phone'],
          'email' => $paymentData['customer']['email'],
          'name' =>  $paymentData['customer']['first_name'],
        ];

        $transactionData = [
          'status' => $paymentStatus,
          'reference_id' => $paymentData['reference'],
          'payment_type' => '',
          'payment_aggregator' => 'paystack',
          'amount' => ($paymentData['amount']/100),
          'transaction_payload' => json_encode($paymentDetails),
          'transaction_meta' => json_encode($txMeta),
          'customer_id' => '', // references local instance
        ];

       
        $customer = BookPurchasedTrait::storeCustomer($customerData);
        $transactionData['customer_id'] = $customer->id;
        $transaction = BookPurchasedTrait::storeTransaction($transactionData);

        $purchasedEbookData['transaction_id'] = $transaction->id;
        $purchasedEbookData['customer_id'] = $customer->id;
        $purchasedEbook = BookPurchasedTrait::storePurchasedEbook($purchasedEbookData);

        // current customer info  to session
        request()->session()->put('customer', $customerData);

        // send id of purchased ebook to successful page, 
        // return redirect()->route('ebooks.purchased', ['id'=> id_encode($txMeta['ebook_id']) ]); //->with($custmerData);
           return redirect()->action([\Modules\Ebook\Http\Controllers\EbookController::class, 'deliverFile'], [ 'id'=>id_encode($txMeta['ebook_id']) ]);
    
      } else{
        return 'An error occured';
      }
    }



    /**
     * Show page to finalize payment.
     * @param int $id
     * @return Renderable
     */
    public function show($slug)
    {
        //
    }
}
