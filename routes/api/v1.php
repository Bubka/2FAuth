<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/**
 * Unprotected routes
 */
Route::get('user/name', 'UserController@show')->name('user.show.name');


/**
 * Routes protected by the api authentication guard
 */
Route::group(['middleware' => 'auth:api-guard'], function () {
    Route::get('user', 'UserController@show')->name('user.show'); // Returns email address in addition to the username

    Route::get('settings/{settingName}', 'SettingController@show')->name('settings.show');
    Route::get('settings', 'SettingController@index')->name('settings.index');
    Route::post('settings', 'SettingController@store')->name('settings.store');
    Route::put('settings/{settingName}', 'SettingController@update')->name('settings.update');
    Route::delete('settings/{settingName}', 'SettingController@destroy')->name('settings.destroy');

    Route::delete('twofaccounts', 'TwoFAccountController@batchDestroy')->name('twofaccounts.batchDestroy');
    Route::post('twofaccounts/import', 'TwoFAccountController@import')->name('twofaccounts.import');
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

    Route::post('icons/default', 'IconController@fetch')->name('icons.fetch');
    Route::post('icons', 'IconController@upload')->name('icons.upload');
    Route::delete('icons/{icon}', 'IconController@delete')->name('icons.delete');
});