<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;
use Digikraaft\PaystackSubscription\PaystackSubscription;
use Spatie\SlackAlerts\Facades\SlackAlert;
use Digikraaft\Paystack\Plan;
use App\Modules\Subscription\Http\Controllers\SubscriptionController;
use App\Modules\Subscription\Models\PaystackSubscription as Subscription;
use App\Modules\Subscription\Events\PaystackWebhookEvent;






Route::prefix('subscriptions')->group(function() {    
    Route::get('/user-invoices', function(){
    	$user = auth()->user();
		$invoices = $user->invoices();
		// Debug route - commented out for security
		// dd($invoices);
		return redirect()->route('account.index')->with('info', 'Invoice feature coming soon.');
    });

    Route::get('user/{invoice}', function (Request $request, $invoice) {
	    return $request->user()->downloadInvoice($invoice, [
	        'vendor'  => 'Your Company',
	        'product' => 'Your Product',
	    ]);
	});

	Route::get('/refresh', 'SubscriptionController@refreshSubscriptions')->name('subscriptions.refresh');
	Route::get('/cancel', 'SubscriptionController@cancelSubscription')->name('subscriptions.cancel');
	Route::get('/enable', 'SubscriptionController@restartSubscription')->name('subscriptions.enable');

});


// ========================================================================
// PUBLIC PRICING ROUTES (No authentication required - guests can view)
// ========================================================================

// Public pricing routes - accessible to all visitors (no auth middleware)
Route::group([ 'prefix' => 'pricings', 'middleware' => ['web'] ], function() {
    Route::get('/', 'PricingController@index')->name('pricings.index');
    Route::get('/{slug}', 'PricingController@show')->name('pricings.show');
});

// ========================================================================
// PROTECTED PAYMENT ROUTES (Authentication required)
// ========================================================================

// Payment routes - require authentication
Route::group([ 'prefix' => 'pricings', 'middleware' => ['auth', 'web'] ], function() {
    Route::get('/{slug}/pay', 'PricingController@pay')->name('pricings.pay');
});


Route::post('paystack/webhook', function(){
	$payload =   request()->all();
	event(new PaystackWebhookEvent($payload));
});



Route::post('paystack/subscription-disable', function(Request $request){

			$subscription = Subscription::where('subscription_id', $request->subscription_id)->first();

			$response = \Http::acceptJson()->withHeaders(['Authorization' => "Bearer " . env('PAYSTACK_SECRET_KEY')])
	    	->post('https://api.paystack.co/subscription/disable', [
	    		'code' => $subscription->subscription_code, 
	    		'token' => $subscription->email_token
	    	]);
	    return $response;
});


Route::post('paystack/subscription-enable', function(Request $request){

			$subscription = Subscription::where('subscription_id', $request->subscription_id)->first();
			$response = \Http::acceptJson()->withHeaders(['Authorization' => "Bearer " . env('PAYSTACK_SECRET_KEY')])
	    	->post('https://api.paystack.co/subscription/enable', [
	    		'code' => $subscription->subscription_code, 
	    		'token' => $subscription->email_token
	    	]);
	    return $response;
});

Route::post('paystack/verify', 'SubscriptionController@verifyPaystack');






/* 
    ADMIN
 */

/*EXAMPLE */
// Route::group([ 'prefix' => 'admin/plans', 'namespace' => 'Admin',  'middleware' => ['auth', 'web'] ], function() {
//     Route::get('', 'PaystackPlanController@index')->name('admin.plans.index');
// 	Route::post('', 'PaystackPlanController@store')->name('admin.plans.store');
// 	Route::delete('/plans/{id}', 'PaystackPlanController@destroy')->name('admin.plans.destroy');
// });


Route::namespace('Admin')->prefix('admin')->middleware('role:sudo|admin|publisher')->group(function() {
    Route::group(['middleware' => ['auth', 'web', 'permission'] ], function() {
		Route::resource('plans', 'PaystackPlanController', ['names' => 'admin.plans']);
	});
	Route::group(['middleware' => ['auth', 'web', 'permission'] ], function() {
		Route::resource('subscriptions', 'SubscriptionController', ['names' => 'admin.subscriptions']);
	});
	Route::group([ 'prefix' => 'pricings',  'middleware' => ['auth', 'web', 'permission'] ], function() {
	    Route::get('', 'PricingController@index')->name('admin.pricings.index');
	    Route::get('/{slug}', 'PricingController@show')->name('admin.pricings.single');
	    Route::patch('/{pricing}', 'PricingController@update')->name('admin.pricings.update');
		Route::post('', 'PricingController@store')->name('admin.pricings.store');
	});
});