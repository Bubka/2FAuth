<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebAuthnManageController;
use App\Http\Controllers\Auth\WebAuthnRegisterController;
use App\Http\Controllers\Auth\WebAuthnLoginController;
use App\Http\Controllers\Auth\WebAuthnDeviceLostController;
use App\Http\Controllers\Auth\WebAuthnRecoveryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/**
 * Routes that only work for unauthenticated user (return an error otherwise)
 */
Route::group(['middleware' => ['guest', 'rejectIfDemoMode']], function () {
    Route::post('user', 'Auth\RegisterController@register')->name('user.register');
    Route::post('user/password/lost', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('user.password.lost');;
    Route::post('user/password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');
    Route::post('webauthn/login/options', [WebAuthnLoginController::class, 'options'])->name('webauthn.login.options');
    Route::post('webauthn/lost', [WebAuthnDeviceLostController::class, 'sendRecoveryEmail'])->name('webauthn.lost');
    Route::post('webauthn/recover/options', [WebAuthnRecoveryController::class, 'options'])->name('webauthn.recover.options');
    Route::post('webauthn/recover', [WebAuthnRecoveryController::class, 'recover'])->name('webauthn.recover');
});

/**
 * Routes that only work for unauthenticated user (return an error otherwise)
 * that can be requested max 10 times per minute by the same IP
 */
Route::group(['middleware' => ['SkipIfAuthenticated', 'throttle:10,1']], function () {
    Route::post('user/login', 'Auth\LoginController@login')->name('user.login');
    Route::post('webauthn/login', [WebAuthnLoginController::class, 'login'])->name('webauthn.login');
});

/**
 * Routes protected by an authentication guard but rejected when reverse-proxy guard is enabled
 */
Route::group(['middleware' => ['behind-auth', 'rejectIfReverseProxy']], function () {
    Route::put('user', 'Auth\UserController@update')->name('user.update');
    Route::patch('user/password', 'Auth\PasswordController@update')->name('user.password.update')->middleware('rejectIfDemoMode');
    Route::get('user/logout', 'Auth\LoginController@logout')->name('user.logout');
    Route::delete('user', 'Auth\UserController@delete')->name('user.delete')->middleware('rejectIfDemoMode');

    Route::get('oauth/personal-access-tokens', '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@forUser')->name('passport.personal.tokens.index');
    Route::post('oauth/personal-access-tokens', '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@store')->name('passport.personal.tokens.store');
    Route::delete('oauth/personal-access-tokens/{token_id}', '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@destroy')->name('passport.personal.tokens.destroy');
    
    Route::post('webauthn/register/options', [WebAuthnRegisterController::class, 'options'])->name('webauthn.register.options');
    Route::post('webauthn/register', [WebAuthnRegisterController::class, 'register'])->name('webauthn.register');
    Route::get('webauthn/credentials', [WebAuthnManageController::class, 'index'])->name('webauthn.credentials.index');
    Route::patch('webauthn/credentials/{credential}/name', [WebAuthnManageController::class, 'rename'])->name('webauthn.credentials.rename');
    Route::delete('webauthn/credentials/{credential}', [WebAuthnManageController::class, 'delete'])->name('webauthn.credentials.delete');
});

Route::get('refresh-csrf', function(){
    return csrf_token();
});

/**
 * Route for the main landing view
 */
Route::get('/{any}', 'SinglePageController@index')->where('any', '.*')->name('landing');