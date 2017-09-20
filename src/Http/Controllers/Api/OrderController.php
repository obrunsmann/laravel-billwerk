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
	 * @param $planVariantId
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function preview($planVariantId, Request $request)
	{
		//create the billwerk order
		$orderClient = new Order();
		$res = $orderClient->preview($request->user()->merchant->getCustomer()->billwerk_id, $planVariantId);

		return response()->json($res->data()->Order);
	}
}
