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

Auth::routes();

Route::group(['middleware' => ['auth'/*, 'userActive', 'forceSSL'*/]], function() {

	Route::get('/home', 'HomeController@index');

	Route::get('/networks', 'NetworkController@index');
	Route::get('/create-network', 'NetworkController@new');
	Route::post('/create-network', 'NetworkController@create');
	Route::get('/networks/edit', 'NetworkController@edit');
	Route::post('/networks/update', 'NetworkController@update');
	Route::delete('/networks/delete', 'NetworkController@delete');

});

