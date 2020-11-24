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

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('api/rates/{rate_date}', [
         'uses' => 'RatesController@getrate']);

    Route::get('api/txns/{userid}/{date}', [
        'uses' => 'TxnsController@dailysumm']);

    Route::post('api/txns', [
        'uses' => 'TxnsController@posttxn']);

    Route::get('api/txns', [
        'uses' => 'TxnsController@gettxns']);

    Route::get('api/user/{username}', [
        'uses' => 'UsersController@getuserdetails']);
    
    Route::post('api/user/{username}/changepassword', [
        'uses' => 'UsersController@changePassword']);

    Route::get('api/pump/fuel/{attendantid}', [
        'uses' => 'PumpsController@fuelattendantpumps']);

    Route::get('api/pump/{attendantid}', [
        'uses' => 'PumpsController@attendantpumps']);

    Route::get('api/getvehicles', [
        'uses' => 'VehiclesController@getvehicles']);
});

//Route::group(['middleware' => 'guest'], function () {
    

Route::get('/users/signin', [
    'uses' => 'UsersController@getSignin',
    'as' => 'users.signin'
]);

Route::post('/users/signin', [
    'uses' => 'UsersController@postSignin',
    'as' => 'users.signin'
]);

// Route::get('testemail', function(){
// 	Mail::raw('Sending emails with Mailgun and Laravel is easy!', function($message)
// 	{
//         $message->subject('Mailgun and Laravel are awesome!');
// 		$message->to('djuma@avanettech.co.ke');
// 	});
// });

