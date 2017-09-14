<?php

namespace Lefamed\LaravelBillwerk\Transformers\Billwerk;

use League\Fractal\TransformerAbstract;

/**
 * Class CustomerTransformer
 *
 * @package Lefamed\LaravelBillwerk\Transformers\Billwerk
 */
class CustomerTransformer extends TransformerAbstract
{
	/**
	 * A Fractal transformer.
	 *
	 * @param mixed $customer
	 * @return array
	 */
	public function transform($customer)
	{
		$transformer = [
			'customer_name' => $customer->CustomerName,
			'customer_sub_name' => $customer->CustomerSubName,
			'company_name' => $customer->CompanyName,
			'first_name' => $customer->FirstName,
			'last_name' => $customer->LastName,
			'language' => $customer->Language,
			'vat_id' => isset($customer->VatId) ? $customer->VatId : '',
			'email_address' => $customer->EmailAddress,
			'notes' => isset($customer->Notes) ? $customer->Notes : '',

			'street' => '',
			'house_number' => '',
			'postal_code' => '',
			'city' => '',
			'country' => ''
		];

		//if address is available, add to data
		if (isset($customer->Address)) {
			$transformer['street'] = $customer->Address->Street;
			$transformer['house_number'] = $customer->Address->HouseNumber;
			$transformer['postal_code'] = $customer->Address->PostalCode;
			$transformer['city'] = $customer->Address->City;
			$transformer['country'] = $customer->Address->Country;
		}

		return $transformer;
	}
}
