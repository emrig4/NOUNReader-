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

Route::prefix('setting')->group(function() {
    Route::get('/', 'SettingController@index');

     Route::post('/locale', 'SettingController@setLocale')->name('settings.locale');
});


Route::namespace('Admin')->prefix('admin')->middleware('role:sudo|admin|publisher')->group(function() {

    Route::group([ 'prefix' => 'settings',  'middleware' => ['auth', 'web', 'permission'] ], function() {
        Route::get('', 'SettingController@index')->name('admin.settings.index');
        Route::get('/{slug}', 'SettingController@show')->name('admin.settings.single');
        Route::patch('/{pricing}', 'SettingController@update')->name('admin.settings.update');
        Route::post('', 'SettingController@store')->name('admin.settings.store');
    });
});
