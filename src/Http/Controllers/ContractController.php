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
	public function show($contractId)
	{
		return view('ld-billwerk::contract.show', [
			'contractId' => $contractId
		]);
	}
}
