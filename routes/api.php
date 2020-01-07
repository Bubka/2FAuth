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

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('logout', 'UserController@logout');
    Route::get('user', 'UserController@getDetails');
    Route::apiResource('twofaccounts', 'TwoFAccountController');
    Route::get('twofaccounts/{twofaccount}/totp', 'TwoFAccountController@generateTOTP')->name('twofaccounts.generateTOTP');
    Route::post('qrcode/decode', 'QrCodeController@decode');
    Route::post('icon/upload', 'IconController@upload');
});