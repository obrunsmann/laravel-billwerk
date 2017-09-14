<?php

Route::group(['namespace' => '\Lefamed\LaravelBillwerk\Http\Controllers'], function() {
	Route::post('/billwerk/webhook', 'WebhookController@handle');
});
