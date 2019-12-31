<?php

return [
	'api' => [
		'baseUrl' => env('BILLWERK_BASE_URL', 'https://sandbox.billwerk.com/api/v1/'),
		'authUrl' => env('BILLWERK_AUTH_URL', 'https://sandbox.billwerk.com/oauth/token')
	],

	'auth' => [
		'client_id' => env('BILLWERK_CLIENT_ID'),
		'client_secret' => env('BILLWERK_CLIENT_SECRET'),
		'token_cache_key' => 'laravel_billwerk_access_token',

		'public_key' => env('BILLWERK_PUBLIC_KEY')
	]
];
