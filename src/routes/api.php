<?php

// -- Webhook Handling -- //
Route::post('/billwerk/webhook', 'WebhookController@handle');
