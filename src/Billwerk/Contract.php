<?php

namespace Lefamed\LaravelBillwerk\Billwerk;

/**
 * Class Contract
 * @package Lefamed\LaravelBillwerk\Billwerk
 */
class Contract extends BaseClient
{
	protected $resource = 'Contracts';

	/**
	 * @param $contractId
	 * @return ApiResponse
	 */
	public function selfServiceToken($contractId)
	{
		return $this->get($contractId, 'SelfServiceToken');
	}
}
