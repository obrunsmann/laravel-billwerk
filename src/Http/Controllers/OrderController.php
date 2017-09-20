<?php

namespace Lefamed\LaravelBillwerk\Http\Controllers;

use Illuminate\Http\Request;
use Lefamed\LaravelBillwerk\Billwerk\Order;
use Lefamed\LaravelBillwerk\Billwerk\PlanVariant;

/**
 * Class OrderController
 *
 * @package Lefamed\LaravelBillwerk\Http\Controllers
 */
class OrderController extends Controller
{
	/**
	 * @param $planVariantId
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index($planVariantId, Request $request)
	{
		$customer = $request->user()->merchant->getCustomer();
		return view('ld-billwerk::order.index', compact('planVariantId', 'customer'));
	}
}
