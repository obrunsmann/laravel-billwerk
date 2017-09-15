<?php

// -- Webhook Handling -- //
Route::post('/billwerk/webhook', 'WebhookController@handle');

// -- REST Routes -- //
Route::resource('/customer', 'CustomerController', [
	'only' => ['update'],
	'as' => 'billwerk'
]);
