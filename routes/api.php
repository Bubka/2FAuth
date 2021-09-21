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

    Route::post('auth/login', 'Auth\LoginController@login');
    Route::post('checkuser', 'Auth\RegisterController@checkUser');
    Route::post('auth/register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->middleware('AvoidResetPassword');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

});

Route::group(['middleware' => 'auth:api'], function() {

    Route::post('auth/logout', 'Auth\LoginController@logout');

    Route::prefix('settings')->group(function () {
        Route::get('account', 'Settings\AccountController@show');
        Route::patch('account', 'Settings\AccountController@update');
        Route::patch('password', 'Settings\PasswordController@update');
        Route::get('options', 'Settings\OptionController@index');
        Route::post('options', 'Settings\OptionController@store');
    });

    Route::delete('twofaccounts', 'TwoFAccountController@batchDestroy');
    Route::patch('twofaccounts/withdraw', 'TwoFAccountController@withdraw');
    Route::post('twofaccounts/reorder', 'TwoFAccountController@reorder');
    Route::post('twofaccounts/preview', 'TwoFAccountController@preview');
    Route::get('twofaccounts/{twofaccount}/qrcode', 'QrCodeController@show');
    Route::get('twofaccounts/count', 'TwoFAccountController@count');
    Route::get('twofaccounts/{id}/otp', 'TwoFAccountController@otp')->where('id', '[0-9]+');;
    Route::post('twofaccounts/otp', 'TwoFAccountController@otp');
    Route::apiResource('twofaccounts', 'TwoFAccountController');
    Route::post('groups/{group}/assign', 'GroupController@assignAccounts');
    Route::apiResource('groups', 'GroupController');

    // Done
    Route::post('qrcode/decode', 'QrCodeController@decode');
    Route::post('icons', 'IconController@upload');
    Route::delete('icons/{icon}', 'IconController@delete');

});