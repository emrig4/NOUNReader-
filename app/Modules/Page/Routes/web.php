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

// homepage

    Route::get('/', 'HomeController@index')->name('pages.home');

    Route::get('/faq', 'PageController@faq')->name('pages.faq');
	Route::get('/about-us', 'PageController@about')->name('pages.about');
	
	Route::get('/privacy-policy', 'PageController@privacy')->name('pages.privacy');
	Route::get('/copyright', 'PageController@copyright')->name('pages.copyright');
	
	Route::get('/policy', 'PageController@privacy')->name('policy.show');
	Route::get('/how-it-works', 'PageController@howItWorks')->name('how_it_works');

