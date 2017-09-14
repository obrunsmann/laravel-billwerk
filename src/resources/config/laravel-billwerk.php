<?php

return [
	'auth' => [
		'client_id' => env('BILLWERK_CLIENT_ID', ''),
		'client_secret' => env('BILLWERK_CLIENT_SECRET', ''),
		'token_cache_key' => 'laravel_billwerk_access_token'
	]
];
