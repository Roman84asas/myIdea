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

Route::get('payments/new', 'PaymentsController@create')
    ->name('payments.create')
    ->middleware('auth');

Route::post('payments', 'PaymentsController@store')
    ->name('payments.store')
    ->middleware('auth');

Route::get('login', 'LoginController@showLoginForm')
    ->name('login');