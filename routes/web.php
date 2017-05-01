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

$apiPrefix = Config::get('constants.route.prefix');

Route::get('/', function () {
    return view('welcome');
});

Route::get('user/{id}', function ($id) use ($apiPrefix){
    return $apiPrefix;
})->where('id', '[0-9]+');

Route::get($apiPrefix . '/ping', function () {
	Log::debug('ping pong');
	Log::debug(Config::get('constants.route.prefix'));
    return Response::json('pong');
});

// Route::pattern('reservation_code', '[a-zA-Z0-9\-]+');

Route::group(['middleware' => 'api_auth'], function () use ($apiPrefix) {
    Route::post($apiPrefix . '/reservation', 'ReservationController@create');
    Route::get($apiPrefix . '/reservations', 'ReservationController@index');
    Route::put($apiPrefix . '/reservation/{reservation_code}', 'ReservationController@update');
    Route::delete($apiPrefix . '/reservation/{reservation_code}', 'ReservationController@delete');
});
