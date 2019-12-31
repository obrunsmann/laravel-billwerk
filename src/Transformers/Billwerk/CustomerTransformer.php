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
		return [
			'customer_name' => $customer->CustomerName,
			'customer_sub_name' => $customer->CustomerSubName,
			'company_name' => $customer->CompanyName ?? '',
			'first_name' => $customer->FirstName ?? '',
			'last_name' => $customer->LastName ?? '',
			'language' => $customer->Language,
			'vat_id' => $customer->VatId ?? '',
			'email_address' => $customer->EmailAddress,
			'notes' => $customer->Notes ?? '',

			'street' => $customer->Address->Street ?? '',
			'house_number' => $customer->Address->HouseNumber ?? '',
			'postal_code' => $customer->Address->PostalCode ?? '',
			'city' => $customer->Address->City ?? '',
			'country' => $customer->Address->Country ?? ''
		];
	}
}
