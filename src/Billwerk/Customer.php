<?php

namespace Lefamed\LaravelBillwerk\Billwerk;

/**
 * Class Customer
 * @package Lefamed\LaravelBillwerk\Billwerk
 */
class Customer extends BaseClient
{
	protected $resource = 'Customers';

	public function getContracts($customerId)
	{
		return $this->get($customerId, 'Contracts');
	}
}
