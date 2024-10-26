<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\WebAuthnDeviceLostController;
use App\Http\Controllers\Auth\WebAuthnLoginController;
use App\Http\Controllers\Auth\WebAuthnManageController;
use App\Http\Controllers\Auth\WebAuthnRecoveryController;
use App\Http\Controllers\Auth\WebAuthnRegisterController;
use App\Http\Controllers\SinglePageController;
use App\Http\Controllers\SystemController;
use App\Http\Middleware\CustomCreateFreshApiToken;
use App\Http\Middleware\SetLanguage;
use Illuminate\Routing\Middleware\SubstituteBindings;
// use Illuminate\Foundation\Events\DiagnosingHealth;
// use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;

// use App\Models\User;
// use App\Notifications\SignedInWithNewDeviceNotification;
// use App\Models\AuthLog;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/**
 * Routes that only work for unauthenticated user (return an error otherwise)
 * 'kickOutInactiveUser', 
 */
Route::group(['middleware' => ['rejectIfDemoMode', 'RejectIfSsoOnlyAndNotForAdmin', 'forceLogout']], function () {
    Route::post('user', [RegisterController::class, 'register'])->name('user.register');
    Route::post('user/password/lost', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('user.password.lost');
    Route::post('user/password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');
    Route::post('webauthn/login/options', [WebAuthnLoginController::class, 'options'])->name('webauthn.login.options');
    Route::post('webauthn/lost', [WebAuthnDeviceLostController::class, 'sendRecoveryEmail'])->name('webauthn.lost');
});

/**
 * Routes that can be requested max 10 times per minute by the same IP
 */
Route::group(['middleware' => ['rejectIfDemoMode', 'throttle:10,1', 'RejectIfSsoOnlyAndNotForAdmin', 'forceLogout']], function () {
    Route::post('webauthn/recover', [WebAuthnRecoveryController::class, 'recover'])->name('webauthn.recover');
});

/**
 * Routes that only work for unauthenticated user (return an error otherwise)
 * that can be requested max 10 times per minute by the same IP 'kickOutInactiveUser', 
 */
Route::group(['middleware' => ['forceLogout', 'throttle:10,1']], function () {
    Route::post('user/login', [LoginController::class, 'login'])->name('user.login')->middleware('RejectIfSsoOnlyAndNotForAdmin');
    Route::post('webauthn/login', [WebAuthnLoginController::class, 'login'])->name('webauthn.login')->middleware('RejectIfSsoOnlyAndNotForAdmin');

    Route::get('/socialite/redirect/{driver}', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
    Route::get('/socialite/callback/{driver}', [SocialiteController::class, 'callback'])->name('socialite.callback');
});

/**
 * Routes protected by an authentication guard but rejected when the reverse-proxy
 * guard is enabled or SSO only is enabled
 */
Route::group(['middleware' => ['behind-auth', 'rejectIfReverseProxy']], function () {
    Route::put('user', [UserController::class, 'update'])->name('user.update');
    Route::patch('user/password', [PasswordController::class, 'update'])->name('user.password.update')->middleware('rejectIfDemoMode');
    Route::get('user/logout', [LoginController::class, 'logout'])->name('user.logout');
    Route::delete('user', [UserController::class, 'delete'])->name('user.delete')->middleware('rejectIfDemoMode');

    Route::get('oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'forUser'])->name('passport.personal.tokens.index')->middleware('RejectIfSsoOnlyAndNotForAdmin');
    Route::post('oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'store'])->name('passport.personal.tokens.store')->middleware('RejectIfSsoOnlyAndNotForAdmin');
    Route::delete('oauth/personal-access-tokens/{token_id}', [PersonalAccessTokenController::class, 'destroy'])->name('passport.personal.tokens.destroy')->middleware('RejectIfSsoOnlyAndNotForAdmin');

    Route::post('webauthn/register/options', [WebAuthnRegisterController::class, 'options'])->name('webauthn.register.options')->middleware('RejectIfSsoOnlyAndNotForAdmin');
    Route::post('webauthn/register', [WebAuthnRegisterController::class, 'register'])->name('webauthn.register')->middleware('RejectIfSsoOnlyAndNotForAdmin');
    Route::get('webauthn/credentials', [WebAuthnManageController::class, 'index'])->name('webauthn.credentials.index')->middleware('RejectIfSsoOnlyAndNotForAdmin');
    Route::patch('webauthn/credentials/{credential}/name', [WebAuthnManageController::class, 'rename'])->name('webauthn.credentials.rename')->middleware('RejectIfSsoOnlyAndNotForAdmin');
    Route::delete('webauthn/credentials/{credential}', [WebAuthnManageController::class, 'delete'])->name('webauthn.credentials.delete')->middleware('RejectIfSsoOnlyAndNotForAdmin');
});

/**
 * Routes protected by an authentication guard and restricted to administrators
 */
Route::group(['middleware' => ['behind-auth', 'admin']], function () {
    Route::get('system/infos', [SystemController::class, 'infos'])->name('system.infos');
    Route::post('system/test-email', [SystemController::class, 'testEmail'])->name('system.testEmail');
});

Route::get('system/optimize', [SystemController::class, 'optimize'])->name('system.optimize');
Route::get('system/clear-cache', [SystemController::class, 'clear'])->name('system.clear');
Route::get('system/latestRelease', [SystemController::class, 'latestRelease'])->name('system.latestRelease');

Route::get('refresh-csrf', function () {
    return csrf_token();
});

Route::withoutMiddleware([
    SubstituteBindings::class,
    SetLanguage::class,
    CustomCreateFreshApiToken::class,
])->get('/up', function () {
    //Event::dispatch(new DiagnosingHealth);
    return view('health');
});

// Route::get('/notification', function () {
//     $user = User::find(1);
//     return (new SignedInWithNewDeviceNotification(AuthLog::find(9)))
//         ->toMail($user);
// });

/**
 * Route for the main landing view
 */
Route::get('/{any}', [SinglePageController::class, 'index'])->where('any', '.*')->name('landing');
