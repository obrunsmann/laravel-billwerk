<?php

namespace Lefamed\LaravelBillwerk\Billwerk;

/**
 * Class Order
 * @package Lefamed\LaravelBillwerk\Billwerk
 */
class Order extends BaseClient
{
	protected $resource = 'Orders';

	public function preview($customerId, $planVariantId)
	{
		return $this->post([
			'CustomerId' => $customerId,
			'Cart' => [
				'PlanVariantId' => $planVariantId
			]
		], $this->resource . '/Preview');
	}
}
