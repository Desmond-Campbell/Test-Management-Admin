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

Route::get('/checklogin', 'AccountController@checkLogin' );

Auth::routes();

Route::group(['middleware' => ['auth'/*, 'userActive', 'forceSSL'*/]], function() {

	// Network

	Route::get('/', 'NetworkController@index');
	Route::get('/home', 'NetworkController@index');
	Route::get('/networks', 'NetworkController@index');
	Route::get('/create-network', 'NetworkController@new');
	Route::post('/create-network', 'NetworkController@create');
	Route::get('/network/{id}/edit', 'NetworkController@edit');
	Route::post('/network/{id}update', 'NetworkController@update');
	Route::delete('/network/{id}/close', 'NetworkController@close');

	// People

	Route::get('/network/{network_id}/people', 'NetworkController@people');
	Route::get('/network/{network_id}/people/invite', 'NetworkController@invitePeople');
	Route::post('/network/{network_id}/people/invite', 'NetworkController@invitePeopleCreate');
	Route::get('/network/{network_id}/person/{id}/get', 'NetworkController@getPerson');
	Route::get('/network/{network_id}/person/new', 'NetworkController@newPerson');
	Route::post('/network/{network_id}/person/create', 'NetworkController@createPerson');
	Route::get('/network/{network_id}/person/{id}/edit', 'NetworkController@editPerson');
	Route::post('/network/{network_id}/person/{id}/update', 'NetworkController@updatePerson');
	Route::delete('/network/{network_id}/person/{id}/remove', 'NetworkController@removePerson');

	// Account

	Route::get('/account', 'AccountController@index' );
	Route::get('/account/get', 'AccountController@getAccount' );
	Route::post('/account/update', 'AccountController@update' );

});

