<?php

Route::group(['namespace' => '\Lefamed\LaravelBillwerk\Http\Controllers', 'middleware' => ['web', 'auth']], function() {
	// -- Webhook Handling -- //
	Route::post('/billwerk/webhook', 'WebhookController@handle');

	// -- Account Page -- //
	Route::get('/account', 'AccountController@index');
});