Route::group(['middleware' => 'auth'] , function () {
    //Dashboard
    Route::get('/', function () {
        return view('dashboard.index');
    });
    
    Route::get('/dashboard', [
        'uses' => 'DashboardController@index' , 
        'as' => 'dashboard.index'
    ]);

    //Superadmin
    Route::get('/superadmin', [
        'uses' => 'SuperadminController@index' , 
        'as' => 'superadmin.index'
    ]);

    Route::get('/superadmin/company', [
        'uses' => 'SuperadminController@companyindex' , 
        'as' => 'superadmin.company.index'
    ]);

    Route::get('/superadmin/company/create', [
        'uses' => 'SuperadminController@companycreate' , 
        'as' => 'superadmin.company.create'
    ]);

    Route::post('/superadmin/company/store', [
        'uses' => 'SuperadminController@companystore' , 
        'as' => 'superadmin.company.store'
    ]);

    Route::get('/superadmin/company/{id}/edit', [
        'uses' => 'SuperadminController@companyedit' , 
        'as' => 'superadmin.company.edit'
    ]);

    Route::match(array('PUT', 'PATCH'), '/superadmin/company/{id}', [
        'uses' => 'SuperadminController@companyupdate' , 
        'as' => 'superadmin.company.update'
    ]);

    Route::delete('/superadmin/company/{id}', [
        'uses' => 'SuperadminController@companydestroy' , 
        'as' => 'superadmin.company.destroy'
    ]);

    //Transactions
    Route::get('/txns/{id}/edit', [
        'uses' => 'TxnsController@edit' , 
        'as' => 'txns.edit'
    ]);
    
    Route::match(array('PUT', 'PATCH'), '/txns/{id}/edit', [
        'uses' => 'TxnsController@update' , 
        'as' => 'txns.update'
    ]);

    Route::get('/txns/downloadExcel/{type}', [
        'uses' => 'TxnsController@downloadExcel',
        'as' => 'txns.downloadExcel'
    ]);

    Route::get('/txns/downloadloyaltyExcel/{type}', [
        'uses' => 'TxnsController@downloadloyaltyExcel',
        'as' => 'txns.downloadloyaltyExcel'
    ]);

    Route::get('/loyalty', [
        'uses' => 'TxnsController@loyaltySummary',
        'as' => 'loyalty.index'
    ]);

    Route::post('/loyalty', [
        'uses' => 'TxnsController@loyaltySummary',
        'as' => 'loyalty.filter'
    ]);

    Route::get('/loyalty/{vehregno}', [
        'uses' => 'TxnsController@loyaltyDetails',
        'as' => 'loyalty.show'
    ]);
		
    Route::get('/txns', 'TxnsController@index')->name('txns.index');

    // Route::post('/txns', 'TxnsController@index')->name('txns.filter');

    Route::get('/txns/salessumm', 'TxnsController@salessumm')->name('txns.salessumm.index');
        
    // Route::post('/txns/salessumm', 'TxnsController@salessumm')->name('txns.salessumm.filter');

    //Eodays
    Route::get('/eodays/downloadExcel/{type}', [
        'uses' => 'EodaysController@downloadeodayExcel',
        'as' => 'eodays.downloadeodayExcel'
    ]);

    Route::get('/eodays', [
        'uses' => 'EodaysController@index' , 
        'as' => 'eodays.index'
    ]); 

    Route::get('/eodays/{eoday}', [
        'uses' => 'EodaysController@show' , 
        'as' => 'eodays.show'
    ]);

    //Users
    Route::get('/users/logout', [
        'uses' => 'UsersController@getLogout',
        'as' => 'users.logout'
    ]);

    Route::get('/users/profile', [
        'uses' => 'UsersController@getProfile',
        'as' => 'users.profile'
    ]);

    Route::get('/users/resetpass', [
        'uses' => 'UsersController@resetpass',
        'as' => 'users.resetindividualpass'
    ]);

    Route::post('/users/resetpass', [
        'uses' => 'UsersController@postResetpass',
        'as' => 'users.postResetindividualpass'
    ]);


    //Readings
    Route::resource('readings', 'ReadingsController');

    //Othertxns
    Route::resource('othertxns', 'OthertxnsController');

    //Monthlyrpt
    /*Route::get('/reports/monthly', function () {
        return view('reports.monthly.index');
    });*/

    Route::post('/reports/monthly', 'EodaysController@monthlyrpt')->name('monthly.post');
   
    Route::get('/reports/monthly', 'EodaysController@monthlyrpt')->name('monthly.get');

    Route::get('/reports/vehicles', 'EodaysController@vehiclesrpt')->name('reports.vehicles');		
  
    //PDF
    Route::get('/pdf/stations', 'PDFController@index');

    //Admin Middleware
    Route::group(['middleware' => 'auth.admin'] , function () {
        //Stations
        // Route::get('/stations', 'StationsController@index');

        // Route::post('/stations', 'StationsController@store');

        // Route::get('/stations/create', 'StationsController@create');

        Route::resource('stations', 'StationsController');

        //Owners
        // Route::get('/owners', [
        //     'uses' => 'OwnersController@index',
        //     'as' => 'owners.index'
        // ]);

        // Route::post('/owners', 'OwnersController@store');

        // Route::get('/owners/create', 'OwnersController@create');

        Route::resource('owners', 'OwnersController');

        // Vehicles
        Route::resource('vehicles', 'VehiclesController');        

        //Users
        // Route::get('/users', 'UsersController@index');
        
        Route::resource('users', 'UsersController');

        Route::get('/users/{user}/resetpass', [
            'uses' => 'UsersController@resetPassword',
            'as' => 'users.resetpass'
        ]);

        //Rates
        Route::resource('rates', 'RatesController');

        //Pumps
        Route::get('/stationid/attendant/{stationid}', 'PumpsController@getattendants')->name('station.getattendants');

        Route::resource('pumps', 'PumpsController');

        //Getting started
        Route::get('/getstarted', [
            'uses' => 'DashboardController@getstarted' , 
            'as' => 'dashboard.getstarted'
        ]);
           
    });

    //StationadminMiddleware
    Route::group(['middleware' => 'auth.stationadmin'] , function () {
        //Creade Eoday/Readings
        Route::get('/readings/create', [
            'uses' => 'ReadingsController@create' , 
            'as' => 'readings.create'
        ]);        
    });

});
