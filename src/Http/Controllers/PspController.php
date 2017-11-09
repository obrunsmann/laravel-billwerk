<?php

namespace Lefamed\LaravelBillwerk\Http\Controllers;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Lefamed\LaravelBillwerk\Forms\CustomerAddressForm;
use Lefamed\LaravelBillwerk\Models\Customer;

/**
 * Class PspController
 *
 * @package Lefamed\LaravelBillwerk\Http\Controllers
 */
class PspController extends Controller
{
	public function finalize()
	{
		return view('ld-billwerk::psp.finalize');
	}
}
