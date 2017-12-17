<?php

namespace Lefamed\LaravelBillwerk\Billwerk;

/**
 * Class Order
 *
 * @package Lefamed\LaravelBillwerk\Billwerk
 */
class Order extends BaseClient
{
	protected $resource = 'Orders';

	public function preview($customerId, $planVariantId, $couponCode = null)
	{
		return $this->post([
			'CustomerId' => $customerId,
			'Cart' => [
				'PlanVariantId' => $planVariantId,
				'CouponCode' => $couponCode,
			],
		], $this->resource.'/Preview');
	}

	/**
	 * @param $customerId
	 * @param $planVariantId
	 * @return ApiResponse
	 */
	public function orderForExistingCustomer($customerId, $planVariantId)
	{
		return $this->post([
			'CustomerId' => $customerId,
			'Cart' => [
				'PlanVariantId' => $planVariantId,
			],
		]);
	}

	/**
	 * @param $orderId
	 * @param array $payload
	 * @return \Lefamed\LaravelBillwerk\Billwerk\ApiResponse
	 */
	public function commit($orderId, $payload = [])
	{
		return $this->post($payload, null, $orderId.'/Commit/');
	}
}
