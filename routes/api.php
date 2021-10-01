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

    Route::post('user', 'Auth\RegisterController@register');

    Route::post('login', 'Auth\LoginController@login');

    Route::get('user/name', 'Auth\UserController@show');
    Route::post('user/password/lost', 'Auth\ForgotPasswordController@sendResetLinkEmail')->middleware('AvoidResetPassword');
    Route::post('user/password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

});

Route::group(['middleware' => 'auth:api'], function() {

    Route::get('user', 'Auth\UserController@show');
    Route::put('user', 'Auth\UserController@update');
    Route::patch('user/password', 'Auth\PasswordController@update');

    Route::post('logout', 'Auth\LoginController@logout');

    // Route::prefix('settings')->group(function () {
        // Route::get('account', 'Settings\AccountController@show');
        // Route::post('options', 'Settings\OptionController@store');
    // });

    Route::get('settings/{name}', 'SettingController@show');
    Route::get('settings', 'SettingController@index');
    Route::post('settings', 'SettingController@store');
    Route::put('settings/{name}', 'SettingController@update');
    Route::delete('settings/{name}', 'SettingController@destroy');

    Route::delete('twofaccounts', 'TwoFAccountController@batchDestroy');
    Route::patch('twofaccounts/withdraw', 'TwoFAccountController@withdraw');
    Route::post('twofaccounts/reorder', 'TwoFAccountController@reorder');
    Route::post('twofaccounts/preview', 'TwoFAccountController@preview');
    Route::get('twofaccounts/{twofaccount}/qrcode', 'QrCodeController@show');
    Route::get('twofaccounts/count', 'TwoFAccountController@count');
    Route::get('twofaccounts/{id}/otp', 'TwoFAccountController@otp')->where('id', '[0-9]+');
    Route::post('twofaccounts/otp', 'TwoFAccountController@otp');
    Route::apiResource('twofaccounts', 'TwoFAccountController');

    Route::get('groups/{group}/twofaccounts', 'GroupController@accounts');
    Route::post('groups/{group}/assign', 'GroupController@assignAccounts');
    Route::apiResource('groups', 'GroupController');

    Route::post('qrcode/decode', 'QrCodeController@decode');

    Route::post('icons', 'IconController@upload');
    Route::delete('icons/{icon}', 'IconController@delete');

});