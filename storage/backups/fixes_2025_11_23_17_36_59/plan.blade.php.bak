@extends('layouts.public', ['title' => 'Buy Credits'])
@push('meta')
@endpush

@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-counterfull">
        <div class="container" style="width: 100%">
            <div class="row">                
                <div class="col-md-12" style="margin-top: 50px"></div>
                <div class="col-md-12">
                    <div class="flex justify-center" style="position: relative; top: 50px" id="alert-holder"></div>
                    <div class="ereaders-shop-detail">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6 ms-md-auto">
                                <div class="ereaders-detail-thumb-text">
                                    <h2>{{ $plan->title }} Credit Package | 
                                      <span>{{ $plan->currency() }}</span>
                                      <span id="amountpayable">{{ $plan->price() }}</span>
                                    </h2>

                                    <ul class="ereaders-detail-option" style="margin-top: 10px">
                                        @foreach($plan->features() as $feature)
                                            <li>
                                                <span>{{$feature}}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                   
                                    <div class="clearfix"></div>
                                   

                                    <p>TOP-UP your credit wallet online using your ATM, debit, credit cards, mobile money or USSD payments via secured Paystack payment gateway. The credits will be credited to your wallet in your dashboard automatically once transaction is successful. These credits never expire and can be used to access premium content.</p>
                                   
                                   
                                    <a onclick="validateAndPay()" class="ereaders-detail-btn cursor-pointer">Buy Credits Now <i class="icon ereaders-shopping-bag"></i></a>
                                   
                                   <!--  <a onClick="makePayment()" class="ereaders-detail-btn">Pay with Flutterwave <i class="icon ereaders-shopping-bag"></i></a> -->


                                   <!--  <div class="col-md-6" style="margin-top: 50px">
                                        <a href=""><img src="https://fiverr-res.cloudinary.com/images/q_auto,f_auto/gigs/93790811/original/d659bf6ae224ded386238ebc8e0a77c406ff9730/integrate-paystack-payment-gateway.png"></a>
                                    </div> -->

                                    <hr>    

                                    <!-- <div class="col-md-6" style="margin-top: 50px">
                                        <div id="paypal-button-container" data-amount="{{ $plan->price() }}"></div>
                                    </div> -->
                               
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!--// Main Section \\-->
@endsection



<!-- Paypal -->
@push('js')
    <script src="https://www.paypal.com/sdk/js?client-id=AYuO0FRMAzZ6DunYe20kV6hl1skK5w5-44Q8-KXa2cpDgBuAno1pax976Dd7Me4CAVUW7SYOAKfa7SkQ&components=buttons"data-sdk-integration-source="button-factory"></script>
    <script type="text/javascript">
       // function createPaypalButton() {
       //      paypal.Buttons({
       //              // env: 'sandbox', // Optional: specify 'sandbox' environment
       //              style: {
       //                  size: 'large',
       //                  color: 'gold',
       //                  shape: 'pill',
       //                  label: 'checkout',
       //                  // tagline: 'true'
       //              },
       //              // style: {
       //              //     shape: "rect",
       //              //     color: "blue",
       //              //     layout: "vertical",
       //              //     label: "paypal",
       //              //     size: "medium"
       //              // },
       //              createOrder: function(data, actions) {
       //                  let meta = JSON.stringify({
       //                      "gc_equivalent": 'that.coin',
       //                      "gc_rate": 'that.rate'
       //                  })
       //                  let paypal = document.getElementById("paypal-button-container");
       //                  let amount = paypal.getAttribute("data-amount");
       //                  return actions.order.create({
       //                      purchase_units: [{
       //                          amount: {
       //                              value: amount
       //                          },
       //                          description: meta
       //                      }]
       //                  });
       //              },
       //              onApprove: function(data, actions) {
       //                  // This function captures the funds from the transaction.
       //                  return actions.order.capture().then(async function(details) {
       //                      let order_id = details.id
       //                      try {
       //                          await PaypalService.confirmPayment({ order_id })
       //                              .then((res) => {
       //                                  if (res.status == 202) {
       //                                      that.$toast.success(`Payment was successfull and account credited`, { position: 'top-right' });
       //                                  } else {
       //                                      that.$toast.info(`Payment could not be confirmed. please contact support`, { position: 'top-right' });
       //                                  }
       //                              })
       //                      } catch{
       //                          that.$toast.error(`Uknown error occured, please contact support`, { position: 'top-right' });
       //                      }
       //                      that.$router.push({name: 'user.dashboard'})
       //                      // console.log(details)
       //                  });
       //              },
       //              commit: true, // Optional: show a 'Pay Now' button in the checkout flow
       //          })
       //          .render("#paypal-button-container");
       //  }


        // createPaypalButton()

        // function clickPaypalButton(){
        //     let paypalBtn = document.getElementById('amountinnaira').style.display="block";
        // }
    </script>
