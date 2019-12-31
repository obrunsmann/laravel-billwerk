<?php

namespace Lefamed\LaravelBillwerk\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateCustomerRequest
 * @package Lefamed\LaravelBillwerk\Http\Requests
 */
class UpdateCustomerRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'company_name' => 'nullable|max:100',
			'first_name' => 'required|max:100',
			'last_name' => 'required|max:100',
			'street' => 'required|max:100',
			'house_number' => 'required|max:100',
			'city' => 'required|max:100',
			'postal_code' => 'required|max:5',
			'country' => 'required|max:100',
			'email_address' => 'required|max:100|email'
		];
	}
}
