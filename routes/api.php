<?php

use Illuminate\Http\Request;

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

Route::post('login', 'Api\UserController@login');
Route::post('register', 'Api\UserController@register');
Route::post('google_signin', 'Api\UserController@googleSignIn');
Route::post('refresh_token', 'Api\UserController@refreshToken');
Route::post('send_otp', 'Api\UserController@sendOtp');
Route::post('verify_otp', 'Api\UserController@verifyOtp');

Route::middleware('auth:api')->group(function () {
    Route::get('/validate-token', function () {
	    return ['valid' => true];
	});
    Route::get('details', 'Api\UserController@details');
});
