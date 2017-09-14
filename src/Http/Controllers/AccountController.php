<?php

namespace Lefamed\LaravelBillwerk\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class AccountController
 *
 * @package Lefamed\LaravelBillwerk\Http\Controllers
 */
class AccountController extends Controller
{
	public function index(Request $request)
	{
		$customer = $request->user()->merchant->getCustomer();
		return view('ld-billwerk::account.index', compact('customer'));
	}
}
