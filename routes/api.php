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

Route::group(['middleware' => 'guest:api'], function () {

    Route::post('login', 'UserController@login');
    Route::post('checkuser', 'UserController@checkUser');
    Route::post('register', 'UserController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

});

Route::group(['middleware' => 'auth:api'], function() {

    Route::post('logout', 'UserController@logout');
    Route::patch('user/edit', 'UserController@update');
    Route::patch('user/password', 'UserController@changePassword');
    Route::get('user', 'UserController@getDetails');

    Route::apiResource('twofaccounts', 'TwoFAccountController');
    Route::get('twofaccounts/{twofaccount}/totp', 'TwoFAccountController@generateTOTP')->name('twofaccounts.generateTOTP');
    Route::post('qrcode/decode', 'QrCodeController@decode');
    Route::post('icon/upload', 'IconController@upload');
    Route::delete('icon/delete/{icon}', 'IconController@delete');

});