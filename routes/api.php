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

    Route::post('login', 'Auth\LoginController@login');
    Route::post('checkuser', 'Auth\RegisterController@checkUser');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

});

Route::group(['middleware' => 'auth:api'], function() {

    Route::post('logout', 'Auth\LoginController@logout');

    Route::prefix('settings')->group(function () {
        Route::get('account', 'Settings\AccountController@show');
        Route::patch('account', 'Settings\AccountController@update');
        Route::patch('password', 'Settings\PasswordController@update');
        Route::get('options', 'Settings\OptionController@index');
        Route::post('options', 'Settings\OptionController@store');
    });

    Route::delete('twofaccounts/batch', 'TwoFAccountController@batchDestroy');
    Route::apiResource('twofaccounts', 'TwoFAccountController');
    Route::post('twofaccounts/otp', 'TwoFAccountController@generateOTP')->name('twofaccounts.generateOTP');
    Route::post('qrcode/decode', 'QrCodeController@decode');
    Route::post('icon/upload', 'IconController@upload');
    Route::delete('icon/delete/{icon}', 'IconController@delete');

});