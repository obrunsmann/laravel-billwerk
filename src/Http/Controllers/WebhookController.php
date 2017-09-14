<?php

namespace Lefamed\LaravelBillwerk\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Lefamed\LaravelBillwerk\Jobs\Webhooks\CustomerChanged;

/**
 * Class WebhookController
 */
class WebhookController extends Controller
{
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function handle(Request $request): Response
	{
		//only allow json body
		if (!$request->isJson()) {
			abort(406, 'Please provide JSON body!');
		}

		//handle all the different events
		$content = json_decode($request->getContent());
		switch ($content->Event) {
			case 'CustomerChanged':
				dispatch(new CustomerChanged($content->CustomerId));
				break;
		}

		return response(null, 201);
	}
}
