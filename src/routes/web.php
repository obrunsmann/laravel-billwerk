<?php

// -- Account Page -- //
Route::get('/account', 'AccountController@index')
	->name('billwerk.account');
Route::get('/account/edit/address', 'AccountController@editAddress')
	->name('billwerk.account.edit-address');
