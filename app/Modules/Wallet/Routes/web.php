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

Route::prefix('wallet')->group(function() {
    Route::get('/', 'CreditWalletController@index');

    Route::post('/withdraw', 'CreditWalletController@withdraw')->name('wallet.withdraw');
    Route::get('/cancel-withdrawal/{transaction}', 'CreditWalletController@cancelWithdrawal')->name('wallet.cancel-withdrawal');
});



/* 
    ADMIN
 */


Route::namespace('Admin')->prefix('admin')->middleware('role:sudo|admin|publisher')->group(function() {

    Route::group([ 'prefix' => 'wallets',  'middleware' => ['auth', 'web', 'permission'] ], function() {
        Route::get('/transactions', 'CreditWalletController@walletTransaction')->name('admin.wallets.transactions');
        Route::get('/{wallet}', 'CreditWalletController@show')->name('admin.wallets.show');
        Route::post('credit-wallet', 'CreditWalletController@credit')->name('admin.wallets.credit');
        Route::get('/process-withdrawal/{transaction}', 'CreditWalletController@processWithdrawal')->name('admin.process-withdrawal');
        Route::get('/reject-withdrawal/{transaction}', 'CreditWalletController@rejectWithdrawal')->name('admin.reject-withdrawal');
        Route::get('/earnings', 'CreditWalletController@walletTransaction')->name('admin.wallets.earnings');
        Route::get('/holdings', 'CreditWalletController@holdings')->name('admin.wallets.holdings');
        
        // Route::post('', 'PricingController@store')->name('admin.pricings.store');
    });
});