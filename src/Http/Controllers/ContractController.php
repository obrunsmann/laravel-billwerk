<?php

namespace Lefamed\LaravelBillwerk\Http\Controllers;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Lefamed\LaravelBillwerk\Billwerk\Contract;
use Lefamed\LaravelBillwerk\Forms\CustomerAddressForm;
use Lefamed\LaravelBillwerk\Models\Customer;

/**
 * Class ContractController
 *
 * @package Lefamed\LaravelBillwerk\Http\Controllers
 */
class ContractController extends Controller
{
	/**
	 * @return Customer
	 */
	private function getCustomer(): Customer
	{
		return \Auth::user()->getBillable()->getCustomer();
	}

	public function show($contractId)
	{
		//only allow access if requested contract ID is part of the associated customer contracts
		$contracts = $this->getCustomer()->contracts->pluck('id');
		abort_if(!in_array($contractId, $contracts->toArray()), 404);

		return view('ld-billwerk::contract.show', [
			'contractId' => $contractId
		]);
	}
}
