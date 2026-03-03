<!-- Paystack Button - For logged in users, direct purchase --> 
    <!-- Meta Payload -->
    @php
        $array = [
            ['metaname' => 'item', 'metavalue' => $item ],
            
        ];
    @endphp
      
<!--     <div class="">
        <form class="cart-plus-minus" role="form" method="POST" action="{{ route('paystack.pay') }}" id="paymentForm" >
            <div class="">
                <input type="hidden" name="amount" value="{{ '45' }}" />
                <input type="hidden" name="country" value="NG" /> 
                <input type="hidden" name="currency" value="{{ 'NGN' }}" />
                <input type="hidden" name="email" value="{{ auth()->user()->email }}" />
                <input type="hidden" name="metadata" value="{{ json_encode($array) }}" >
                <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}"> 
                <input type="hidden" name="callback_url" value="{{ route('paystack.callback') }}">
                <input type="hidden" name="phone" value="" /> 
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                {{ csrf_field() }}
            </div>

            <button  class="btn btn-primary" type="submit" value="Pay Now!">
                <i class="bg-green mr25 fa fa-download" aria-hidden="true" ></i>
                <span>Pay</span>
            </button>

        </form>
    </div>
 -->


<!DOCTYPE html>
<html>
<head>
  <title>Provide email and Pay Now</title>
  <link href="https://www.filepicker.io/api/file/Lh5PgMCTrKBCvUNRhSKy" rel="shortcut icon" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet" />
</head>
<body onload="startUp()">
  <div class="container">
    <div class="jumbotron">
      <h1 class="display-3">Paystack inline sample</h1>
      <form class="form" id="pay-form">
        <div id="alert-holder"></div>

        <fieldset class="form-group row">
          <div class="col-sm-offset-3 col-sm-9">
            <button class="btn btn-secondary" type="button" onclick="validateAndPay()"> Pay </button>
          </div>
        </fieldset>
 
      </form>
    </div>
  </div>
  <script src="https://js.paystack.co/v1/inline.js"></script>
  <script>
    // change this to your public key so you 
    // will no more be prompted
    var public_key = 'pk_test_9d97cf0be86b0758ece444694d57a8db41a4be59';
    
    /*
     * Start up
     */
    function startUp(){
      checkAmountInKobo();
      promptForPublicKey();
    }
    
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
     * Prompt for and set public key to use
     * If public key set is not valid
     */
    function promptForPublicKey(){
      if(!isValidPublicKey()){
        // get a public key to use
        public_key = prompt("To run this sample, you need to provide a public key.\n"+
                            "Please visit https://dashboard.paystack.co/#/settings/developer to get your "+
                            "public key and enter in the box below:", "pk_xxxx_");
        // check that we got a valid public key 
        // (starts with pk_live_ or pk_test_)
        if (!isValidPublicKey()) {
          var error_msg = "You didn't provide a public key. This page will not load.";
          alert(error_msg);
          document.getElementById('pay-form').innerHTML = error_msg;
        }
      }
    }
    /* 
     * Validate before opening Paystack popup
     */
    function validateAndPay(){
      var email = document.getElementById('email').value;
      if(!simpleValidEmail(email)){
        alert("Please provide a valid email");
        return;
      }
      
      var amountinkobo = getAmountInKobo();
      if(!amountinkobo){
        alert("Please provide a valid amount");
        document.getElementById('amountinnaira').style.display="block";
        document.getElementById('static-amount').style.display="none";
        return;
      }
      
      // We are not validating firstname and lastname
      var firstname = document.getElementById('firstname').value;
      var lastname  = document.getElementById('lastname').value;
      
      payWithPaystack(email, amountinkobo, firstname, lastname);
    }
  
    /* Get the query parameters for this window
     * 
     * source: http://stackoverflow.com/a/21210643/671568
     */
    function getParams(){
      var queryDict = {};
      location.search
        .substr(1)
        .split("&")
        .forEach(function(item) {
          queryDict[item.split("=")[0]] = item.split("=")[1];
        });
      return queryDict;
    }
    
    /* Check amount sent by get if it's a valid integer
     * show the amount input box if not
     */
    function checkAmountInKobo(){
      amountinkobo = getParams().amountinkobo;
      
      if(!simpleValidInt(amountinkobo)){
        // show the amount box since an amount was not specified by GET
        document.getElementById('amountinnaira').style.display="block";
        document.getElementById('static-amount').style.display="none";
      } else {
        document.getElementById('amountinngn').innerHTML = amountinkobo / 100;
      }
    }
  
    /* Get amount sent by get if it's a valid integer
     * Get the amount entered in input box  multiplied
     *  by 100. Show alert if no valid amountinkobo can be found
     */
    function getAmountInKobo(){
      amountinkobo = getParams().amountinkobo;
      
      if(!amountinkobo){
        amountinkobo = 100 * +document.getElementById('amount-in-naira').value;
      }
      
      if(!simpleValidInt(amountinkobo)){
        alert("Please provide an amount to pay");
      }
      
      return amountinkobo;
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
    
    /* Validate integer
     *
     * gotten from http://stackoverflow.com/a/25016143/671568
     */
    function simpleValidInt( data ) {
      if (+data===parseInt(data)) {
        return true;
      } else {
        return false;
      }
    };

    
    /* Validate email address 
     * not meant for really secure email validation
     *
     * gotten from http://stackoverflow.com/a/28633540/671568
     * Had to correct Regex, allowing A-Za-z0-9 to repeat
     */
    function simpleValidEmail( email ) {
      return email.length < 256 && /^[^@]+@[^@]+[A-Za-z0-9]{2,}\.[^@]+[A-Za-z0-9]{2,}$/.test(email);
    };

    /* Show the paystack payment popup
     * 
     * source: https://developers.paystack.co/docs/paystack-inline
     */
    function payWithPaystack(validatedemail, amountinkobo, firstname, lastname){
      var handler = PaystackPop.setup({
        key:       public_key,
        email:     validatedemail,
        firstname: firstname,
        lastname:  lastname,
        amount:    amountinkobo,
        ref:       generateREF(), // This uses a random transaction reference based 
                                  // on the time the "Pay" button was clicked.
        callback:  function(response){
          // payment was received
          // clear away the form, show success message
          var msg = 'Success. The transaction reference is <b>"' + response.trxref + '"</b>';
          document.getElementById('alert-holder').innerHTML = '<div class="alert alert-success">' + msg + '</div>';
          document.getElementById("pay-form").reset();
        },
        onClose:  function(){
          // Visitor cancelled payment
          var msg = 'Cancelled. Please click the \'Pay\' button to try again';
          document.getElementById('alert-holder').innerHTML = '<div class="alert alert-danger">' + msg + '</div>';
        }
      });
      handler.openIframe();
    }
  </script>
</body>
</html>


