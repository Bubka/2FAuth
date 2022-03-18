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
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('user', 'Auth\RegisterController@register')->name('user.register');
Route::post('user/password/lost', 'Auth\ForgotPasswordController@sendResetLinkEmail')->middleware('AvoidResetPassword')->name('user.password.lost');;
Route::post('user/password/reset', 'Auth\ResetPasswordController@reset')->name('user.password.reset');
Route::post('webauthn/lost', [WebAuthnDeviceLostController::class, 'sendRecoveryEmail'])->name('webauthn.lost');
Route::post('webauthn/recover/options', [WebAuthnRecoveryController::class, 'options'])->name('webauthn.recover.options');
Route::post('webauthn/recover', [WebAuthnRecoveryController::class, 'recover'])->name('webauthn.recover');

Route::group(['middleware' => 'auth:reverse-proxy,web'], function () {
    Route::put('user', 'Auth\UserController@update')->name('user.update');
    Route::patch('user/password', 'Auth\PasswordController@update')->name('user.password.update');
    Route::get('user/logout', 'Auth\LoginController@logout')->name('user.logout');
    Route::post('webauthn/register/options', [WebAuthnRegisterController::class, 'options'])->name('webauthn.register.options');
    Route::post('webauthn/register', [WebAuthnRegisterController::class, 'register'])->name('webauthn.register');
    Route::get('webauthn/credentials', [WebAuthnManageController::class, 'index'])->name('webauthn.credentials.index');
    Route::patch('webauthn/credentials/{credential}/name', [WebAuthnManageController::class, 'rename'])->name('webauthn.credentials.rename');
    Route::delete('webauthn/credentials/{credential}', [WebAuthnManageController::class, 'delete'])->name('webauthn.credentials.delete');
});

Route::group(['middleware' => ['guest:web', 'throttle:10,1']], function () {
    Route::post('user/login', 'Auth\LoginController@login')->name('user.login');
    Route::post('webauthn/login/options', [WebAuthnLoginController::class, 'options'])->name('webauthn.login.options');
    Route::post('webauthn/login', [WebAuthnLoginController::class, 'login'])->name('webauthn.login');
});

Route::get('/{any}', 'SinglePageController@index')->where('any', '.*')->name('landing');