<?php

// -- Account Page -- //
Route::get('/account', 'AccountController@index')
	->name('billwerk.account');
Route::get('/account/edit/address', 'AccountController@editAddress')
	->name('billwerk.account.edit-address');

// -- Order Routes -- //
Route::get('/order/{planVariantId}', 'OrderController@index')
	->name('billwerk.order');


// -- API Routes within web middleware -- //
Route::group(['prefix' => '/api/billing', 'namespace' => 'Api'], function () {
	Route::get('/order/preview/{planVariantId}', 'OrderController@preview');
});

