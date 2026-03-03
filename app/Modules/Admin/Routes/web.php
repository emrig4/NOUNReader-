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



Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'web', 'role:sudo|admin' ] ], function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('admin.index');
});



Route::namespace('Admin')->prefix('admin')->middleware('role:sudo|admin|publisher')->group(function() {
    Route::group(['middleware' => ['auth', 'web', 'permission'] ], function() {
        Route::resource('users', 'UserController', ['names' => 'admin.users']);
        Route::resource('roles', RoleController::class,  ['names' => 'admin.roles']);
        Route::resource('permissions', PermissionController::class,  ['names' => 'admin.permissions']);
    });


});

