<?php

namespace Lefamed\LaravelBillwerk\Http\Controllers;

use Illuminate\Http\RedirectResponse;
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
		$request->session()->forget('order');

		$customer = $request->user()->merchant->getCustomer();
		return view('ld-billwerk::order.index', compact('planVariantId', 'customer'));
	}

	public function finish()
	{
		return view('ld-billwerk::order.finish');
	}

	/**
	 * @param string $planVariantId
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function changeAddress(string $planVariantId, Request $request): RedirectResponse
	{
		$request->session()->put('order', $planVariantId);
		return redirect()->route('billwerk.account.edit-address');
	}
}
