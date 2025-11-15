<?php

use App\Api\v1\Controllers\GroupController;
use App\Api\v1\Controllers\IconController;
use App\Api\v1\Controllers\QrCodeController;
use App\Api\v1\Controllers\SettingController;
use App\Api\v1\Controllers\TwoFAccountController;
use App\Api\v1\Controllers\UserController;
use App\Api\v1\Controllers\UserManagerController;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/**
 * DEPRECATED - Unprotected routes
 */
Route::get('user/name', function () {
    return response()->json(['deprecation' => true], 200, ['Deprecation' => Date::createFromDate(2023, 03, 21)->toRfc7231String()]);
});

/**
 * Routes protected by the api authentication guard
 */
Route::group(['middleware' => 'auth:api-guard'], function () {
    Route::get('user', [UserController::class, 'show'])->name('user.show'); // Returns email address in addition to the username

    Route::get('user/preferences/{preferenceName}', [UserController::class, 'showPreference'])->name('user.preferences.show');
    Route::get('user/preferences', [UserController::class, 'allPreferences'])->name('user.preferences.all');
    Route::put('user/preferences/{preferenceName}', [UserController::class, 'setPreference'])->name('user.preferences.set');

    Route::delete('twofaccounts', [TwoFAccountController::class, 'batchDestroy'])->name('twofaccounts.batchDestroy');
    Route::patch('twofaccounts/withdraw', [TwoFAccountController::class, 'withdraw'])->name('twofaccounts.withdraw');
    Route::post('twofaccounts/reorder', [TwoFAccountController::class, 'reorder'])->name('twofaccounts.reorder');
    Route::post('twofaccounts/migration', [TwoFAccountController::class, 'migrate'])->name('twofaccounts.migrate');
    Route::post('twofaccounts/preview', [TwoFAccountController::class, 'preview'])->name('twofaccounts.preview');
    Route::get('twofaccounts/export', [TwoFAccountController::class, 'export'])->name('twofaccounts.export');
    Route::get('twofaccounts/{twofaccount}/qrcode', [QrCodeController::class, 'show'])->name('twofaccounts.show.qrcode');
    Route::get('twofaccounts/count', [TwoFAccountController::class, 'count'])->name('twofaccounts.count');
    Route::get('twofaccounts/{id}/otp', [TwoFAccountController::class, 'otp'])->where('id', '[0-9]+')->name('twofaccounts.show.otp');
    Route::post('twofaccounts/otp', [TwoFAccountController::class, 'otp'])->name('twofaccounts.otp');
    Route::apiResource('twofaccounts', TwoFAccountController::class);

    Route::get('groups/{group}/twofaccounts', [GroupController::class, 'accounts'])->name('groups.show.twofaccounts');
    Route::post('groups/{group}/assign', [GroupController::class, 'assignAccounts'])->name('groups.assign.twofaccounts');
    Route::post('groups/reorder', [GroupController::class, 'reorder'])->name('groups.reorder');
    Route::apiResource('groups', GroupController::class);

    Route::post('qrcode/decode', [QrCodeController::class, 'decode'])->name('qrcode.decode');

    Route::get('icons/packs', [IconController::class, 'iconPacks'])->name('icons.iconPacks');
    Route::post('icons/default', [IconController::class, 'fetch'])->name('icons.fetch');
    Route::post('icons', [IconController::class, 'upload'])->name('icons.upload');
    Route::delete('icons/{icon}', [IconController::class, 'delete'])->name('icons.delete');
});

/**
 * Routes protected by the api authentication guard and restricted to administrators
 */
Route::group(['middleware' => ['auth:api-guard', 'admin']], function () {
    Route::get('users/{user}/authentications', [UserManagerController::class, 'authentications'])->name('users.authentications');
    Route::patch('users/{user}/password/reset', [UserManagerController::class, 'resetPassword'])->name('users.password.reset');
    Route::patch('users/{user}/promote', [UserManagerController::class, 'promote'])->name('users.promote');
    Route::delete('users/{user}/pats', [UserManagerController::class, 'revokePATs'])->name('users.revoke.pats');
    Route::delete('users/{user}/credentials', [UserManagerController::class, 'revokeWebauthnCredentials'])->name('users.revoke.credentials');
    Route::apiResource('users', UserManagerController::class, ['except' => ['update']]);

    Route::get('settings/{settingName}', [SettingController::class, 'show'])->name('settings.show');
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'store'])->name('settings.store');
    Route::put('settings/{settingName}', [SettingController::class, 'update'])->name('settings.update');
    Route::delete('settings/{settingName}', [SettingController::class, 'destroy'])->name('settings.destroy');
});
