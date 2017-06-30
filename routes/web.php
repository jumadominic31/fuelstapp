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

Route::get('api/rates/{fueltype}/{rate_date}','RatesController@show');

Route::get('api/txns/{userid}/{date}','TxnsController@dailysumm');

Route::resource('api/txns','TxnsController');

Route::resource('api/rates','RatesController');

Route::resource('api/stations','StationsController');

Route::resource('api/stocks','StocksController');

Route::resource('api/users','UsersController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