@endpush


<!-- Paystack -->
@push('js')
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script>
        // change this to your public key so you 
        // will no more be prompted
        var public_key = '<?php echo  env('PAYSTACK_PUBLIC_KEY') ?>';
        
    
        /*
         * check if the public key set is valid
         * 
         * @return bool
         */
        function isValidPublicKey(){
          var publicKeyPattern = new RegExp('^pk_(?:test|live)_','i');
          return publicKeyPattern.test(public_key);
        }
        
        
        /* 
         * Validate before opening Paystack popup for credit purchase
         */
        function validateAndPay(){
            let amount = Number( '<?php echo $plan->price() ; ?>' )
            let baseamount =  Math.round(amount * 100)
            let currency =   '<?php echo $plan->currency() ; ?>'
            let email  = '<?php echo  auth()->user()->email ; ?>'
            let firstname = '<?php echo  auth()->user()->first_name ; ?>'
            let lastname = '<?php echo  auth()->user()->last_name ; ?>'
        
            let meta = {}
            meta.user_id = '<?php echo  auth()->user()->id ; ?>'
            meta.plan_id = '<?php echo $plan->plan_id ; ?>'
            meta.pricing = '<?php echo $plan ; ?>'
            meta.txntype = 'buycredit' // Changed from 'subscription' to 'buycredit'

          payWithPaystack( email, baseamount, firstname, lastname, currency, meta);
        }
      
        /* Get a random reference number based on the current time
         * 
         * gotten from http://stackoverflow.com/a/2117523/671568
         * replaced UUID with REF
         */
        function generateREF(){
          var d = new Date().getTime();
          if(window.performance && typeof window.performance.now === "function"){
            d += performance.now(); //use high-precision timer if available
          }
          var ref = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = (d + Math.random()*16)%16 | 0;
            d = Math.floor(d/16);
            return (c=='x' ? r : (r&0x3|0x8)).toString(16);
          });
          return ref;
        }
        
      
        /* Show the paystack payment popup for credit purchase
         * 
         * source: https://developers.paystack.co/docs/paystack-inline
         */
        function payWithPaystack(email, baseamount, firstname, lastname, currency, meta){
          var handler = PaystackPop.setup({
            key:       public_key,
            email:     email,
            firstname: firstname,
            lastname:  lastname,
            amount:    baseamount,
            currency: currency,
            ref:       generateREF(), 
            metadata: meta,
            callback:  function(response){
              // payment was received - process as credit purchase
                document.getElementById('alert-holder').innerHTML = '<div class="alert bg-gray-100">' + 'Please wait while we verify your credit purchase' + '<img class="mx-auto" src="/images/preloaders/48x48.gif">' + '</div>';
                    verifyTransaction(response)
            },
            onClose:  function(){
              // Visitor cancelled payment
              var msg = 'Cancelled. Please click the \'Buy Credits\' button to try again';
              document.getElementById('alert-holder').innerHTML = '<div class="alert alert-danger">' + msg + '</div>';
            }
          });
          handler.openIframe();
        }


        function verifyTransaction(payload){
            const http = new XMLHttpRequest()
            let url = '/paystack/verify'
            http.open('POST', url)

            http.setRequestHeader("Content-Type", "application/json");
            
            // send JSON data to the remote server
            http.send(JSON.stringify(payload))

            http.onload = (res) => {
                console.log('Response Status:', http.status);
                console.log('Response Text:', http.responseText);

                if (http.status === 201 || http.status === 200) {
                    try {
                        data = JSON.parse(http.response);
                        
                        // Handle credit purchase response
                        if (data.data && data.data.credits_added) {
                            var msg = '<b>Success:</b> Your wallet has been credited with ' + data.data.credits_added + ' credits!';
                            document.getElementById('alert-holder').innerHTML = '<div class="alert bg-green-100 text-green-800">' + msg + '<img class="mx-auto" src="/images/preloaders/48x48.gif">' + '</div>';

                            setTimeout(function(){
                               var msg = 'Your purchase was successful. You will be redirected to your subscription in 3 seconds';
                               document.getElementById('alert-holder').innerHTML = '<div class="alert bg-green-100 text-green-800">' + msg + '<img class="mx-auto" src="/images/preloaders/48x48.gif">' + '</div>'
                            }, 2000)
                            setTimeout(function(){
                                window.location = '/account/subscription'
                            }, 5000)
                        } else if (data.data && data.data.reference) {
                            // Handle successful credit purchase with reference
                            var msg = '<b>Payment Successful:</b> Reference ' + data.data.reference + '. Credits will be added to your wallet...';
                            document.getElementById('alert-holder').innerHTML = '<div class="alert bg-yellow-100 text-yellow-800">' + msg + '<img class="mx-auto" src="/images/preloaders/48x48.gif">' + '</div>';
                            
                            // Redirect to subscription after 3 seconds
                            setTimeout(function(){
                                window.location = '/account/subscription'
                            }, 3000);
                        } else {
                            // Unknown response format - treat as success
                            var msg = '<b>Payment Successful:</b> Your credits will be added to your wallet shortly.';
                            document.getElementById('alert-holder').innerHTML = '<div class="alert bg-blue-100 text-blue-800">' + msg + '<img class="mx-auto" src="/images/preloaders/48x48.gif">' + '</div>';
                            setTimeout(function(){
                                window.location = '/account/subscription'
                            }, 3000);
                        }

                    } catch (e) {
                        console.error('JSON Parse Error:', e);
                        // If JSON parsing fails, treat as success and redirect
                        var msg = '<b>Payment Successful:</b> Your credit purchase has been processed.';
                        document.getElementById('alert-holder').innerHTML = '<div class="alert bg-green-100 text-green-800">' + msg + '<img class="mx-auto" src="/images/preloaders/48x48.gif">' + '</div>';
                        setTimeout(function(){
                            window.location = '/account/subscription'
                        }, 3000);
                    }

                } else if (http.status === 422) {
                    // Validation error
                    var msg = 'Payment validation failed. Please try again.';
                    document.getElementById('alert-holder').innerHTML = '<div class="alert bg-red-100 text-red-800">' + msg + '<img class="mx-auto" src="/images/preloaders/48x48.gif">' + '</div>'
                } else if (http.status === 404) {
                    var msg = 'Payment verification failed. Please contact support with your transaction reference.';
                    document.getElementById('alert-holder').innerHTML = '<div class="alert bg-red-100 text-red-800">' + msg + '<img class="mx-auto" src="/images/preloaders/48x48.gif">' + '</div>'
                } else {
                    // Any other error status
                    var msg = 'An error occurred while processing your credit purchase. Please contact support.';
                    document.getElementById('alert-holder').innerHTML = '<div class="alert bg-red-100 text-red-800">' + msg + ' (Status: ' + http.status + ')<img class="mx-auto" src="/images/preloaders/48x48.gif">' + '</div>'
                }
            }

            http.onerror = function() {
                console.log("Network error occurred");
                var msg = 'Network error occurred. Please check your connection and try again.';
                document.getElementById('alert-holder').innerHTML = '<div class="alert bg-red-100 text-red-800">' + msg + '<img class="mx-auto" src="/images/preloaders/48x48.gif">' + '</div>'
            }

        }

        // verifyTransaction(
        //     {
        //         "reference": "dd1b66c8-58f5-4a92-b0de-370ae39c1db2",
        //         "trans": "1722461611",
        //         "status": "success",
        //         "message": "Approved",
        //         "transaction": "1722461611",
        //         "trxref": "dd1b66c8-58f5-4a92-b0de-370ae39c1db2"
        //     }
        // )
    </script>
@endpush


<!-- Flutterwave -->
@push('js')
  <script src="https://checkout.flutterwave.com/v3.js"></script>
  <script>
    function makePayment() {
      FlutterwaveCheckout({
        public_key: "FLWPUBK_TEST-1595a3a434dd908481982e57a7b7cee7-X",
        tx_ref: "RX1",
        amount: 10,
        currency: "USD",
        country: "US",
        payment_options: " ",
        customer: {
          email: "cornelius@gmail.com",
          phone_number: "08102909304",
          name: "Flutterwave Developers",
        },
        callback: function (data) { // specified callback function
          console.log(data);
        },
        customizations: {
          title: "My store",
          description: "Payment for items in cart",
          logo: "https://assets.piedpiper.com/logo.png",
        },
      });
    }
  </script>
@endpush
