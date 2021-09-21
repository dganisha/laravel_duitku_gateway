<?php

use Illuminate\Support\Facades\Route;

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


Route::get('login', function(){
    return view('auth.login');
})->name('login');

Route::get('register', function(){
    return view('auth.register');
})->name('register');


Route::post('login', 'AuthController@login')->name('auth.login');
Route::post('register', 'AuthController@register')->name('auth.register');

Route::post('callback_transaksi', 'DashboardController@getCallback');
Route::get('cron_status', 'DashboardController@getStatus');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'DashboardController@index');
    Route::post('order', 'DashboardController@create')->name('order.create');
    Route::get('my_transaction', 'DashboardController@getMyTransaction');
    Route::get('admin_transaction', 'DashboardController@getAllTransaction');

    Route::get('/check_payment', 'DashboardController@check_pembayaran');
    Route::get('redirect-order', 'DashboardController@getCallback');
    Route::get('check_status', 'DashboardController@getStatus');

    Route::get('logout', 'AuthController@logout');
});

