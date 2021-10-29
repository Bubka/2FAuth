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

    Route::get('user/name', 'Auth\UserController@show')->name('user.show.name');
    Route::post('user', 'Auth\RegisterController@register')->name('user.register');
    Route::post('user/password/lost', 'Auth\ForgotPasswordController@sendResetLinkEmail')->middleware('AvoidResetPassword')->name('user.password.lost');;
    Route::post('user/password/reset', 'Auth\ResetPasswordController@reset')->name('user.password.reset');

});

Route::group(['middleware' => 'auth:api'], function() {

    Route::get('oauth/personal-access-tokens', '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@forUser')->name('passport.personal.tokens.index');
    Route::post('oauth/personal-access-tokens', '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@store')->name('passport.personal.tokens.store');
    Route::delete('oauth/personal-access-tokens/{token_id}', '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@destroy')->name('passport.personal.tokens.destroy');

    Route::get('user', 'Auth\UserController@show')->name('user.show');
    Route::put('user', 'Auth\UserController@update')->name('user.update');
    Route::patch('user/password', 'Auth\PasswordController@update')->name('user.password.update');

    Route::get('settings/{settingName}', 'SettingController@show')->name('settings.show');
    Route::get('settings', 'SettingController@index')->name('settings.index');
    Route::post('settings', 'SettingController@store')->name('settings.store');
    Route::put('settings/{settingName}', 'SettingController@update')->name('settings.update');
    Route::delete('settings/{settingName}', 'SettingController@destroy')->name('settings.destroy');

    Route::delete('twofaccounts', 'TwoFAccountController@batchDestroy')->name('twofaccounts.batchDestroy');
    Route::patch('twofaccounts/withdraw', 'TwoFAccountController@withdraw')->name('twofaccounts.withdraw');
    Route::post('twofaccounts/reorder', 'TwoFAccountController@reorder')->name('twofaccounts.reorder');
    Route::post('twofaccounts/preview', 'TwoFAccountController@preview')->name('twofaccounts.preview');
    Route::get('twofaccounts/{twofaccount}/qrcode', 'QrCodeController@show')->name('twofaccounts.show.qrcode');
    Route::get('twofaccounts/count', 'TwoFAccountController@count')->name('twofaccounts.count');
    Route::get('twofaccounts/{id}/otp', 'TwoFAccountController@otp')->where('id', '[0-9]+')->name('twofaccounts.show.otp');
    Route::post('twofaccounts/otp', 'TwoFAccountController@otp')->name('twofaccounts.otp');
    Route::apiResource('twofaccounts', 'TwoFAccountController');

    Route::get('groups/{group}/twofaccounts', 'GroupController@accounts')->name('groups.show.twofaccounts');
    Route::post('groups/{group}/assign', 'GroupController@assignAccounts')->name('groups.assign.twofaccounts');
    Route::apiResource('groups', 'GroupController');

    Route::post('qrcode/decode', 'QrCodeController@decode')->name('qrcode.decode');

    Route::post('icons', 'IconController@upload')->name('icons.upload');
    Route::delete('icons/{icon}', 'IconController@delete')->name('icons.delete');

});