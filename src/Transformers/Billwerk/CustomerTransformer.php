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
	 * @param object $customer
	 * @return array
	 */
	public function transform(object $customer)
	{
		return [
			'id' => $customer->Id,
			'customer_name' => $customer->CustomerName,
			'customer_sub_name' => $customer->CustomerSubName,
			'company_name' => $customer->CompanyName,
			'first_name' => $customer->FirstName,
			'last_name' => $customer->LastName,
			'language' => $customer->Language,
			'vat_id' => $customer->VatId,
			'email_address' => $customer->EmailAddress,
			'nodes' => $customer->Notes
		];
	}
}
