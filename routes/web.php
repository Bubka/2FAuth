<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('twofaccount/{TwoFAccount}', 'TwoFAccountController@show');

Route::group(['middleware' => 'guest:web'], function () {
    Route::post('user/login', 'Auth\LoginController@login')->name('user.login');
});

Route::group(['middleware' => 'auth:web'], function () {
    Route::get('user/logout', 'Auth\LoginController@logout')->name('user.logout');
});

Route::get('/{any}', 'SinglePageController@index')->where('any', '.*')->name('landing');