<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User profile endpoint for dynamic avatar updates
Route::middleware('auth:sanctum')->get('/user/profile', function (Request $request) {
    $user = $request->user();
    return response()->json([
        'name' => $user->name,
        'avatar' => $user->profile_photo_url,
        'email' => $user->email,
    ]);
});