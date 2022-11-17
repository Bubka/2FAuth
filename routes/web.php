<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\WebAuthnDeviceLostController;
use App\Http\Controllers\Auth\WebAuthnLoginController;
use App\Http\Controllers\Auth\WebAuthnManageController;
use App\Http\Controllers\Auth\WebAuthnRecoveryController;
use App\Http\Controllers\Auth\WebAuthnRegisterController;
use App\Http\Controllers\SinglePageController;
use App\Http\Controllers\SystemController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/**
 * Routes that only work for unauthenticated user (return an error otherwise)
 */
Route::group(['middleware' => ['guest', 'rejectIfDemoMode']], function () {
    Route::post('user', [RegisterController::class, 'register'])->name('user.register');
    Route::post('user/password/lost', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('user.password.lost');
    Route::post('user/password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');
    Route::post('webauthn/login/options', [WebAuthnLoginController::class, 'options'])->name('webauthn.login.options');
    Route::post('webauthn/lost', [WebAuthnDeviceLostController::class, 'sendRecoveryEmail'])->name('webauthn.lost');
});

/**
 * Routes that can be requested max 10 times per minute by the same IP
 */
Route::group(['middleware' => ['rejectIfDemoMode', 'throttle:10,1']], function () {
    Route::post('webauthn/recover', [WebAuthnRecoveryController::class, 'recover'])->name('webauthn.recover');
});

/**
 * Routes that only work for unauthenticated user (return an error otherwise)
 * that can be requested max 10 times per minute by the same IP
 */
Route::group(['middleware' => ['SkipIfAuthenticated', 'throttle:10,1']], function () {
    Route::post('user/login', [LoginController::class, 'login'])->name('user.login');
    Route::post('webauthn/login', [WebAuthnLoginController::class, 'login'])->name('webauthn.login');
});

/**
 * Routes protected by an authentication guard but rejected when reverse-proxy guard is enabled
 */
Route::group(['middleware' => ['behind-auth', 'rejectIfReverseProxy']], function () {
    Route::put('user', [UserController::class, 'update'])->name('user.update');
    Route::patch('user/password', [PasswordController::class, 'update'])->name('user.password.update')->middleware('rejectIfDemoMode');
    Route::get('user/logout', [LoginController::class, 'logout'])->name('user.logout');
    Route::delete('user', [UserController::class, 'delete'])->name('user.delete')->middleware('rejectIfDemoMode');

    Route::get('oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'forUser'])->name('passport.personal.tokens.index');
    Route::post('oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'store'])->name('passport.personal.tokens.store');
    Route::delete('oauth/personal-access-tokens/{token_id}', [PersonalAccessTokenController::class, 'destroy'])->name('passport.personal.tokens.destroy');

    Route::post('webauthn/register/options', [WebAuthnRegisterController::class, 'options'])->name('webauthn.register.options');
    Route::post('webauthn/register', [WebAuthnRegisterController::class, 'register'])->name('webauthn.register');
    Route::get('webauthn/credentials', [WebAuthnManageController::class, 'index'])->name('webauthn.credentials.index');
    Route::patch('webauthn/credentials/{credential}/name', [WebAuthnManageController::class, 'rename'])->name('webauthn.credentials.rename');
    Route::delete('webauthn/credentials/{credential}', [WebAuthnManageController::class, 'delete'])->name('webauthn.credentials.delete');
});

Route::get('refresh-csrf', function () {
    return csrf_token();
});

Route::get('infos', [SystemController::class, 'infos'])->name('system.infos');
Route::get('latestRelease', [SystemController::class, 'latestRelease'])->name('system.latestRelease');

/**
 * Route for the main landing view
 */
Route::get('/{any}', [SinglePageController::class, 'index'])->where('any', '.*')->name('landing');
