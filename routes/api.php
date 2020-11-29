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

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->middleware('AvoidResetPassword');
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
    Route::patch('twofaccounts/reorder', 'TwoFAccountController@reorder');
    Route::post('twofaccounts/preview', 'TwoFAccountController@preview');
    Route::get('twofaccounts/{twofaccount}/withSensitive', 'TwoFAccountController@showWithSensitive');
    Route::get('twofaccounts/count', 'TwoFAccountController@count');
    Route::post('twofaccounts/token', 'TwoFAccountController@token');
    Route::apiResource('twofaccounts', 'TwoFAccountController');
    Route::patch('group/accounts', 'GroupController@associateAccounts');
    Route::apiResource('groups', 'GroupController');
    Route::post('qrcode/decode', 'QrCodeController@decode');
    Route::get('qrcode/{twofaccount}', 'QrCodeController@show');
    Route::post('icon/upload', 'IconController@upload');
    Route::delete('icon/delete/{icon}', 'IconController@delete');

});