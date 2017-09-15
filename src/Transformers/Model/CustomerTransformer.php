<?php

namespace Lefamed\LaravelBillwerk\Transformers\Model;

use League\Fractal\TransformerAbstract;
use Lefamed\LaravelBillwerk\Models\Customer;

/**
 * Class CustomerTransformer
 *
 * @package Lefamed\LaravelBillwerk\Transformers\Billwerk
 */
class CustomerTransformer extends TransformerAbstract
{
	/**
	 * @param Customer $customer
	 * @return array
	 */
	public function transform(Customer $customer)
	{
		return [
			'CompanyName' => $customer->company_name,
			'EmailAddress' => $customer->email_address,
			'FirstName' => $customer->first_name,
			'LastName' => $customer->last_name,
			'Address' => [
				'Street' => $customer->street,
				'HouseNumber' => $customer->house_number,
				'PostalCode' => $customer->postal_code,
				'City' => $customer->city,
				'Country' => $customer->country
			],
			'VatId' => $customer->vat_id
		];
	}
}
