<?php

Route::group(['middleware' => 'auth'], function () {
	// -- Order Routes -- //
	Route::get('/order/finish', 'OrderController@finish')
		->name('billwerk.order.finish');
	Route::get('/order/{planVariantId}', 'OrderController@index')
		->name('billwerk.order');
	Route::get('/order/{planVariantId}/change-address', 'OrderController@changeAddress')
		->name('billwerk.order.changeAddress');

	// -- Account Page -- //
	Route::get('/account', 'AccountController@index')
		->name('billwerk.account');
	Route::get('/account/edit/address', 'AccountController@editAddress')
		->name('billwerk.account.edit-address');
	Route::get('/account/contract/{contractId}', 'ContractController@show');


	// -- Customer -- //
	Route::resource('/customer', 'CustomerController', [
		'only' => ['update'],
		'as' => 'billwerk'
	]);

	// -- API Routes within web middleware -- //
	Route::group(['prefix' => '/api/billing', 'namespace' => 'Api'], function () {
		Route::post('/order/preview', 'OrderController@preview');
		Route::post('/order', 'OrderController@order');

		Route::get('/contract/{contractId}/token', 'ContractController@getSelfServiceToken');
	});


});
