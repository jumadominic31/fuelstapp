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

Route::get('/', function () {
    return view('welcome');
});

Route::get('api/rates/{rate_date}', [
    'middleware' => 'auth.jwt', 
    'uses' => 'RatesController@show']);

Route::get('api/txns/{userid}/{date}', [
    'middleware' => 'auth.jwt', 
    'uses' => 'TxnsController@dailysumm']);

Route::get('api/user/{username}', [
    'middleware' => 'auth.jwt', 
    'uses' => 'UsersController@getuserdetails']);

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::resource('api/txns', 'TxnsController');
});

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::resource('api/rates', 'RatesController');
});

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::resource('api/stations','StationsController');
});

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::resource('api/stocks','StocksController');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
