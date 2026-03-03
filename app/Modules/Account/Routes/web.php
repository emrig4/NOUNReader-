<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => 'account', 'middleware' => ['auth', 'web'] ], function () {
    
    Route::get('/', 'AccountController@index' )->name('account.index');
    

    Route::get('/settings', function () {
        return view('account.settings');
    })->name('account.settings');

    Route::get('/subscription', function () {
        // if( account()->hasActiveSubscription() ){
        //     return view('account.subscription');
        // }else{
        //     return redirect()->route('pricings.index');
        // }
        return view('account.subscription');
    })->name('account.subscription');

    Route::get('/my-works', 'AccountController@myWorks')->name('account.myworks');
    
    Route::get('/my-wallet', 'AccountController@myWallet')->name('account.mywallet');
    
    Route::get('/my-wallet-history', 'AccountController@creditWalletHistory')->name('account.mywallethistory');
    
    Route::get('/my-notifications', 'AccountController@myNotifications')->name('account.notifications');
    
    Route::get('/my-notifications-markasread/{id}', 'AccountController@markNotificationAsRead')->name('account.notifications.read');


    Route::get('/followings', 'AccountFollowerController@index')->name('account.followings');
    Route::get('/follow/{userId}', 'AccountFollowerController@follow')->name('account.follow');
    Route::get('/unfollow/{userId}', 'AccountFollowerController@unfollow')->name('account.unfollow');


    Route::get('/favorites', 'AccountFavoriteController@index')->name('account.favorites');
    Route::get('/add-favorite/{resourceId}', 'AccountFavoriteController@store')->name('account.favorites.add');
    Route::get('/remove-favorite/{resourceId}', 'AccountFavoriteController@destroy')->name('account.favorites.remove');


});

Route::get('/a/{username}', 'AccountController@myProfile')->name('account.profile');



