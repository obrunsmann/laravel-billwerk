<?php

Route::group(['namespace' => '\Lefamed\LaravelBillwerk\Http\Controllers', 'middleware' => ['web', 'auth']], function () {
	// -- Webhook Handling -- //
	Route::post('/billwerk/webhook', 'WebhookController@handle');

	// -- Account Page -- //
	Route::get('/account', 'AccountController@index')
		->name('billwerk.account');
	Route::get('/account/edit/address', 'AccountController@editAddress')
		->name('billwerk.account.edit-address');

	// -- REST Routes -- //
	Route::resource('/customer', 'CustomerController', [
		'only' => ['update'],
		'as' => 'billwerk'
	]);
});
