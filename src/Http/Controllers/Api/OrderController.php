<?php

namespace Lefamed\LaravelBillwerk\Http\Controllers\Api;

use Illuminate\Http\Request;
use Lefamed\LaravelBillwerk\Billwerk\Order;
use Lefamed\LaravelBillwerk\Http\Controllers\Controller;

/**
 * Class OrderController
 * @package Lefamed\LaravelBillwerk\Http\Controllers\Api
 */
class OrderController extends Controller
{
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function preview(Request $request)
	{
		//only allow json requests
		abort_if(!$request->isJson(), 406);

		$payload = json_decode($request->getContent());
		abort_if(!$request->planVariantId, 400);

		$planVariantId = $payload->planVariantId;
		$couponCode = $payload->couponCode ?? null;

		//create the billwerk order
		$orderClient = new Order();
		$res = $orderClient->preview($request->user()->merchant->getCustomer()->billwerk_id, $planVariantId, $couponCode);

		return response()->json($res->data()->Order);
	}

	/**
	 * Place order in REST Api
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function order(Request $request)
	{
		//only allow json requests
		abort_if(!$request->isJson(), 406);

		//find out the plan variant id
		$payload = json_decode($request->getContent());
		abort_if(!$request->planVariantId, 400);

		$planVariantId = $payload->planVariantId;

		$orderClient = new Order();
		$res = $orderClient->orderForExistingCustomer($request->user()->merchant->getCustomer()->billwerk_id, $planVariantId);

		return response()->json($res->data());
	}
}
