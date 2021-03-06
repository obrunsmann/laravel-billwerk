<?php

namespace Lefamed\LaravelBillwerk\Http\Controllers;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Lefamed\LaravelBillwerk\Forms\CustomerAddressForm;
use Lefamed\LaravelBillwerk\Models\Customer;

/**
 * Class AccountController
 *
 * @package Lefamed\LaravelBillwerk\Http\Controllers
 */
class AccountController extends Controller
{
	use FormBuilderTrait;

	private function getCustomer(): Customer
	{
		return \Auth::user()->getBillable()->getCustomer();
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$customer = $this->getCustomer();

		//get all contracts for this customer
		$contracts = $customer->contracts;

		return view('ld-billwerk::account.index', [
			'customer' => $customer,
			'contracts' => $contracts->pluck('reference_code', 'id')
		]);
	}

	public function editAddress(Request $request)
	{
		$customer = $this->getCustomer();
		$form = $this->form(CustomerAddressForm::class, [
			'model' => $customer,
			'method' => 'PUT',
			'url' => route('billwerk.customer.update', ['customer' => $customer->id])
		]);

		return view('ld-billwerk::account.editAddress', compact('form'));
	}
}
